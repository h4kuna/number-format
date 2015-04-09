<?php

use Nette\Utils\FileSystem;

include __DIR__ . "/../vendor/autoload.php";

function dd($var /* ... */)
{
    Tracy\Debugger::enable(FALSE);
    foreach (func_get_args() as $arg) {
        \Tracy\Debugger::dump($arg);
    }
    exit;
}

Tester\Environment::setup();

$tmp = __DIR__ . '/temp/' . php_sapi_name();
FileSystem::createDir($tmp, 0755);

//$configurator = new Nette\Configurator;
//$configurator->enableDebugger($tmp);
//$configurator->setTempDirectory($tmp);
//$configurator->setDebugMode(FALSE);
//$container = $configurator->createContainer();
//return $container;



