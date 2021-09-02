<?php
namespace FrontModule;

use Nette\Application\BadRequestException;
use Nette\Mail\IMailer;
use Nette\Security\AuthenticationException;

class DefaultPresenter extends BasePresenter {

	public function actionLogout() {
		$this->user->logout(TRUE);
		\Interlos::cleanPasswordResetTokens();
		$this->redirect("default");
	}

	public function renderChat() {
		$this->getComponent("chat")->setSource(\Interlos::chat()->findAll());
		$this->setPageTitle("Diskuse");
	}

	public function renderDefault() {
		$this->setPagetitle("INTERnetová LOgická Soutěž");
	}

	public function renderLastYears() {
		$this->setPagetitle("Minulé ročníky");
	}

	public function renderLogin() {
		\Interlos::cleanPasswordResetTokens();
		$this->setPagetitle("Přihlásit se");
	}

	public function renderResetPassword($code = '') {
		\Interlos::cleanPasswordResetTokens();
		$this->setPagetitle("Změna hesla");
	}

	// ----- PROTECTED METHODS

	protected function createComponentChat($name) {
		$chat = new \ChatListComponent();
		return $chat;
	}

	protected function createComponentLogin($name) {
		$comp = new \LoginFormComponent($this->context->getByType(IMailer::class));
		$comp->setMailParameters($this->context->parameters['mail']);
		return $comp;
	}

	protected function createComponentResetPassword($name) {
		$comp = new \ResetPasswordFormComponent($this->getParameter('code'), $this->getParameter('name'));
		return $comp;
	}

}
