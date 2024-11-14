<?php

use Nette\Mail\Mailer;

class ResetPasswordFormComponent extends BaseComponent
{
	private $codeFromUrl;

	private $nameFromUrl;

	private $mailParams;

	private Mailer $mailer;

	public function __construct($codeFromUrl, $nameFromUrl, Mailer $mailer)
	{
		parent::__construct();
		$this->codeFromUrl = $codeFromUrl;
		$this->nameFromUrl = $nameFromUrl;
		$this->mailer = $mailer;
	}

	public function setMailParameters($params) {
		$this->mailParams = $params;
	}


	public function formSubmitted(Nette\Forms\Form $form) {
		$values = $form->getValues();

		if ($this->codeFromUrl == '') {
			$row = Interlos::teams()->findAll()->where("[email] = %s", $values['email'])->fetch();
			if (!$row) {
				$this->getPresenter()->flashMessage("Pod tímto e-mailem není zaregistrován žádný tým", "error");
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

		else {
			$row = Interlos::teams()->findAll()->where("[name] = %s AND [reset_code] IS NOT NULL AND [reset_code] = %s", $values['name'], $values['code'])->fetch();
			if (!$row) {
				$this->getPresenter()->flashMessage("Daný tým neexistuje nebo je kód pro obnovu hesla již neplatný.", "error");
				return;
			}

			Interlos::teams()->update(['reset_code' => null, 'password' => TeamAuthenticator::passwordHash($values['password'])])->where("[id_team] = %i", $row->id_team)->execute();
			$this->getPresenter()->flashMessage("Týmu '".$row->name."' bylo úspěšně změněho heslo a byl rovnou přihlášen.", "success");
			Interlos::getUser()->login($values['name'], $values['password']);
			$this->getPresenter()->redirect("Team:default");
		}
	}

	// ---- PROTECTED METHODS

	protected function createComponentForm($name) {
		$form = new BaseForm($this, $name);

		if ($this->codeFromUrl == '') {
			$nameField = $form->addText("email", "Týmový e-mail");

			$form->addSubmit("reset", "Resetovat heslo")
				->setValidationScope([$nameField]);
			$form->onSuccess[] = array($this, "formSubmitted");
		}

		else {
			$nameField = $form->addText("name", "Název týmu");
			$nameField
				->setRequired(true)
				->addRule(Nette\Forms\Form::FILLED, "Název týmu musí být vyplněn.");

			$form->addText('code', 'Kód')
				->setRequired(true)
				->addRule(\Nette\Forms\Form::FILLED, 'Kód pro obnovu hesla musí být vyplněn.');

			$form->addPassword("password", "Heslo")
				->setRequired(true)
				->addRule(Nette\Forms\Form::FILLED, "Heslo musí být vyplněno.");
			$form->addPassword("password2", "Heslo pro kontrolu")
				->setRequired(true)
				->addRule(Nette\Forms\Form::FILLED, "Heslo pro kontrolu musí být vyplněno.")
				->addRule(\Nette\Forms\Form::EQUAL, 'Hesla se musí shodovat', $form['password']);

			$form->addReCaptcha('recaptcha', $label = 'Ochrana před spamboty', $required = TRUE, $message = 'Jste živý člověk?')
				->setRequired('Ochrana před spamboty je povinná.');
			$form->addSubmit("reset", "Nastavit nové heslo");
			$form->onSuccess[] = array($this, "formSubmitted");
			$form->setDefaults(['code' => $this->codeFromUrl, 'name' => $this->nameFromUrl]);
		}

	return $form;
	}

}
