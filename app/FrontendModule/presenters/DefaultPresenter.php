<?php
namespace FrontModule;

use Nette\DI\Attributes\Inject;
use Nette\Mail\Mailer;

class DefaultPresenter extends BasePresenter {

    #[Inject]
    public Mailer $mailer;

    public function actionLogout(): never {
        $this->user->logout(TRUE);
        \Interlos::cleanPasswordResetTokens();
        $this->redirect("default");
    }

    public function renderChat(): void {
        $this->getComponent("chat")->setSource(\Interlos::chat()->findAll());
        $this->setPageTitle("Diskuse");
    }

    public function renderDefault(): void {
        $this->setPagetitle("INTERnetová LOgická Soutěž");
    }

    public function renderLastYears(): void {
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

    protected function createComponentChat() {
        return new \ChatListComponent();
    }

    protected function createComponentLogin() {
        return new \LoginFormComponent();
    }

    protected function createComponentResetPassword() {
        $comp = new \ResetPasswordFormComponent($this->getParameter('code'), $this->getParameter('name'), $this->mailer);
        $comp->setMailParameters($this->mailParameters);
        return $comp;
    }

}
