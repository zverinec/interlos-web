<?php

class ResetPasswordFormComponent extends BaseComponent
{
	private $codeFromUrl;

	private $nameFromUrl;

	public function __construct($codeFromUrl, $nameFromUrl)
	{
		parent::__construct();
		$this->codeFromUrl = $codeFromUrl;
		$this->nameFromUrl = $nameFromUrl;
	}

	public function formSubmitted(Nette\Forms\Form $form) {
		$values = $form->getValues();


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

	// ---- PROTECTED METHODS

	protected function createComponentForm($name) {
		$form = new BaseForm($this, $name);

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

	return $form;
	}

}
