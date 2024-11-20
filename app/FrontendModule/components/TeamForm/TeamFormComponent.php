<?php

use Dibi\DriverException;
use Nette\Forms\Form;
use Nette\Mail\Mailer;
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

	private User $user;

	private Mailer $mailer;

	public function __construct(User $user, Mailer $mailer) {
		parent::__construct();
		$this->user = $user;
		$this->mailer = $mailer;
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
		$values = $form->getValues();
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

		// This is a hack protection agains spambots (they usually insert same string to all text fields)
		$names = array_map(function ($c) { return $c["name"]; }, $competitors);
		if ((count(array_unique($names)) < count($names)) && (count($names) > 1)) {
			$form->addError('Jména členů musí být různá.');
			return;
		}

		try {
			// Insert team
			$insertedTeam = Interlos::teams()->insert(
				$values["team_name"],
				$values["email"],
				$values["category"],
				$values["password"],
				$values["source"] === '' ? NULL : $values["source"]
			);
			// Send e-mail
			/** @var \Nette\Bridges\ApplicationLatte\Template&\Nette\Application\UI\Template $template */
			$template = InterlosTemplate::loadTemplate($this->createTemplate());
			$template->setFile(__DIR__ . "/../../templates/mail/registration.latte");
			$template->team = $values["team_name"];
			$template->id = $insertedTeam;
			$mail = new Nette\Mail\Message();
			$mail->setHtmlBody($template->renderToString());
			$mail->addTo($values["email"]);
			$mail->setFrom($this->mailParams['info'], $this->mailParams['name']);
			$mail->setSubject("Interlos - registrace");
			$this->mailer->send($mail);
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
				"category" => $values["category"],
				"source" => $values["source"] === '' ? NULL : $values["source"]
			);
			if(!empty($values["team_name"])) {
				$changes["name"] = $values["team_name"];
			}
			$nameUsed = Interlos::teams()->findAll()->where("[name] = %s", $values["team_name"], " AND [id_team] != %s", $values["id_team"])->count();
			if($nameUsed != 0) {
				throw new DuplicityException("Daný tým již exituje");
			}
			if(isSet($values["email"])) {
				$changes["email"] = $values["email"];
			}
			if (!empty($values["password"])) {
				$changes["password"] = TeamAuthenticator::passwordHash($values["password"]);
			}
			Interlos::teams()->update($changes)->where("[id_team] = %i", $loggedTeam->id_team)->execute();
			// Update competitors
			Interlos::competitors()->deleteByTeam($loggedTeam->id_team);
			$this->insertCompetitorsFromValues($loggedTeam->id_team, $values);
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
		$form->addText("email", "E-mail")
			->setRequired('Vyplňte, prosím, e-mail.')
			->addRule(Nette\Forms\Form::EMAIL, "E-mail nemá správný formát.");

		$schools = Interlos::schools()->findAll()->orderBy("name")->fetchPairs("id_school", "name");
		$schools = array(NULL => "-- Nevyplněno/jiná --") + $schools;

		// Source
		$form->addSelect("source", "Odkud jste se o soutěži dozvěděli", array(
			TeamsModel::SRC_WEB => "z tohoto webu",
			TeamsModel::SRC_FB => "z facebooku",
			TeamsModel::SRC_IG => "z instagramu",
			TeamsModel::SRC_PAPER => "z plakátku",
			TeamsModel::SRC_FRIENDS => "od známých",
			TeamsModel::SRC_EMAIL => "z e-mailu",
			TeamsModel::SRC_PUZZLE => "šifrovačky.cz",
			TeamsModel::SRC_NOT_DEFINED => "nevyplněno / jiné",
		));

		// Members
		for ($i=1; $i<=self::NUMBER_OF_MEMBERS; $i++) {
			$form->addGroup("$i. člen");
			$form->addText($i."_competitor_name", "Jméno");
			$form->addSelect($i."_school", "Škola", $schools)
				->setRequired(false)
				->addCondition(Form::FILLED)
					->toggle($i."_otherschool_container", false);
			$form->addText($i."_otherschool", "Jiná škola")
				->setOption('id', $i."_otherschool_container");
			$form[$i."_otherschool"]->getLabelPrototype()->id = "frm".$name."-".$i."_otherschool-label";
			$form[$i."_school"]->setOption("description", "Nenašli jste svoji školu? Vyberte \"Nevyplněno/jiná\" a zadejte název.");
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
				"source"	=> $loggedTeam->source === NULL ? '' : $loggedTeam->source,
				"id_team"	=> $loggedTeam->id_team
			);
			$competitors = Interlos::competitors()->findAllByTeam($loggedTeam->id_team)->orderBy("id_competitor")->fetchAll();
			$counter = 1;
			foreach($competitors as $competitor) {
				$defaults += array(
					$counter . "_competitor_name" => $competitor->name,
					$counter . "_school" => $competitor->id_school
				);
				$counter++;
			}
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
			$form->addReCaptcha('recaptcha', $label = 'Ochrana před spamboty', $required = TRUE, $message = 'Jste živý člověk?')
				->setRequired('Ochrana před spamboty je povinná.');
			$form->addSubmit("insert", "Registrovat");
			$form->onSuccess[] = array($this, "insertSubmitted");
		}

		$form->setDefaults($defaults);
		return $form;
	}

	// ---- PRIVATE METHODS

	private function insertCompetitorsFromValues($team, $values) {
		$competitors = $this->loadCompetitorsFromValues($values);
		foreach($competitors as $competitor) {
			if (!empty($competitor['name']) && empty($competitor['school']) && !empty($competitor['otherschool'])) {
				$schoolId = Interlos::schools()->getByName($competitor['otherschool']);
				if ($schoolId) {
					$competitor['school'] = $schoolId;
				} else {
					$competitor['school'] = $schoolId = Interlos::schools()->insert($competitor['otherschool']);
				}
			}
			Interlos::competitors()->insert($team, $competitor['school'], $competitor['name']);
		}
	}

	private function loadCompetitorsFromValues($values) {
		$competitors = [];
		for($i=1; $i <= 5; $i++) {
			if (!empty($values[$i."_competitor_name"])) {
				$competitor = [];
				$competitor["name"] = $values[$i."_competitor_name"];
				$competitor["school"] = $values[$i."_school"];
				$competitor["otherschool"] = $values[$i."_otherschool"];
				$competitors[] = $competitor;
			}
		}
		return $competitors;
	}

}
