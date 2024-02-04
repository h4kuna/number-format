<?php declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use h4kuna\Format\Number\Formatters\IntlNumberFormatter;
use h4kuna\Format\Number\Formatters\NumberFormatter;
use h4kuna\Format\Number\Parameters\ZeroClear;
use h4kuna\Format\Number\Percentage;
use h4kuna\Format\Number\Round;

$number = 1234.456;

$format = new NumberFormatter();
echo $format->format($number); // 1 234,46

$format = $format->modify(nbsp: false);
echo $format->format($number); // 1 234,46

echo $format->modify(decimals: 1)->format($number); // 1 234,5
echo $format->modify(decimals: 0)->format($number); // 1 234
echo $format->modify(decimals: -1)->format($number); // 1 230

echo $format->modify(decimalPoint: '.')->format($number); // 1 234.46

echo $format->modify(decimals: 4, zeroClear: ZeroClear::NO)->format($number); // 1 234,4560
echo $format->modify(decimals: 4, zeroClear: ZeroClear::DECIMALS_EMPTY)->format($number); // 1 234,4560
echo $format->modify(decimals: 4, zeroClear: ZeroClear::DECIMALS_EMPTY)->format('1234.000'); // 1 234
echo $format->modify(decimals: 4, zeroClear: ZeroClear::DECIMALS)->format($number); // 1 234,456

echo $format->modify(emptyValue: '-')->format(null); // -
echo $format->modify(emptyValue: '-')->format(''); // -
echo $format->modify(emptyValue: '-')->format(0); // 0,00

echo $format->modify(emptyValue: '-', zeroIsEmpty: true)->format(0); // -

echo $format->modify(unit: 'Kg')->format($number); // 1 234,46 Kg
echo $format->modify(unit: 'Kg')->format(0); // 0,00 Kg

echo $format->modify(unit: 'Kg', showUnitIfEmpty: false)->format(0); // 0,00

echo $format->modify(zeroIsEmpty: true, unit: 'Kg', showUnitIfEmpty: false)->format(0); // 0,00
echo $format->modify(unit: '€', mask: '⎵1')->format($number); // €1 234,46

echo $format->modify(round: Round::BY_FLOOR)->format($number); // 1 234,45
echo $format->modify(decimals: 0)->format($number); // 1 234
echo $format->modify(decimals: 0, round: Round::BY_CEIL)->format($number); // 1 235
echo $format->modify(decimals: 0, round: Round::BY_FLOOR)->format($number); // 1 234

echo '-------' . PHP_EOL;
$numberFormatter = new \NumberFormatter('cs_CZ', \NumberFormatter::DECIMAL);
$format = new IntlNumberFormatter($numberFormatter);
echo $format->format($number); // 1 234,456

$percent = new Percentage(20);
echo $percent->with(100); // 120.0
echo $percent->without(120); // 100.0
echo $percent->withoutDiff(120); // 20.0
echo $percent->deduct(120); // 96.0
echo $percent->diff(120); // 24.0

$percent = new Percentage(20, new NumberFormatter(unit: '%'));
echo $percent; // 20,00 %
