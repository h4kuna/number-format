Number Format
=============

[![Build Status](https://travis-ci.com/h4kuna/number-format.svg?branch=master)](https://travis-ci.com/h4kuna/number-format)
[![Latest stable](https://img.shields.io/packagist/v/h4kuna/number-format.svg)](https://packagist.org/packages/h4kuna/number-format)
[![Downloads this Month](https://img.shields.io/packagist/dm/h4kuna/number-format.svg)](https://packagist.org/packages/h4kuna/number-format)
[![Coverage Status](https://coveralls.io/repos/github/h4kuna/number-format/badge.svg?branch=master)](https://coveralls.io/github/h4kuna/number-format?branch=master)

Wrapper above number_format, api is very easy.

# Changelog
## v5.0
- support php 8.0+
- add new static class [Format](src/Format.php), you can format numbers without instance of class
- class NumberFormat is immutable
- **BC break** removed parameters like unit and decimals in method NumberFormat::format()
  - let's use method modify()
- class Parameters removed, because php 8.0 has native support
- **BC break** NumberFormat support for int numbers removed, like a parameter **intOnly**
- **BC break** NumberFormat removed method enableExtendFormat() all options move to constructor
- add new class Round
- class NumberFormatFactory removed
- parameter zeroClear is integer instead of bool

## v4.0
- removed dependency on h4kuna/data-type
- support php 7.4+
- removed interface NumberFormat
- renamed class NumberFormatState -> NumberFormat
- removed class UnitFormatState, replace by `NumberFormat` like `$nf = new NumberFormat(); $nf->enableExtendFormat();`
- removed class UnitPersistentFormatState, replace by `NumberFormat` like `$nf = new NumberFormat(); $nf->enableExtendFormat('1 MY_PERSISTENT_UNIT');`
- method format has second parameter like decimals and third is dynamic defined unit
- char for unit in mask changed to `⎵`
- added parameter nbsp to NumberFormat::__construct()

Install via composer
-------------------
```sh
composer require h4kuna/number-format
```

### NumberFormat

Class has many parameters and all paremetes has default value. You can add parameters normaly by position or name of keys in array like first parameter.

```php
use h4kuna\Number;

// set decimals as 3
$numberFormat = new Number\NumberFormat(3);
// or
$numberFormat = new Number\NumberFormat(decimals: 3);

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
- zeroIsEmpty: [false] disabled, only `null` and empty string `''` are replaced by `emptyValue`, but by this option is zero empty value too
- unit: [''] disabled, define unit for formatted number, $, €, kg etc...
- showUnitIfEmpty: [false] unit must be defined, show unit if is empty value
- mask: [1 ⎵] if you want to define **1 €** or **$ 1**
- round: [null] change round function, let's use `Round::BY_CEIL` or `Round::BY_FLOOR`

Here are tests for [more use cases](tests/src/NumberFormatTest.php).

### Format expect unit and use modify()
```php
use h4kuna\Number;

$numberFormat = new Number\NumberFormat(mask: '⎵ 1', decimals: 3, unit: '€');
echo $numberFormat->format(1000, 3, '€'); // € 1 000,000 with nbsp

$numberFormatDisableNbsp = $numberFormat->modify(nbsp: false); // keep previous setting and disable nbsp
echo $numberFormatDisableNbsp->format(1000); // € 1 000,000
```

### Format persistent unit
This class is same like previous, but unit is persistent like currencies or temperature. 

```php
use h4kuna\Number;

$numberFormat = new Number\NumberFormat();
$numberFormat->enableExtendFormat('€ 1');

echo $numberFormat->format(1000); // € 1 000,00
```

### NumberFormatFactory
For all previous cases is prepared [factory class](src/NumberFormatFactory.php). This class help you create new instance and support named parameters in constructor. [Visit test](tests/src/NumberFormatFactoryTest.php)

### Tax

```php
$tax = new Tax(20);
echo $tax->add(100); // 120
echo $tax->deduct(120); // 100.0
echo $tax->diff(120); // 20.0
```

### Percent

```php
$percent = new Percent(20);
echo $percent->add(100); // 120.0
echo $percent->deduct(120); // 96.0
echo $percent->diff(120); // 24.0
```

## Integration to Nette framework

In your neon file
```neon
services:
	number: h4kuna\Number\NumberFormat(decimalPoint: '.', intOnly: 1, decimals: 1) #support named parameters by nette

	latte.latteFactory:
		setup:
			- addFilter('number', [@number, 'format'])
```

We added new filter number, in template use like:
```html
{=10000|number} // this render "1 000.0" with &nbps; like white space
```

# Units
Help us convert units in general [decimal system](//en.wikipedia.org/wiki/Metric_prefix#List_of_SI_prefixes).

### Units\Unit
```php
use h4kuna\Number\Units;

$unit = new Units\Unit(/* [string $from], [array $allowedUnits] */);
```
* **$from** select default prefix for your units default is BASE = 0
* **$allowedUnits** if we need other units if is defined

This example say: I have 50kilo (10<sup>3</sup>) and convert to base 10<sup>0</sup>
```php
$unitValue = $unit->convertFrom(50, $unit::KILO, $unit::BASE);
echo $unitValue->unit; // empty string mean BASE
echo $unitValue->value; // 50000
```

If second parameter is NULL then use from unit whose is defined in constructor.
```php
$unitValue = $unit->convertFrom(5000, NULL, $unit::KILO);
// alias for this use case is 
$unitValue = $unit->convert(5000, $unit::KILO);

echo $unitValue->unit; // k mean KILO
echo $unitValue->value; // 5
```
If third parameter is NULL, class try find best unit.
```php
$unitValue = $unit->convertFrom(5000000, $unit::MILI, NULL);
echo $unitValue->unit; // k mean KILO
echo $unitValue->value; // 5
```

Last method, take string and convert how we need. This is good for Byte.
```php
$unitValue = $unit->fromString('100k', $unit::BASE);
echo $unitValue->unit; // BASE
echo $unitValue->value; // 100000
```

### Units\Byte
```php
$unitValue = $byte = new Units\Byte();
$byte->fromString('128M');
echo $unitValue->unit; // BASE
echo $unitValue->value; // 134217728
```

### Units\UnitFormat
If we need format our units.
```php
$nff = new Number\NumberFormatFactory();
$unitfFormat = new Units\UnitFormat('B', new Byte, $nff->createUnit());

$unitfFormat->convert(968884224); // '924,00 MB'
$unitfFormat->convert(1024); // '1,00 kB'
```
