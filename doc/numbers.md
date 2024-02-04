# Numbers

Format setting and use across the project. The class provide many options, all options has default value. The class is immutable, the property are read only. If you want any to change setup, let's use method modify(). Both classes [IntlNumberFormatter](../src/Number/Formatters/IntlNumberFormatter.php) and [NumberFormatter](../src/Number/Formatters/NumberFormatter.php) has implemented interface [Formatter](../src/Number/Formatter.php).

## NumberFormatter by h4kuna
The class wraps a php function `number_format` and extends the behavior.

#### Parameters

> The parameters are same for methods __construct() and modify(). 

[NumberFormatter](../src/Number/Formatters/NumberFormatter.php)

- decimals [2]: Sets the number of decimal digits.
- decimalPoint [',']: Sets the separator for the decimal point.
- thousandsSeparator [' ']: Sets the thousands separator.
- nbsp [true]: Replace space by \&nbsp; like utf-8 char.
- zeroClear [ZeroClear::NO]: Clear zero from right after decimal point.
    - ZeroClear::NO: disabled `1.0` -> `1,00`
    - ZeroClear::DECIMALS_EMPTY: `1.0` -> `1`, `1.50` -> `1,50`
    - ZeroClear::DECIMALS: `1.0` -> `1`, `1.50` -> `1,5`
- emptyValue [\x00]: By default is disabled, if value is empty (by default `null` or empty string `''`) will display some wildcard
- zeroIsEmpty [false]: By default is disabled, only `null` and empty string `''` are replaced by `emptyValue`, but by this option is
  zero empty value too.
- unit ['']: Disabled, define unit for formatted number, $, €, kg etc...
- showUnitIfEmpty [false]: Unit must be defined, show unit if is empty value.
- mask [1 ⎵]: If you want to define **1 €** or **$ 1**.
- round [null]: Change round function, let's use `Round::BY_CEIL` or `Round::BY_FLOOR`.

#### Examples

Basic usage:
```php
use h4kuna\Format\Number\Formatters\NumberFormatter;
$number = 1234.456;

$format = new NumberFormatter();
echo $format->format($number); // 1⎵234,46 // in this example char ⎵ represent &nbsp
echo $format->modify(unit: 'Kg')->format($number); // 1⎵234,46⎵Kg
```

##### nbsp
disable nbsp for all examples
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
echo $format->modify(emptyValue: '-')->format(0); // 0,00 if you want zero replace by empty value set zeroIsEmpty
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
echo $format->modify(unit: 'Kg')->format(0); // 0,00 Kg
```

##### showUnitIfEmpty
```php
echo $format->modify(unit: 'Kg', showUnitIfEmpty: false)->format(0); // 0,00
```

##### mask
```php
echo $format->modify(unit: '€', mask: '⎵1')->format($number); // €1 234,46
```

##### round
```php
echo $format->modify(decimals: 0)->format($number); // 1 234
echo $format->modify(decimals: 0, round: Round::BY_CEIL)->format($number); // 1 235
echo $format->modify(decimals: 0, round: Round::BY_FLOOR)->format($number); // 1 234
```

## IntlNumberFormatter

Support php native [NumberFormatter](https://www.php.net/manual/en/class.numberformatter.php) and extends by two parameters `$emptyValue` and `$zeroIsEmpty`.
```php
$numberFormatter = new \NumberFormatter('cs_CZ', \NumberFormatter::DECIMAL);
$format = new IntlNumberFormatter($numberFormatter);
echo $format->format($number); // 1 234,456
```


## Collection of NumberFormat

Keep all defined formatters on one place.

```php
use h4kuna\Format\Number\Formats;
use h4kuna\Format\Number\Formatters\NumberFormatter;

$formats = new Formats([
	'EUR' => static fn (Formats $formats) => new NumberFormatter(decimals: 0, nbsp: false, unit: '€'), // callback like factory if is needed
	'GBP' => new NumberFormatter(nbsp: false, unit: '£', mask: '⎵ 1'),
]);

$formats->get('EUR')->format(5); // 5€
$formats->get('GBP')->format(5); // £ 5,00
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

Latte template
```html
{=10000|number} // this render "1 000.0000" with &nbps; like white space
{=10000|percent}
{=10000|czk}
```
