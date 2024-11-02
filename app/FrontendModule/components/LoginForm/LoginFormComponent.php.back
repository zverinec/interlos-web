<?php

use Nette\Mail\Mailer;
use Tracy\Debugger;

class LoginFormComponent extends BaseComponent
{
	private $mailParams;

	private Mailer $mailer;

	public function __construct(Mailer $mailer) {
		parent::__construct();
		$this->mailer = $mailer;
	}

	public function setMailParameters($params) {
		$this->mailParams = $params;
	}

	public function formSubmitted(Nette\Forms\Form $form) {
		$values = $form->getValues();

		if ($form['reset']->isSubmittedBy()) {
			$row = Interlos::teams()->findAll()->where("[name] = %s", $values['name'])->fetch();
			if (!$row) {
				$this->getPresenter()->flashMessage("Daný tým neexistuje.", "error");
				return;
			}
			// Insert team
			$code = sha1(\Nette\Utils\Random::generate(160));
			Interlos::teams()->update(['reset_code' => $code])->where("[id_team] = %i", $row->id_team)->execute();
			// Send e-mail
			/** @var \Nette\Bridges\ApplicationLatte\Template&\Nette\Application\UI\Template $template */
			$template = InterlosTemplate::loadTemplate($this->createTemplate());
			$template->setFile(__DIR__ . "/../../templates/mail/resetPassword.latte");
			$template->team = $values['name'];
			$template->id = $row->id_team;
			$template->code = $code;
			$mail = new Nette\Mail\Message();
			$mail->setHtmlBody($template->renderToString());
			$mail->addTo($row->email);
			$mail->setFrom($this->mailParams['info'], $this->mailParams['name']);
			$mail->setSubject("Interlos - žádost o změnu hesla");
			$this->mailer->send($mail);

			$this->getPresenter()->flashMessage("Týmu '".$row->name."' byl úspěšně zaslán e-mail s instrukcemi pro změnu hesla.", "success");
			$this->getPresenter()->redirect("this");
			return;
		}

		try {
			Interlos::getUser()->login($values['name'], $values['password']);
		}
		catch(Nette\Security\AuthenticationException $e) {
			if ($e->getCode() == Nette\Security\Authenticator::IDENTITY_NOT_FOUND) {
			$this->getPresenter()->flashMessage("Daný tým neexistuje.", "error");
			}
			else {
			$this->getPresenter()->flashMessage("Nesprávné heslo", "error");
			}
			return;
		}
		catch(Exception $e) {
			$this->getPresenter()->flashMessage("Stala se neočekávaná chyba.", "error");
			Debugger::log($e, Debugger::EXCEPTION);
			return;
		}
		$this->getPresenter()->redirect("Team:default");
	}

	// ---- PROTECTED METHODS

	protected function createComponentForm($name) {
		$form = new BaseForm($this, $name);

		$nameField = $form->addText("name", "Název týmu");
		$nameField
			->addRule(Nette\Forms\Form::FILLED, "Název týmu musí být vyplněn.");

		$form->addPassword("password", "Heslo")
			->addRule(Nette\Forms\Form::FILLED, "Heslo musí být vyplněno.");

		$recaptcha = $form->addReCaptcha('recaptcha', $label = 'Ochrana před spamboty', $required = TRUE, $message = 'Jste živý člověk?')
			->setRequired('Ochrana před spamboty je povinná.');
		$form->addSubmit("login", "Přihlásit se");
		$form->addSubmit("reset", "Resetovat heslo")
			->setValidationScope([$nameField, $recaptcha]);
		$form->onSuccess[] = array($this, "formSubmitted");

		return $form;
	}

}
