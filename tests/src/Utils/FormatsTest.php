<?php declare(strict_types=1);

namespace h4kuna\Number\Tests\Utils;

use h4kuna\Number\Exceptions\InvalidStateException;
use h4kuna\Number\NumberFormat;
use h4kuna\Number\Utils\Formats;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

$formats = new Formats([
	'EUR' => fn (Formats $formats): NumberFormat => new NumberFormat(decimals: 0, nbsp: false, unit: '€'),
	'GBP' => new NumberFormat(nbsp: false, unit: '£', mask: '⎵ 1'),
]);

$formats->add('CZK', new NumberFormat(decimals: 3, nbsp: false, unit: 'CZK'));
$formats->add('USD', static fn (Formats $formats
): NumberFormat => $formats->getDefault()(['unit' => '$'], $formats, 'USD'));
$formats->setDefault(static function (array $options) {
	$options['nbsp'] = $options['nbsp'] ?? false;
	$options['decimals'] = $options['decimals'] ?? 0;
	return new NumberFormat(...$options);
});

Assert::exception(function () use ($formats) {
	$formats->setDefault(fn () => new NumberFormat(nbsp: false));
}, InvalidStateException::class);

Assert::same('100 UNKNOWN', $formats->get('UNKNOWN')->format(100));
Assert::same('100 $', $formats->get('USD')->format(100));
Assert::same('100,000 CZK', $formats->get('CZK')->format(100));
Assert::same('100 CZK', $formats->get('CZK')->modify(decimals: 0)->format(100));
Assert::same('5 €', $formats->get('EUR')->format(5));
Assert::same('£ 5,00', $formats->get('GBP')->format(5));
