<?php declare(strict_types=1);

use Nette\Utils;

include __DIR__ . "/../vendor/autoload.php";

Tester\Environment::setup();

$tmp = __DIR__ . '/temp/' . php_sapi_name();
Utils\FileSystem::createDir($tmp, 0755);
