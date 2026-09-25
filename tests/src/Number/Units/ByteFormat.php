<?php declare(strict_types = 1);

namespace h4kuna\Format\Tests\Units;

use h4kuna\Format\Number\Formatters\NumberFormatter;
use h4kuna\Format\Number\Units\ByteFormat;
use h4kuna\Format\Utils\Space;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

$uf = new ByteFormat(new NumberFormatter(0));

Assert::same(Space::nbsp('924 MB'), $uf->convert(968_884_224));
Assert::same(Space::nbsp('1 kB'), $uf->convert(1024));

Assert::same(Space::nbsp('31 457 280'), $uf->fromString('30M'));
