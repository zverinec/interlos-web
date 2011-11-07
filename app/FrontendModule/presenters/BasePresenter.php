<?php
class Frontend_BasePresenter extends Presenter {

	public function setPageTitle($pageTitle) {
		$this->getTemplate()->pageTitle = $pageTitle;
	}

	// ----- PROTECTED METHODS

	protected function createComponentClock($name) {
		return new ClockComponent($this, $name);
	}

	protected function createComponentFlashMessages($name) {
		return new FlashMessagesComponent($this, $name);
	}

	protected function createTemplate() {
		$this->oldLayoutMode = false;

		$template = parent::createTemplate();
		$template->today = date("Y-m-d H:i:s");

		return InterlosTemplate::loadTemplate($template);
	}

	protected function startUp() {
		parent::startup();
        Interlos::prepareAdminProperties();
        Interlos::createAdminMessages();
		$this->oldModuleMode = FALSE;
	}

}