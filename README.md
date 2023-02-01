Number Format
=============

[![Build Status](https://travis-ci.com/h4kuna/number-format.svg?branch=master)](https://travis-ci.com/h4kuna/number-format)
[![Latest stable](https://img.shields.io/packagist/v/h4kuna/number-format.svg)](https://packagist.org/packages/h4kuna/number-format)
[![Downloads this Month](https://img.shields.io/packagist/dm/h4kuna/number-format.svg)](https://packagist.org/packages/h4kuna/number-format)
[![Coverage Status](https://coveralls.io/repos/github/h4kuna/number-format/badge.svg?branch=master)](https://coveralls.io/github/h4kuna/number-format?branch=master)

Wrapper above number_format, api is very easy.

# Changelog
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
$numberFormat = new Number\NumberFormat(['decimals' => 3]);

echo $numberFormat->format(1000); // 1 000,000
```

#### Parameters
- decimals: [2]
- decimalPoint: string [',']
- thousandsSeparator: string [' ']
- nbsp: bool [true] - replace space by \&nbsp;
- zeroIsEmpty: bool [FALSE] - transform 0 to empty value
- emptyValue: string [NULL] has two options dependency on zeroIsEmpty if is FALSE than empty value transform to zero or TRUE mean zero transform to empty string if is not defined other string
- zeroClear: [FALSE] mean 1.20 trim zero from right -> 1.2 
- intOnly: [-1] if we have numbers like integers. This mean set 3 and transform number 1050 -> 1,05
- round: [0] change round function, let's use `NumberFormat::ROUND_BY_CEIL` or `NumberFormat::ROUND_BY_FLOOR` 

Here is test for [more use cases](tests/src/NumberFormatTest.php).

### Format expect unit
```php
use h4kuna\Number;

$numberFormat = new Number\NumberFormat();
$numberFormat->enableExtendFormat('⎵ 1');

echo $numberFormat->format(1000, 3, '€'); // € 1 000,000
```

#### Parameters for NumberFormat::enableExtendFormat()
- mask: ['1 ⎵'] mean 1 pattern for number and ⎵ is pattern for unit
- showUnit: [TRUE] mean show unit if number is empty 
- nbsp: [TRUE] mean replace white space in mask by \&nbsp

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
