## v6.0

- global namespace `h4kuna\Number` renamed to `h4kuna\Format`
- add new namespace `h4kuna\Format\Date`, other files move to namespace `h4kuna\Format\Number`
- behavior is same like v5.0 but namespaces are different
- add support for php native [NumberFormatter](https://www.php.net/manual/en/class.numberformatter.php)
- I don't keep back compatibility, because v5.0 is not widespread, but here are aliases. You can add to your project and
  will work.

```php
back compatibility with v5
use h4kuna;
class_alias(h4kuna\Format\Number\Formats::class, 'h4kuna\Number\Utils\Formats');
class_alias(h4kuna\Format\Number\NumberFormat::class, 'h4kuna\Number\Format');
class_alias(h4kuna\Format\Number\Percent::class, 'h4kuna\Number\Percent');
class_alias(h4kuna\Format\Number\Round::class, 'h4kuna\Number\Utils\Round');
class_alias(h4kuna\Format\Number\Tax::class, 'h4kuna\Number\Tax');
class_alias(h4kuna\Format\Number\UnitValue::class, 'h4kuna\Number\Utils\UnitValue');

// parameters
class_alias(h4kuna\Format\Number\Parameters\ZeroClear::class, 'h4kuna\Number\Parameters\Format\ZeroClear');

// formatters
class_alias(h4kuna\Format\Number\Formatters\NumberFormatter::class, 'h4kuna\Number\NumberFormat');

// namespace Unit
class_alias(h4kuna\Format\Number\Units\Byte::class, 'h4kuna\Number\Units\Byte');
class_alias(h4kuna\Format\Number\Units\Unit::class, 'h4kuna\Number\Units\Unit');
class_alias(h4kuna\Format\Number\Units\UnitFormat::class, 'h4kuna\Number\Units\UnitFormat');
```

## v5.0
- support php 8.0+
- add new static class [Format](src/Format.php), you can format numbers without instance of class
- add new static class [NumberFormat](src/Number/NumberFormat.php), you can format numbers without instance of class
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
- support php 7.4+
- removed interface NumberFormat
- renamed class NumberFormatState -> NumberFormat
- removed class UnitFormatState, replace by `NumberFormat` like `$nf = new NumberFormat(); $nf->enableExtendFormat();`
- removed class UnitPersistentFormatState, replace by `NumberFormat` like `$nf = new NumberFormat(); $nf->enableExtendFormat('1 MY_PERSISTENT_UNIT');`
- method format has second parameter like decimals and third is dynamic defined unit
- char for unit in mask changed to `⎵`
- added parameter nbsp to NumberFormat::__construct()

## v3.0
This version is same like v2.0 but support php7.1+.

## v2.0

New behavior is representing by one class is one type of format. Onetime create class and you can'nt change by life of object. Added new classes for number, unit and currency. Working with percent and taxes are better too.

## v1.3
Here is [manual](//github.com/h4kuna/number-format/tree/v1.3.0) for older version 1.3.0.
