<?php declare(strict_types=1);

namespace h4kuna\Format\Tests\Units;

use h4kuna\Format;
use h4kuna\Format\Utils\Space;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

$uf = new Format\Number\Units\UnitFormat('g', new Format\Number\Units\Unit());

Assert::same(Space::nbsp('968,88 Mg'), $uf->convert(968884224));
Assert::same(Space::nbsp('1,02 kg'), $uf->convert(1024));

Assert::same(Space::nbsp('30 000 000,00'), $uf->fromString('30M'));
