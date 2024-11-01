<?php
# In a case of emergency uncomment one of following lines
# include_once __DIR__ . '/templates/.maintenance.phtml';

# Use this when problem in time of contest occures
use Nette\Bootstrap\Configurator;
use Nette\DI\Container;

$problem = FALSE; // Set to true and add whitelisted IPs
$remoteIP = $_SERVER['REMOTE_ADDR'];
$allowedIP = array("127.0.0.1", "::1", "192.168.99.1", "172.24.0.1", "172.20.0.1");
if ($problem && !in_array($remoteIP, $allowedIP, true)) {
	include_once __DIR__ . '/templates/.problem.phtml';
}

// Load Nette Framework or autoloader generated by Composer
require __DIR__ . '/../vendor/autoload.php';

$configurator = new Configurator();

// Enable Nette Debugger for error visualisation & logging
$configurator->setDebugMode(in_array($remoteIP, $allowedIP));
$configurator->enableTracy(__DIR__ . '/../log');

// Set proper timezone and set up a temp directory
$configurator->setTimeZone('Europe/Prague');
$configurator->setTempDirectory(__DIR__ . '/../temp');

// Enable RobotLoader - this will load all classes automatically
$configurator->createRobotLoader()
	->addDirectory(__DIR__)
	->addDirectory(__DIR__ . '/../vendor/others')
	->register();

// Create Dependency Injection container from config.neon file
$configurator->addConfig(__DIR__ . '/config/config.neon');
// Tuning config with local only settings like passwords etc.
if (file_exists(__DIR__ . '/config/config.local.neon')) {
	$configurator->addConfig(__DIR__ . '/config/config.local.neon');
}

/** @var Container $container */
$container = $configurator->createContainer();

// Create directory for session data (Nette does not seem to create it on its own, then fails to initialize session)
//@mkdir($container->session->options['save_path'], 0777, true);
// TODO

Interlos::injectContainer($container);

return $container;
