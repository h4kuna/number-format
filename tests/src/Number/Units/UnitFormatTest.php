<?php declare(strict_types = 1);

namespace h4kuna\Format\Tests\Units;

use h4kuna\Format\Number\Units\Unit;
use h4kuna\Format\Number\Units\UnitFormat;
use h4kuna\Format\Utils\Space;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

$uf = new UnitFormat('g', new Unit());

Assert::same(Space::nbsp('968,88 Mg'), $uf->convert(968_884_224));
Assert::same(Space::nbsp('1,02 kg'), $uf->convert(1024));

Assert::same(Space::nbsp('30 000 000,00'), $uf->fromString('30M'));
