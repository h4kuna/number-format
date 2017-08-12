<?php

namespace h4kuna\Number\Units;

use h4kuna\Number,
	Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

$nff = new Number\NumberFormatFactory();
$uf = new UnitFormat('B', new Byte, $nff->createUnit());

Assert::same('924,00 MB', $uf->convert(968884224));
Assert::same('1,00 kB', $uf->convert(1024));
