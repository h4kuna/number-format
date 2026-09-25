# Numbers

Define the format once and use it across the project. The class provides many options, every option has a default value. The class is immutable, the properties are read only. If you want to change the setup, use the method modify(). Both classes [IntlNumberFormatter](../src/Number/Formatters/IntlNumberFormatter.php) and [NumberFormatter](../src/Number/Formatters/NumberFormatter.php) implement the interface [Formatter](../src/Number/Formatter.php).

## NumberFormatter by h4kuna
The class wraps the PHP function `number_format` and extends its behavior.

#### Parameters

> The parameters are the same for the methods __construct() and modify().

> The method modify() returns a new instance, the original one is not changed.

[NumberFormatter](../src/Number/Formatters/NumberFormatter.php)

- decimals [2]: Sets the number of decimal digits.
- decimalPoint [',']: Sets the separator for the decimal point.
- thousandsSeparator [' ']: Sets the thousands separator.
- nbsp [true]: Replace spaces by the UTF-8 non-breaking space (`&nbsp;`).
- zeroClear [ZeroClear::NO]: Remove trailing zeros after the decimal point.
    - ZeroClear::NO: disabled `1.0` -> `1,00`
    - ZeroClear::DECIMALS_EMPTY: `1.0` -> `1`, `1.50` -> `1,50`
    - ZeroClear::DECIMALS: `1.0` -> `1`, `1.50` -> `1,5`
- emptyValue [\x00]: Disabled by default (an empty string is returned). A text displayed instead of an empty value, by default `null` or a non-numeric string like `''`.
- zeroIsEmpty [false]: Disabled by default, only `null` and non-numeric strings are replaced by `emptyValue`. When enabled, zero is replaced too.
- unit ['']: Disabled by default, unit of the formatted number, $, €, kg etc.
- showUnitIfEmpty [true]: Show the unit when the value is zero or empty, the unit must be defined.
- mask ['1 ⎵']: Position of the number `1` and of the unit `⎵`, for example **1 €** or **$ 1**.
- round [null]: Change the round function, use `Round::BY_CEIL` or `Round::BY_FLOOR`, see [Round](../src/Number/Round.php). Rounding is standard by default.

#### Examples

Basic usage:
```php
use h4kuna\Format\Number\Formatters\NumberFormatter;
$number = 1234.456;

$format = new NumberFormatter();
echo $format->format($number); // 1⎵234,46, in this example the char ⎵ represents &nbsp;
echo $format->modify(unit: 'Kg')->format($number); // 1⎵234,46⎵Kg
```

##### nbsp
Disable nbsp for all the following examples.
```php
$format = $format->modify(nbsp: false);
echo $format->format($number); // 1 234,46
```

##### decimals
```php
echo $format->modify(decimals: 1)->format($number); // 1 234,5
echo $format->modify(decimals: 0)->format($number); // 1 234
echo $format->modify(decimals: -1)->format($number); // 1 230
```

##### decimalPoint
```php
echo $format->modify(decimalPoint: '.')->format($number); // 1 234.46
```

##### thousandsSeparator
```php
echo $format->modify(decimalPoint: '.', thousandsSeparator: ',')->format($number); // 1,234.46
```

##### zeroClear
```php
use h4kuna\Format\Number\Parameters\ZeroClear;

echo $format->modify(decimals: 4, zeroClear: ZeroClear::NO)->format($number); // 1 234,4560

echo $format->modify(decimals: 4, zeroClear: ZeroClear::DECIMALS_EMPTY)->format($number); // 1 234,4560
echo $format->modify(decimals: 4, zeroClear: ZeroClear::DECIMALS_EMPTY)->format('1234.000'); // 1 234

echo $format->modify(decimals: 4, zeroClear: ZeroClear::DECIMALS)->format($number); // 1 234,456
echo $format->modify(decimals: 4, zeroClear: ZeroClear::DECIMALS)->format('1234.000'); // 1 234
```

##### emptyValue
```php
echo $format->modify(emptyValue: '-')->format(null); // -
echo $format->modify(emptyValue: '-')->format(''); // -
echo $format->modify(emptyValue: '-')->format(0); // 0,00, set zeroIsEmpty if zero should be replaced too
```

##### zeroIsEmpty
```php
echo $format->modify(emptyValue: '-', zeroIsEmpty: true)->format(0); // -
```

##### unit
```php
echo $format->modify(unit: 'Kg')->format($number); // 1 234,46 Kg
echo $format->modify(unit: '%')->format($number); // 1 234,46 %
echo $format->modify(unit: '€')->format($number); // 1 234,46 €
```

##### showUnitIfEmpty
```php
echo $format->modify(unit: 'Kg')->format(0); // 0,00 Kg
echo $format->modify(unit: 'Kg', showUnitIfEmpty: false)->format(0); // 0,00
```

##### mask
```php
echo $format->modify(unit: '€', mask: '⎵1')->format($number); // €1 234,46
```

##### round
```php
use h4kuna\Format\Number\Round;

echo $format->modify(decimals: 0)->format($number); // 1 234
echo $format->modify(decimals: 0, round: Round::BY_CEIL)->format($number); // 1 235
echo $format->modify(decimals: 0, round: Round::BY_FLOOR)->format($number); // 1 234
```

## IntlNumberFormatter

Wraps the PHP native [NumberFormatter](https://www.php.net/manual/en/class.numberformatter.php) and adds two parameters `$emptyValue` and `$zeroIsEmpty`.
```php
use NumberFormatter;
use h4kuna\Format\Number\Formatters\IntlNumberFormatter;
use h4kuna\Format\Number\NativePhp\NumberFormatterFactory;

$numberFormatter = new NumberFormatter('cs_CZ', NumberFormatter::CURRENCY);
// or use prepared factory
$numberFormatterFactory = new NumberFormatterFactory('cs_CZ');
$numberFormatter = $numberFormatterFactory->currency();

$format = new IntlNumberFormatter($numberFormatter);
echo $format->format($number); // 1⎵234,46⎵Kč

$numberFormatter = $numberFormatterFactory->currency('en_GB');
$format = new IntlNumberFormatter($numberFormatter);
echo $format->format($number); // £1,234.46
```


## Collection of NumberFormat

Keep all defined formatters in one place. An unknown key creates a formatter with the key as the unit.

```php
use h4kuna\Format\Number\Formats;
use h4kuna\Format\Number\Formatters\NumberFormatter;

$formats = new Formats([
	'EUR' => static fn (Formats $formats) => new NumberFormatter(decimals: 0, nbsp: false, unit: '€'), // a callback works as a lazy factory
	'GBP' => new NumberFormatter(nbsp: false, unit: '£', mask: '⎵ 1'),
]);

$formats->get('EUR')->format(5); // 5 €
$formats->get('GBP')->format(5); // £ 5,00
```

## Integration to Nette framework

Define the formatters as services in your neon file and register them as Latte filters.

```neon
services:
	number: h4kuna\Format\Number\Formatters\NumberFormatter(decimalPoint: '.', decimals: 4) # named parameters are supported by Nette
	percent: h4kuna\Format\Number\Formatters\NumberFormatter(decimalPoint: '.', decimals: 2, unit: '%')
	currency.czk: h4kuna\Format\Number\Formatters\NumberFormatter(decimalPoint: ',', decimals: 2, unit: 'Kč')
	currency.eur: h4kuna\Format\Number\Formatters\NumberFormatter(decimalPoint: '.', decimals: 2, unit: '€', mask: '⎵ 1')
	
	# accessor with all currencies
	currencies: h4kuna\Format\Number\FormatsAccessor(
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

Latte template
```latte
{=10000|number} {* renders "10 000.0000" with &nbsp; as the space *}
{=10000|percent}
{=10000|czk}
```
