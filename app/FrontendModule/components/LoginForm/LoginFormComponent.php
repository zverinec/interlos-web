<?php

use Tracy\Debugger;

class LoginFormComponent extends BaseComponent
{

	public function formSubmitted(Nette\Forms\Form $form) {
		$values = $form->getValues();

		if ($form['reset']->isSubmittedBy()) {
			$this->getPresenter()->redirect("Default:resetPassword");
			return;
		}

		try {
			Interlos::getUser()->login($values['name'], $values['password']);
		} catch (Nette\Security\AuthenticationException $e) {
			if ($e->getCode() == Nette\Security\Authenticator::IDENTITY_NOT_FOUND) {
				$this->getPresenter()->flashMessage("Daný tým neexistuje.", "error");
			} else {
				$this->getPresenter()->flashMessage("Nesprávné heslo", "error");
			}
			return;
		} catch (Exception $e) {
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

		$form->addPassword("password", "Heslo");

		$form->addSubmit("login", "Přihlásit se");
		$form->addSubmit("reset", "Resetovat heslo")
			->setValidationScope([$nameField]);
		$form->onSuccess[] = array($this, "formSubmitted");

		return $form;
	}
}
