<?php declare(strict_types=1);

namespace h4kuna\Number\Tests\Utils;

use h4kuna\Number\Exceptions\InvalidStateException;
use h4kuna\Number\NumberFormat;
use h4kuna\Number\Utils\Formats;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

$formats = new Formats();

$formats->add('CZK', new NumberFormat(decimals: 3, nbsp: false, unit: 'CZK'));
$formats->add('USD', new NumberFormat(decimals: 2, nbsp: false, unit: '$'));
$formats->setDefault(new NumberFormat(decimals: 0, nbsp: false));

Assert::exception(function () use ($formats) {
	$formats->setDefault(new NumberFormat());
}, InvalidStateException::class);

Assert::same('100 EUR', $formats->get('EUR')->format('100'));
Assert::same($formats->get('EUR'), $formats->get('EUR'));
Assert::same('100,00 $', $formats->get('USD')->format('100'));
Assert::same('100,000 CZK', $formats->get('CZK')->format('100'));
Assert::same('100 CZK', $formats->get('CZK')->modify(decimals: 0)->format('100'));
