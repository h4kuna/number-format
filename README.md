Number Format
=============

[![Build Status](https://travis-ci.org/h4kuna/number-format.svg?branch=master)](https://travis-ci.org/h4kuna/number-format)
[![Latest stable](https://img.shields.io/packagist/v/h4kuna/number-format.svg)](https://packagist.org/packages/h4kuna/number-format)
[![Downloads this Month](https://img.shields.io/packagist/dm/h4kuna/number-format.svg)](https://packagist.org/packages/h4kuna/number-format)
[![Coverage Status](https://coveralls.io/repos/github/h4kuna/number-format/badge.svg?branch=master)](https://coveralls.io/github/h4kuna/number-format?branch=master)

Wrapper above number_format, api is very easy.

# Changelog
## v3.0
This version is same like v2.0 but support php7.1+.

## v2.0

New behavior is representing by one class is one type of format. Onetime create class and you can'nt change by life of object. Added new classes for number, unit and currency. Working with percent and taxes are better too.

Here is [manual](//github.com/h4kuna/number-format/tree/v1.3.0) for older version 1.3.0.

Install via composer
-------------------
```sh
composer require h4kuna/number-format
```

### NumberFormatState

Class has many parameters and all paremetes has default value. You can add parameters normaly by position or name of keys in array like first parameter.

```php
use h4kuna\Number;

// set decimals as 3
$numberFormat = new Number\NumberFormatState(3);
// or
$numberFormat = new Number\NumberFormatState(['decimals' => 3]);


echo $numberFormat->format(1000); // 1 000,000
```

#### Parameters
- decimals: [2]
- decimalPoint: string [',']
- thousandsSeparator: string [NULL] mean \&nbsp;
- zeroIsEmpty: bool [FALSE] - transform 0 to empty value
- emptyValue: string [NULL] has two options dependecy on zeroIsEmpty if is FALSE than empty value transform to zero or TRUE mean zero tranform to emtpy string if is not defined other string
- zeroClear: [FALSE] mean 1.20 trim zero from right -> 1.2 
- intOnly: [-1] if we have numbers like integers. This mean set 3 and transform number 1050 -> 1,05
- round: [0] change round function, let's use `NumberFormatState::ROUND_BY_CEIL` or `NumberFormatState::ROUND_BY_FLOOR` 

Here is test for [more use cases](tests/src/NumberFormatStateTest.php).

### UnitFormatState
Use this class for number with unit like Kb, Mb, Gb. Unit symbol is second parameter in [method **format**](src/UnitFormatState.php). Visit [tests](tests/src/UnitFormatStateTest.php).

#### Parameters
- mask: ['1 U'] mean 1 pattern for number and U is pattern for unit
- showUnit: [TRUE] mean show unit if number is empty 
- nbsp: [TRUE] mean replace white space in mask by \&nbsp

### UnitPersistentFormatState
This class is same like previous, but unit is persistent like currencies or temperature. 

#### Parameters
- unit: has'nt default value

### NumberFormatFactory
For all previous classes is prepared [factory class](src/NumberFormatFactory.php). This class help you create new instance and support named parameters in constructor. [Visit test](tests/src/NumberFormatFactoryTest.php)

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
	number: h4kuna\Number\NumberFormatState(decimalPoint: '.', intOnly: 1, decimals: 1) #support named parameters by nette

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
