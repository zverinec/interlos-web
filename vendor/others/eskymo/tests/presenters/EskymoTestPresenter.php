<?php
/**
 * Description of EskymoTestPresenter
 *
 * @author Jan Papousek
 */
abstract class EskymoTestPresenter extends Presenter
{

	protected function createTemplate() {
		$template = parent::createTemplate();

		$template->registerFilter('CurlyBracketsFilter::invoke');

		$template->setFile(dirname(__FILE__) . "/../templates/EskymoTest.default.phtml");

		$template->tested = array();

		return $template;
	}

	protected function beforeRender() {
		// Testing is enabled just when Debug is enabled
		if (!Debug::isEnabled()) {
			die();
		}
		// Load directory with tests
		$debug = Environment::getConfig("debug");
		if (empty($debug->testDir)) {
			throw new InvalidStateException("Test directory is not set.");
		}
		// Register loader for directory
		$loader = new RobotLoader();
		$loader->addDirectory($debug->testDir);
		$loader->register();
	}

	public function renderDefault() {
		$numberOfSuccessfull = 0;

		// Load test classes from config file
		$config = Config::fromFile(Environment::getConfig("debug")->testDir . "/tests.ini");
		$tests = empty($config->tests) ? array() : $config->tests;
		// Run tests
		foreach ($tests AS $class) {
			$test = new $class();
			if (!($test instanceof IEskymoTest)) {
				throw new NotSupportedException("The test class '".get_class($test)."' does not implement IEskymoTest.");
			}
			$result = $test->run();
			$this->getTemplate()->tested[get_class($test)] = $result;
			if ($result->isSuccessful()) {
				$numberOfSuccessfull++;
			}
		}
		$this->getTemplate()->successful = $numberOfSuccessfull;
	}

	public function renderShow($class, $method = NULL) {
		$numberOfSuccessfull = 0;
		$test = new $class();
		if (!($test instanceof IEskymoTest)) {
			throw new NotSupportedException("The test class '".get_class($test)."' does not implement IEskymoTest.");
		}
		$result = $test->run();
		if (!empty($method) && !$result->isSuccessful()) {
			foreach ($result->getTested() AS $name => $info) {
				if ($name == $method && $info instanceof Exception) {
					Debug::_paintBlueScreen($info);
				}
			}
		}
		if ($result->isSuccessful()) {
			$numberOfSuccessfull++;
		}
		$this->getTemplate()->successful = $numberOfSuccessfull;
		$this->getTemplate()->tested[get_class($test)] = $result;
	}
}