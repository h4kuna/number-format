<?php

namespace h4kuna\Number\Units;

use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

$byte = new Byte();
Assert::equal(1.0, $byte->convert(1024)->value);
Assert::equal(1073741824, $byte->convertFrom(1, $byte::GIGA, $byte::BASE)->value);
Assert::equal(134217728, $byte->fromString('128M')->value);
