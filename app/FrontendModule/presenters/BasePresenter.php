<?php
namespace FrontModule;

use Exception;
use Nette\Application\UI\Presenter;
use Nette\Application\UI\Template;

class BasePresenter extends Presenter {

	private ?string $infoPage;

	protected array $mailParameters;

	public function injectParameters(?string $infoPage, array $mail): void
	{
		$this->infoPage = $infoPage;
		$this->mailParameters = $mail;
	}
	public function setPageTitle($pageTitle) {
		$this->getTemplate()->pageTitle = $pageTitle;
	}

	// ----- PROTECTED METHODS

	protected function createComponentClock() {
		return new \ClockComponent();
	}

	protected function createComponentFlashMessages() {
		return new \FlashMessagesComponent();
	}
	protected function createComponentInfoList() {
		$comp = new \InfoListComponent();
		$comp->setInfoPageUrl($this->infoPage);
		return $comp;
	}

	protected function createTemplate(): Template {
		$template = parent::createTemplate();
		$template->today = date("Y-m-d H:i:s");

		return \InterlosTemplate::loadTemplate($template);
	}

	protected function beforeRender()
	{
		parent::beforeRender();
		$this->getTemplate()->noticeBoard = $this->infoPage;
	}

	protected function startUp() {
		parent::startup();
		\Interlos::prepareAdminProperties();
		\Interlos::createAdminMessages();
	}

	protected function check($componentName) {
		try {
			$this->getComponent($componentName);
			$this->getTemplate()->available = TRUE;
		}
		catch(Exception $e) {
			$this->flashMessage("Statistiky jsou momentálně nedostupné. Pravděpodobně dochází k přepočítávání.", "error");
			$this->getTemplate()->available = FALSE;
		}
	}

}
