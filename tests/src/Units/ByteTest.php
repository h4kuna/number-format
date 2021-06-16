<?php declare(strict_types=1);

namespace h4kuna\Number\Tests\Units;

use h4kuna\Number\Units\Byte;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

$byte = new Byte();
Assert::equal(1.0, $byte->convert(1024)->value);
Assert::equal(1073741824.0, $byte->convertFrom(1, $byte::GIGA, $byte::BASE)->value);
Assert::equal(134217728.0, $byte->fromString('128M')->value);
