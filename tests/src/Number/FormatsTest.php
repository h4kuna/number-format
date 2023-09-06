<?php declare(strict_types=1);

namespace h4kuna\Format\Tests\Number;

use h4kuna\Format\Exceptions\InvalidStateException;
use h4kuna\Format\Number\Formats;
use h4kuna\Format\Number\Formatters\NumberFormatter;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

$formats = new Formats([ // @phpstan-ignore-line
	'EUR' => fn (Formats $formats): NumberFormatter => new NumberFormatter(decimals: 0, nbsp: false, unit: '€'),
	'GBP' => new NumberFormatter(nbsp: false, unit: '£', mask: '⎵ 1'),
]);

$formats->add('CZK', new NumberFormatter(decimals: 3, nbsp: false, unit: 'CZK'));
$formats->add('USD', static fn (Formats $formats
): NumberFormatter => $formats->getDefault()('USD', $formats, ['unit' => '$']));

$formats->setDefault(static function (string|int $key, Formats $self, mixed $options) {
	$options ??= [];
	assert(is_array($options));
	$options['unit'] = $options['unit'] ?? $key;
	$options['nbsp'] = $options['nbsp'] ?? false;
	$options['decimals'] = $options['decimals'] ?? 0;
	return new NumberFormatter(...$options);
});

Assert::exception(function () use ($formats) {
	$formats->setDefault(fn () => new NumberFormatter(nbsp: false));
}, InvalidStateException::class);

Assert::same('100 UNKNOWN', $formats->get('UNKNOWN')->format(100));
Assert::same('100 $', $formats->get('USD')->format(100));
Assert::same('100,000 CZK', $formats->get('CZK')->format(100));
Assert::same('100 CZK', $formats->get('CZK')->modify(decimals: 0)->format(100));
Assert::same('5 €', $formats->get('EUR')->format(5));
Assert::same('£ 5,00', $formats->get('GBP')->format(5));
