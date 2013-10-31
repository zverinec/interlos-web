<?php
class LoginFormComponent extends BaseComponent
{

    public function formSubmitted(Nette\Forms\Form $form) {
	$values = $form->getValues();

	try {
	    Interlos::getUser()->login($values['name'], $values['password']);
	}
	catch(Nette\Security\AuthenticationException $e) {
	    if ($e->getCode() == Nette\Security\IAuthenticator::IDENTITY_NOT_FOUND) {
		$this->getPresenter()->flashMessage("Daný tým neexistuje.", "error");
	    }
	    else {
		$this->getPresenter()->flashMessage("Nesprávné heslo", "error");
	    }
	    return;
	}
	catch(Exception $e) {
	    $this->getPresenter()->flashMessage("Stala se neočekávaná chyba.", "error");
	    Nette\Diagnostics\Debugger::processException($e);
	    return;
	}
	$this->getPresenter()->redirect("Team:default");
    }

    // ---- PROTECTED METHODS

    protected function createComponentForm($name) {
	$form = new BaseForm($this, $name);

	$form->addText("name", "Název týmu")
	    ->addRule(Nette\Forms\Form::FILLED, "Název týmu musí být vyplněn.");

	$form->addPassword("password", "Heslo")
	    ->addRule(Nette\Forms\Form::FILLED, "Heslo musí být vyplněno.");

	$form->addSubmit("login", "Přihlásit se");
	$form->onSuccess[] = array($this, "formSubmitted");

	return $form;
    }

}
