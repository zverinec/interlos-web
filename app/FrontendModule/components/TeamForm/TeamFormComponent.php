<?php

use Dibi\DriverException;
use Nette\Forms\Form;
use Nette\Security\User;
use Tracy\Debugger;

/**
 * This form provides inserting and updating of the team.
 *
 * @author Jan Papousek
 */
class TeamFormComponent extends BaseComponent {

	const NUMBER_OF_MEMBERS = 5;

	private $mailParams;

	/** @var User */
	private $user;

	public function __construct(User $user) {
		parent::__construct();
		$this->user = $user;
	}

	public function setMailParameters($params) {
		$this->mailParams = $params;
	}

	/* SUBMITTED FORMS */

	public function insertSubmitted(Nette\Forms\Form $form) {
		if(!Interlos::isRegistrationActive()) {
			$form->addError('Momentálně neprobíhá registrace.');
			return;
		}
		$values		= $form->getValues();
		$competitors= $this->loadCompetitorsFromValues($values);
		if (!$competitors) {
			$this->getPresenter()->flashMessage("Pokoušíte se vložit školu, která již existuje.", "error");
			return;
		}
		// Check team name and e-mail because the database consistency
		$teamExists = Interlos::teams()->findAll()->where("[name] = %s", $values["team_name"], " OR [email] = %s", $values["email"])->count();
		if ($teamExists != 0) {
			$this->getPresenter()->flashMessage("Tým se stejným názvem nebo kontaktním e-mailem již existuje", "error");
			return;
		}
		try {
			// Insert team
			$insertedTeam = Interlos::teams()->insert(
					$values["team_name"],
					$values["email"],
					$values["category"],
					$values["password"]
			);
			// Send e-mail
			/** @var \Nette\Bridges\ApplicationLatte\Template $template */
			$template = InterlosTemplate::loadTemplate($this->createTemplate());
			$template->getLatte()->addFilter(null, 'Nette\Templating\Helpers::loader');
			$template->setFile(__DIR__ . "/../../templates/mail/registration.latte");
			$template->team = $values["team_name"];
			$template->id = $insertedTeam;
			$mail = new Nette\Mail\Message();
			$mail->setBody($template);
			$mail->addTo($values["email"]);
			$mail->setFrom($this->mailParams['info'], $this->mailParams['name']);
			$mail->setSubject("Interlos - registrace");
			//$mail->send();
			// Redirect
			$this->insertCompetitorsFromValues($insertedTeam, $values);
			$this->getPresenter()->flashMessage("Tým '".$values["team_name"]."' byl úspěšně zaregistrován.", "success");
			$this->getPresenter()->redirect("this");
		}
		catch (DriverException $e) {
			$this->getPresenter()->flashMessage("Chyba při práci s databází.", "error");
			Debugger::log($e, Debugger::EXCEPTION);
			return;
		}


	}

	public function updateSubmitted(Nette\Forms\Form $form) {
		$values = $form->getValues();
		$loggedTeam = Interlos::getLoggedTeam();
		if($loggedTeam == null || $values['id_team'] != $loggedTeam->id_team) {
			$form->addError('Pravděpodobně vypršelo přihlášení týmu, přihlašte se prosím znovu.');
			return;
		}
		try {
			// Update the team
			$changes = array(
					"category"  => $values["category"]
			);
			if(isSet($values["email"])) {
				$changes["email"] = $values["email"];
			}
			if (!empty($values["password"])) {
				$changes["password"] = TeamAuthenticator::passwordHash($values["password"]);
			}
			Interlos::teams()->update($changes)->where("[id_team] = %i", $values["id_team"])->execute();
			// Update competitors
			Interlos::competitors()->deleteByTeam($values["id_team"]);
			$this->insertCompetitorsFromValues($values["id_team"], $values);
			// Success
			$this->getPresenter()->flashMessage("Tým byl úspěšně aktualizován.", "success");
			$this->getPresenter()->redirect("this");
		}
		catch (Nette\InvalidArgumentException $e) {
			$this->getPresenter()->flashMessage("Tým musí mít alespoň jednoho člena.", "error");
			Debugger::log($e, Debugger::EXCEPTION);
		}
		catch (DuplicityException $e) {
			$this->getPresenter()->flashMessage("Daný tým již existuje.", "error");
			Debugger::log($e, Debugger::EXCEPTION);
		}
		catch (DriverException $e) {
			$this->getPresenter()->flashMessage("Chyba při práci s databází.", "error");
			Debugger::log($e, Debugger::EXCEPTION);
		}
	}

	/* PROTECTED METHODS */

	protected function createComponentTeamForm($name) {
		$form = new BaseForm($this, $name);

		$form->addGroup("Tým");

		// Team name
		$form->addText("team_name", "Název týmu")->addRule(Nette\Forms\Form::FILLED, "Název týmu musí být vyplněn");

		// Password
		$form->addPassword("password", "Heslo");
		$form->addPassword("password_check", "Kontrola hesla")
			->setRequired(false)
				->addConditionOn($form["password"], Nette\Forms\Form::FILLED)
				->addRule(Nette\Forms\Form::EQUAL, "Heslo a kontrola hesla se neshodují.", $form["password"]);

		// Category
		$form->addSelect("category", "Kategorie", array(
				TeamsModel::HIGH_SCHOOL	=> "Středoškoláci",
				TeamsModel::COLLEGE	=> "Vysokoškoláci",
				TeamsModel::OTHER	=> "Ostatní",
		));

		// E-mails
		$form->addText("email", "E-mail")->addRule(Nette\Forms\Form::EMAIL, "E-mail nemá správný formát.");

		$schools = Interlos::schools()->findAll()->orderBy("name")->fetchPairs("id_school", "name");
		$schools = array(NULL => "Nevyplněno") + $schools + array("other" => "Jiná");

		// Members
		for ($i=1; $i<=self::NUMBER_OF_MEMBERS; $i++) {
			$form->addGroup("$i. člen");
			$form->addText($i."_competitor_name", "Jméno");
			$form->addSelect($i."_school", "Škola", $schools)
					->addConditionOn($form[$i."_competitor_name"], Nette\Forms\Form::FILLED)
					->addRule(~Nette\Forms\Form::EQUAL, "U $i. člena je vyplněno jméno, ale není u něj vyplněna škola.", NULL)
					->endCondition()
					->addCondition(Nette\Forms\Form::EQUAL, "other")
					->toggle("frm".$name."-".$i."_otherschool")
					->toggle("frm".$name."-".$i."_otherschool-label");
			$form->addText($i."_otherschool", "Jiná škola")
					->addConditionOn($form[$i."_competitor_name"], Nette\Forms\Form::FILLED)
					->addConditionOn($form[$i."_school"], Nette\Forms\Form::EQUAL, "other")
					->addRule(Nette\Forms\Form::FILLED, "U $i. člena je vyplněno jméno, ale není u něj vyplněna škola.");
			$form[$i."_otherschool"]->getLabelPrototype()->id = "frm".$name."-".$i."_otherschool-label";
			$form[$i."_school"]->setOption("description", "Pokud není vaše škola přítomna vyberte položku \"Jiná\".");
			if ($i == 1) {
				$form[$i."_competitor_name"]->addRule(Nette\Forms\Form::FILLED, "Jméno prvního člena musí být vyplněno.");
			}
		}

		$defaults = array();

		if ($this->user->isLoggedIn()) {
			$loggedTeam = Interlos::getLoggedTeam();
			$defaults += array(
					"team_name"	=> $loggedTeam->name,
					"email"	=> $loggedTeam->email,
					"category"	=> $loggedTeam->category,
					"id_team"	=> $loggedTeam->id_team
			);
			$competitors = Interlos::competitors()->findAllByTeam($loggedTeam->id_team)->orderBy("id_competitor")->fetchAll();
			$counter = 1;
			foreach($competitors AS $competitor) {
				$defaults += array(
						$counter . "_competitor_name"	=> $competitor->name,
						$counter . "_school"		=> $competitor->id_school
				);
				$counter++;
			}
			$form["team_name"]->setDisabled();
			if (Interlos::isRegistrationEnd()) {
				$form["email"]->setDisabled();
			}
			$form->addHidden("id_team");
			$form->setCurrentGroup(null);
			$form->addSubmit("update", "Upravit");
			$form->onSuccess[] = array($this, "updateSubmitted");
		} else {
			$form["password"]->addRule(Nette\Forms\Form::FILLED, "Není vyplněno heslo týmu.");
			$form->setCurrentGroup(null);
			$form->addReCaptcha('recaptcha', $label = 'Ochrana před spamboty', $required = TRUE, $message = 'Jste živý člověk?');
			$form->addSubmit("insert", "Registrovat");
			$form->onSuccess[] = array($this, "insertSubmitted");
		}

		$form->setDefaults($defaults);
		return $form;
	}

	// ---- PRIVATE METHODS

	private function insertCompetitorsFromValues($team, $values) {
		$competitors = $this->loadCompetitorsFromValues($values);
		foreach($competitors AS $competitor) {
			if (!empty($competitor['otherschool'])) {
				$competitor['school'] = Interlos::schools()->insert($competitor['otherschool']);
			}
			Interlos::competitors()->insert($team, $competitor['school'], $competitor['name']);
		}
	}

	private function loadCompetitorsFromValues($values) {
		$competitors = array();
		$schoolsToInsert = array();
		for($i=1; $i <= 5; $i++) {
			if (!empty($values[$i."_competitor_name"])) {
				$competitor = [];
				$competitor["name"]		= $values[$i."_competitor_name"];
				$competitor["school"]	= $values[$i."_school"];
				$competitor["otherschool"]	= $values[$i."_otherschool"];
				if (!empty($competitor["otherschool"])) {
					$schoolsToInsert[] = $competitor["otherschool"];
				}
				$competitors[] = $competitor;
			}
		}
		if(count($schoolsToInsert) === 0) {
			$schoolExists = 0;
		} else {
			$schoolExists = Interlos::schools()->findAll()->where("[name] IN %l", $schoolsToInsert)->count();
		}
		if ($schoolExists) {
			return FALSE;
		}
		else {
			return $competitors;
		}
	}

}
