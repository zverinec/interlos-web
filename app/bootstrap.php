<?php
// Step 1: Load Nette Framework
// this allows Nette to load classes automatically so that
// you don't have to litter your code with 'require' statements
require_once LIBS_DIR . '/Nette/loader.php';

// Step 2: Register auto loader
$loader = new RobotLoader();
$loader->addDirectory(APP_DIR);
$loader->addDirectory(LIBS_DIR);
$loader->register();

// Step 3: Enable Nette\Debug
// for better exception and error visualisation

$debug = Environment::getConfig('debug');

Environment::loadConfig(APP_DIR . '/config.ini');

$debug = Environment::getConfig("debug");

if ($debug->enable) {

	Debug::enable(Debug::DEVELOPMENT, $debug->log, $debug->email);

	Environment::getApplication()->catchExceptions = false;

	if ($debug->profiler) {
		Debug::enableProfiler();
	}
}

// Step 4: Set up the sessions.
// FIXME
Environment::getSession()->setExpiration(60*60*24*7);
Environment::getUser()->setNamespace("interlos");

// Step 5: Get the front controller
$application = Environment::getApplication();

// Step 6: Setup application router
$router = $application->getRouter();
$router[] = FrontendModule::createRouter();

// Step 7: Connect to the database
dibi::connect(Environment::getConfig("database"));

// Step 8: Reset temporary tables
Interlos::resetTemporaryTables();

// Step 9: Run the application!
$application->run();
