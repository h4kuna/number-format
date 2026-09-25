# Percentage

Calculate with percentage.

```php
use h4kuna\Format\Number\Percentage;

$percent = new Percentage(20);
$percent->with(100); // 120.0
$percent->without(120); // 100.0
$percent->without(0); // 0.0
$percent->withoutDiff(120); // 20.0
$percent->withoutDiff(0); // 0.0
$percent->deduct(120); // 96.0
$percent->diff(120); // 24.0
$percent->percentage; // 20.0
$percent->smallRatio; // 0.2
$percent->ratio; // 1.2
```

### Format

```php
use h4kuna\Format\Number\Percentage;
use h4kuna\Format\Number\Formatters\NumberFormatter;

$percent = new Percentage(20, new NumberFormatter(nbsp: false, unit: '%'));
echo $percent; // 20,00 %

$percent->modify(10); // new instance with 10 %, the formatter is kept
```
