<?php
abstract class BaseComponent extends Nette\Application\UI\Control {
	public function __construct() {
		parent::__construct();

	}

	protected function attached($form)
	{
		parent::attached($form);
		$this->startUp();
	}

	public function render() {
		$this->beforeRender();
		$this->getTemplate()->render();

	}

	protected function createTemplate() {
		$template = parent::createTemplate();

		$componentName = strtr($this->getReflection()->getName(), array("Component" => ""));

		$template->setFile(
				dirname(__FILE__) . "/" .
				$componentName . "/" .
				ExtraString::lowerFirst($componentName) . ".latte"
		);

		return InterlosTemplate::loadTemplate($template);
	}

	protected function getPath() {
		$componentName = strtr($this->getReflection()->getName(), array("Component" => ""));
		return dirname(__FILE__) . "/" . $componentName . "/";
	}

	protected function beforeRender() {

	}

	protected function createComponentFlashMessages($name) {
		return new FlashMessagesComponent();
	}

	protected function startUp() {}
}
