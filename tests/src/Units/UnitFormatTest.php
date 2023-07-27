<?php declare(strict_types=1);

namespace h4kuna\Number\Tests\Units;

use h4kuna\Number;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

$uf = new Number\Units\UnitFormat('B', new Number\Units\Byte(), new Number\Utils\Formats());

Assert::same(nbsp('924,00 MB'), $uf->convert(968884224));
Assert::same(nbsp('1,00 kB'), $uf->convert(1024));

Assert::same(nbsp('31 457 280,00'), $uf->fromString('30M'));
