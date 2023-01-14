<?php declare(strict_types=1);

namespace h4kuna\Number\Tests;

use h4kuna\Number\NumberFormatState;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

$numberFormat = new NumberFormatState();
Assert::same('12,00', $numberFormat->format(12));
