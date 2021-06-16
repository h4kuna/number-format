<?php declare(strict_types=1);

namespace h4kuna\Number\Tests\Units;

use h4kuna\Number;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

$nff = new Number\NumberFormatFactory();
$uf = new Number\Units\UnitFormat('B', new Number\Units\Byte(), $nff->createUnit());

Assert::same('924,00 MB', $uf->convert(968884224));
Assert::same('1,00 kB', $uf->convert(1024));

Assert::same('31 457 280,00', $uf->fromString('30M'));
