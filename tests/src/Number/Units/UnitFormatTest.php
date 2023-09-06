<?php declare(strict_types=1);

namespace h4kuna\Format\Tests\Units;

use h4kuna\Format;
use h4kuna\Format\Utils\Space;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

$uf = new Format\Number\Units\UnitFormat('B', new Format\Number\Units\Byte(), new Format\Number\Formats());

Assert::same(Space::nbsp('924,00 MB'), $uf->convert(968884224));
Assert::same(Space::nbsp('1,00 kB'), $uf->convert(1024));

Assert::same(Space::nbsp('31 457 280,00'), $uf->fromString('30M'));
