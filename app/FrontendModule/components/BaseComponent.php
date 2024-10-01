<?php

use Nette\Application\UI\Template;
use Nette\ComponentModel\IComponent;

abstract class BaseComponent extends Nette\Application\UI\Control {

	public function __construct()
	{
		$this->monitor(\Nette\Application\UI\Presenter::class, function ($presenter): void {
			$this->startUp();
		});
	}

	public function render() {
		$this->beforeRender();
		$this->getTemplate()->render();

	}

	protected function createTemplate(?string $class = null): Template {
		$template = parent::createTemplate();

		$componentName = strtr(self::getReflection()->getName(), array("Component" => ""));

		$template->setFile(
				__DIR__ . "/" .
				$componentName . "/" .
				ExtraString::lowerFirst($componentName) . ".latte"
		);

		return InterlosTemplate::loadTemplate($template);
	}

	protected function getPath() {
		$componentName = strtr(self::getReflection()->getName(), array("Component" => ""));
		return __DIR__ . "/" . $componentName . "/";
	}

	protected function beforeRender() {

	}

	protected function createComponentFlashMessages() {
		return new FlashMessagesComponent();
	}

	protected function startUp() {}
}
