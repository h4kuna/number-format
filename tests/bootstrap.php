<?php declare(strict_types=1);

use h4kuna\Number\Format;

require_once __DIR__ . '/../vendor/autoload.php';

function nbsp(string $value): string
{
	return str_replace(' ', Format::NBSP, $value);
}


Tester\Environment::setup();
