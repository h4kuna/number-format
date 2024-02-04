# Percentage

Calculate with percentage.

```php
use h4kuna\Format\Number\Percentage;

$percent = new Percentage(20);
echo $percent->with(100); // 120.0
echo $percent->without(120); // 100.0
echo $percent->without(0); // 0.0, safe division
echo $percent->withoutDiff(120); // 20.0
echo $percent->withoutDiff(0); // 0.0, safe division
echo $percent->deduct(120); // 96.0
echo $percent->diff(120); // 24.0
echo $percent->percentage; // 20.0
echo $percent->smallRatio; // 0.2
echo $percent->ratio; // 1.2
```

### Format

```php
use h4kuna\Format\Number\Percentage;
use h4kuna\Format\Number\Formatters\NumberFormatter;

$percent = new Percentage(20, new NumberFormatter(nbsp: false, unit: '%'));
echo $percent; // 20,00 %
```
