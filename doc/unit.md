# Units

Convert values between [metric prefixes](https://en.wikipedia.org/wiki/Metric_prefix#List_of_SI_prefixes).

```php
use h4kuna\Format\Number\Units;

$unit = new Units\Unit(/* [string $from] */);
```

* **$from** the default prefix of your values, default is `Unit::BASE` (empty string, 10<sup>0</sup>)

This example says: I have 50 kilo (10<sup>3</sup>) and convert it to base 10<sup>0</sup>.

```php
/** @var h4kuna\Format\Number\Units\Unit $unit */

$unitValue = $unit->convertFrom(50, $unit::KILO, $unit::BASE);
echo $unitValue->unit; // empty string means BASE
echo $unitValue->value; // 50000
```

If the second parameter is `null`, the unit defined in the constructor is used.

```php
/** @var h4kuna\Format\Number\Units\Unit $unit */

$unitValue = $unit->convertFrom(5000, null, $unit::KILO);
// alias for this use case is
$unitValue = $unit->convert(5000, $unit::KILO);

echo $unitValue->unit; // k means KILO
echo $unitValue->value; // 5
```

If the third parameter is `null`, the class tries to find the best unit.

```php
/** @var h4kuna\Format\Number\Units\Unit $unit */

$unitValue = $unit->convertFrom(5000000, $unit::MILI, null);
echo $unitValue->unit; // k means KILO
echo $unitValue->value; // 5
```

The last method takes a string and converts it as we need. This is useful for bytes.

```php
/** @var h4kuna\Format\Number\Units\Unit $unit */

$unitValue = $unit->fromString('100k', $unit::BASE);
echo $unitValue->unit; // empty string means BASE
echo $unitValue->value; // 100000
```

## Units\Byte

Same as `Unit`, but one step is 1024 instead of 1000.

```php
use h4kuna\Format\Number\Units;

$byte = new Units\Byte();
$unitValue = $byte->fromString('128M');
echo $unitValue->unit; // empty string means BASE
echo $unitValue->value; // 134217728
```

## Units\UnitFormat

Format the converted value with its unit, `⎵` represents `&nbsp;`.

```php
use h4kuna\Format\Number;

$nff = new Number\Formats();
$unitFormat = new Number\Units\UnitFormat('B', new Number\Units\Byte(), $nff);
// or
$unitFormat = new Number\Units\ByteFormat($nff);

$unitFormat->convert(968884224); // 924,00⎵MB
$unitFormat->convert(1024); // 1,00⎵kB
```
