<?php

require __DIR__ . '/../libs/autoload.php';

if (!include __DIR__ . '/../libs/nette/tester/Tester/bootstrap.php') {
	die('Install Nette Tester using `composer update --dev`');
}

function id($val) {
	return $val;
}

$configurator = new Nette\Config\Configurator;
$configurator->setDebugMode(FALSE);
$configurator->setTempDirectory(__DIR__ . '/../temp');
$configurator->createRobotLoader()
	->addDirectory(__DIR__ . '/../app')
	->register();

$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
$configurator->addConfig(__DIR__ . '/config.neon', $configurator::NONE); // none section

$configurator->onCompile[] = function ($configurator, $compiler) {
    $compiler->addExtension('modules', new VojtechDobes\ExtensionsList);
};

return $configurator->createContainer();
