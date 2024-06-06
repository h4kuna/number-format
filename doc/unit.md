# Units

Help us convert units in general [decimal system](//en.wikipedia.org/wiki/Metric_prefix#List_of_SI_prefixes).

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
// or
$unitFormat = new Number\Units\ByteFormat($nff);

$unitFormat->convert(968884224); // '924,00 MB'
$unitFormat->convert(1024); // '1,00 kB'
```
