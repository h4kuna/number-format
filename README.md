# Format

[![Build Status](https://travis-ci.com/h4kuna/number-format.svg?branch=master)](https://travis-ci.com/h4kuna/number-format)
[![Latest stable](https://img.shields.io/packagist/v/h4kuna/number-format.svg)](https://packagist.org/packages/h4kuna/number-format)
[![Downloads this Month](https://img.shields.io/packagist/dm/h4kuna/number-format.svg)](https://packagist.org/packages/h4kuna/number-format)
[![Coverage Status](https://coveralls.io/repos/github/h4kuna/number-format/badge.svg?branch=master)](https://coveralls.io/github/h4kuna/number-format?branch=master)

The library can format numbers like percent, currencies or number with unit, next are dates and convert between order of
units.

```bash
composer require h4kuna/number-format
```

# Changelog

Details is moved to [file](./changelog.md).

By statistic the version 3 is most used. I describe upgrade from v3.0 to v6.0.

- removed interface NumberFormat, now this is static class like wrap on [number_format()](https://www.php.net/manual/en/function.number-format.php).
- all classes `NumberFormatState`, `UnitFormatState`, `UnitPersistentFormatState` are joined to one [NumberFormatter](./src/Number/Formatters/NumberFormatter.php)
- change namespace from `h4kuna\Number` to `h4kuna\Format\Number` or `h4kuna\Format\Number\Formatters`
- removed support, defined parameters by array, from php8 is native by named parameters

### NumberFormatter changes

- class `NumberFormatter` is immutable
- is callable by `__invoke()` method
- method format() has only one parameter, `$number`
- mask has new char for unit, old `U` replaced by `⎵`, `NumberFormatter(mask: '⎵ 1')`
- parameter `intOnly` was removed
- parameter `round` is callback, see [Round.php](./src/Number/Round.php)
- add `nbsp` parameter
- parameter `zeroClear` is integer instead of bool, see [ZeroClear.php](./src/Number/Parameters/ZeroClear.php)

## Number

### Formats

Keep formats in object and use if you need.

```php
use h4kuna\Format\Number;

$formats = new Number\Formats([
	'EUR' => static fn (Number\Formats $formats) => new Number\Formatters\NumberFormatter(decimals: 0, nbsp: false, unit: '€'), // callback like factory if is needed
	'GBP' => new NumberFormatter(nbsp: false, unit: '£', mask: '⎵ 1'),
]);

$formats->get('EUR')->format(5); // 5€
$formats->get('GBP')->format(5); // £ 5,00
```

### NumberFormatter by h4kuna

The class provide many options, all options has default value. The class is immutable, the property are read only. If
you want any to change of setup use method modify(). Both classes [IntlNumberFormatter](src/Number/Formatters/IntlNumberFormatter.php) and [NumberFormatter](src/Number/Formatters/NumberFormatter.php) has implemented interface [Formatter](src/Number/Formatter.php).

```php
use h4kuna\Format\Number;

// set decimals as 3
$numberFormat = new Number\Formatters\NumberFormatter(3);
// or
$numberFormat = new Number\Formatters\NumberFormatter(decimals: 3);

echo $numberFormat->format(1000); // 1 000,000
```

#### Parameters

- decimals: [2]
- decimalPoint: [',']
- thousandsSeparator: [' ']
- nbsp: [true] replace space by \&nbsp; like utf-8 char
- zeroClear:
	- [ZeroClear::NO] disabled
	- [ZeroClear::DECIMALS_EMPTY] 1.0 -> `1`, 1.50 -> `1,50`
	- [ZeroClear::DECIMALS] 1.0 -> `1`; 1.50 -> `1,5`
- emptyValue: [\x00] disabled, if value is empty (by default `null` or empty string `''`) will display some wildcard
- zeroIsEmpty: [false] disabled, only `null` and empty string `''` are replaced by `emptyValue`, but by this option is
  zero empty value too
- unit: [''] disabled, define unit for formatted number, $, €, kg etc...
- showUnitIfEmpty: [false] unit must be defined, show unit if is empty value
- mask: [1 ⎵] if you want to define **1 €** or **$ 1**
- round: [null] change round function, let's use `Round::BY_CEIL` or `Round::BY_FLOOR`

Here are tests for [more use cases](tests/src/NumberFormatTest.php).

#### Method modify()

```php
use h4kuna\Format\Number;

$numberFormat = new Number\Formatters\NumberFormatter(mask: '⎵ 1', decimals: 3, unit: '€');
echo $numberFormat->format(1000); // € 1 000,000 with nbsp

$numberFormatDisableNbsp = $numberFormat->modify(nbsp: false); // keep previous setting and disable nbsp
echo $numberFormatDisableNbsp->format(1000); // € 1 000,000
```

### Native NumberFormatter

Create new class MyFormats and extends class [Utils\Formats](./src/Utils/Formats.php) for right suggestion. And use
native [NumberFormatter](https://www.php.net/manual/en/class.numberformatter.php).

```php
use h4kuna\Format\Number;use h4kuna\Format\Utils;

/**
 * @extends Utils\Formats<Number\Formatters\IntlNumberFormatter> 
 */
class MyFormats extends Utils\Formats 
{

}

setlocale(LC_TIME, 'cs_CZ.utf8');

$formats = new MyFormats([
	'decimal' => static fn() => new Number\Formatters\IntlNumberFormatter(new \NumberFormatter(\Locale::getDefault(), \NumberFormatter::DECIMAL)),
	'currency' => static fn() => new Number\Formatters\IntlNumberFormatter(new \NumberFormatter(\Locale::getDefault(), \NumberFormatter::CURRENCY)),
]);

$formats->get('decimal')->format(1000.1235); // 1 000,124
$formats->get('currency')->format(1000.1235); // 1 000,12 Kč
```

## Vat

```php
use h4kuna\Format\Number;

$tax = new Number\Vat(20);
echo $tax->with(100); // 120
echo $tax->without(120); // 100.0
echo $tax->diff(120); // 20.0
```

## Discount

```php
use h4kuna\Format\Number;

$percent = new Number\Discount(20);
echo $percent->with(100); // 120.0
echo $percent->deduct(120); // 96.0
echo $percent->diff(120); // 24.0
```

## Units\Unit

```php
use h4kuna\Format\Number\Units;

$unit = new Units\Unit(/* [string $from], [array $allowedUnits] */);
```

* **$from** select default prefix for your units default is BASE = 0
* **$allowedUnits** if we need other units if is defined

This example say: I have 50kilo (10<sup>3</sup>) and convert to base 10<sup>0</sup>

```php
/** @var h4kuna\Format\Number\Units\Unit $unit */

$unitValue = $unit->convertFrom(50, $unit::KILO, $unit::BASE);
echo $unitValue->unit; // empty string mean BASE
echo $unitValue->value; // 50000
```

If second parameter is `null` then use from unit whose is defined in constructor.

```php
/** @var h4kuna\Format\Number\Units\Unit $unit */

$unitValue = $unit->convertFrom(5000, null, $unit::KILO);
// alias for this use case is 
$unitValue = $unit->convert(5000, $unit::KILO);

echo $unitValue->unit; // k mean KILO
echo $unitValue->value; // 5
```

If third parameter is `null`, class try to find the best unit.

```php
/** @var h4kuna\Format\Number\Units\Unit $unit */

$unitValue = $unit->convertFrom(5000000, $unit::MILI, null);
echo $unitValue->unit; // k mean KILO
echo $unitValue->value; // 5
```

Last method, take string and convert how we need. This is good for Byte.

```php
/** @var h4kuna\Format\Number\Units\Unit $unit */

$unitValue = $unit->fromString('100k', $unit::BASE);
echo $unitValue->unit; // BASE
echo $unitValue->value; // 100000
```

## Units\Byte

```php
use h4kuna\Format\Number\Units;

$unitValue = $byte = new Units\Byte();
$byte->fromString('128M');
echo $unitValue->unit; // BASE
echo $unitValue->value; // 134217728
```

## Units\UnitFormat

If we need format our units.

```php
use h4kuna\Format\Number;

$nff = new Number\Formats();
$unitFormat = new Number\Units\UnitFormat('B', new Byte, $nff);

$unitFormat->convert(968884224); // '924,00 MB'
$unitFormat->convert(1024); // '1,00 kB'
```

## Date

Define own formats for date and time. Both classes [IntlDateFormatter](src/Date/Formatters/IntlDateFormatter.php) and [DateTimeFormatter](src/Date/Formatters/DateTimeFormatter.php) has implemented interface [Formatter](src/Date/Formatter.php).

```php
use h4kuna\Format\Date;

$formats = new Date\Formats([
	'date' => new Date\Formatters\DateTimeFormatter('j. n. Y'),
	'time' => static fn () => new Date\Formatters\DateTimeFormatter('H:i'), // callback like factory if is needed
	'dateTime' => static fn () => new Date\Formatters\DateTimeFormatter('j. n. Y H:i'),
]);

$dateObject = new \DateTime('2023-06-13 12:30:40');
$formats->get('date')->format($dateObject); // 13. 6. 2023 
$formats->get('time')->format($dateObject); // 12:30 
$formats->get('dateTime')->format($dateObject); // 13. 6. 2023 12:30 
```

For better use, you can extends class Formats and add your own methods.

```php
use h4kuna\Format\Date;

class MyFormats extends Date\Formats 
{
	public function date(?\DateTimeInterface $data): string 
	{
		return $this->get('date')->format($data);
	}
}

$formats = new MyFormats([
	'date' => new Date\Formatters\DateTimeFormatter('j. n. Y'),
]);

$dateObject = new \DateTime('2023-06-13 12:30:40');
$formats->date($dateObject); // 13. 6. 2023 
```

### Support IntlDateFormatter

Format by locale.

```php
use h4kuna\Format\Date;
use IntlDateFormatter;

$intlFormatter = new IntlDateFormatter('cs_CZ', IntlDateFormatter::MEDIUM, IntlDateFormatter::MEDIUM,)

$formats = new Date\Formats([
	'date' => new Date\Formatters\IntlDateFormatter($intlFormatter),
]);

$date = new \DateTime('2023-06-13 12:30:40');
$formats->get('date')->format($date); // 12. 6. 2023 12:30:40
```

## Integration to Nette framework

In your neon file, define service for keep formatting and register to latte

```neon
services:
	number: h4kuna\Format\Number\Formatters\NumberFormatter(decimalPoint: '.', decimals: 4) # support named parameters by nette
	percent: h4kuna\Format\Number\Formatters\NumberFormatter(decimalPoint: '.', decimals: 2, unit: '%')
	currency.czk: h4kuna\Format\Number\Formatters\NumberFormatter(decimalPoint: ',', decimals: 2, unit: 'Kč')
	currency.eur: h4kuna\Format\Number\Formatters\NumberFormatter(decimalPoint: '.', decimals: 2, unit: '€', mask: '⎵ 1')
	
	currencies:
		implement: h4kuna\Format\Number\FormatsAccessor(
			czk: @currency.czk
			eur: @currency.eur
		)
		
	latte.latteFactory:
		setup:
			- addFilter('number', @number)
			- addFilter('percent', @percent)
			- addFilter('czk', @currency.czk)
			- addFilter('eur', @currency.eur)
```

We added new filters for formatting and service for currencies:

Formatting 
```php
use h4kuna\Format\Number\FormatsAccessor;

class Invoice {
    public function __construct(private FormatsAccessor $formats) {
    }
    
    public function generate(): void 
    {
        $number = 987.356;
        $currency = 'eur';
        var_dump($this->formats->get($currency)->format($number));
    }
}
```

Latte template
```html
{=10000|number} // this render "1 000.0000" with &nbps; like white space
```

# Units

Help us convert units in general [decimal system](//en.wikipedia.org/wiki/Metric_prefix#List_of_SI_prefixes).
