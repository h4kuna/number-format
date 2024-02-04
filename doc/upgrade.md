## From v3.0 to v6.0

By statistic the version 3 is most used. I describe upgrade from v3.0 to v6.0.

- removed interface NumberFormat, now this is static class like wrap on [number_format()](https://www.php.net/manual/en/function.number-format.php).
- all classes `NumberFormatState`, `UnitFormatState`, `UnitPersistentFormatState` are joined to one [NumberFormatter](./src/Number/Formatters/NumberFormatter.php)
- change namespace from `h4kuna\Number` to `h4kuna\Format\Number` or `h4kuna\Format\Number\Formatters`
- removed support, defined parameters by array, from php8 is native by named parameters

### NumberFormatter changes

- class `NumberFormatter` is immutable
- is callable by `__invoke()` method
- method format() has only one parameter, `$number`
- mask has new char for unit, old `U` replaced by `⎵`, `NumberFormatter(mask: '⎵ 1')`
- parameter `intOnly` was removed
- parameter `round` is callback, see [Round.php](./src/Number/Round.php)
- add `nbsp` parameter
- parameter `zeroClear` is integer instead of bool, see [ZeroClear.php](./src/Number/Parameters/ZeroClear.php)
