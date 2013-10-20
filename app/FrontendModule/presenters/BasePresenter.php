<?php
namespace FrontModule;

use Nette\Application\UI\Presenter;

class BasePresenter extends Presenter {

	public function setPageTitle($pageTitle) {
		$this->getTemplate()->pageTitle = $pageTitle;
	}

	// ----- PROTECTED METHODS

	protected function createComponentClock($name) {
		return new \ClockComponent($this, $name);
	}

	protected function createComponentFlashMessages($name) {
		return new \FlashMessagesComponent($this, $name);
	}

	protected function createTemplate($class = NULL) {
		$template = parent::createTemplate($class);
		$template->today = date("Y-m-d H:i:s");

		return \InterlosTemplate::loadTemplate($template);
	}

	protected function startUp() {
		parent::startup();
        \Interlos::prepareAdminProperties();
        \Interlos::createAdminMessages();
	}

}
