# Date

Define own formats for date and time. Both classes [IntlDateFormatter](../src/Date/Formatters/IntlDateFormatter.php) and [DateTimeFormatter](../src/Date/Formatters/DateTimeFormatter.php) has implemented interface [Formatter](../src/Date/Formatter.php).

#### Collection of dates

```php
use h4kuna\Format\Date;

$formats = new Date\Formats([
	'date' => new Date\Formatters\DateTimeFormatter('j. n. Y'),
	'time' => static fn () => new Date\Formatters\DateTimeFormatter('H:i'), // callback like factory if is needed
	'dateTime' => static fn () => new Date\Formatters\DateTimeFormatter('j. n. Y H:i'),
]);

$dateObject = new \DateTime('2023-06-13 12:30:40');
$formats->get('date')->format($dateObject); // 13. 6. 2023 
$formats->get('time')->format($dateObject); // 12:30 
$formats->get('dateTime')->format($dateObject); // 13. 6. 2023 12:30 
```

For better use, you can extends class Formats and add your own methods.

```php
use h4kuna\Format\Date;

class MyFormats extends Date\Formats 
{
	public function date(?\DateTimeInterface $data): string 
	{
		return $this->get('date')->format($data);
	}
}

$formats = new MyFormats([
	'date' => new Date\Formatters\DateTimeFormatter('j. n. Y'),
]);

$dateObject = new \DateTime('2023-06-13 12:30:40');
$formats->date($dateObject); // 13. 6. 2023 
```

### Support IntlDateFormatter

Format by locale.

```php
use h4kuna\Format\Date;
use IntlDateFormatter;

$intlFormatter = new IntlDateFormatter('cs_CZ', IntlDateFormatter::MEDIUM, IntlDateFormatter::MEDIUM,)

$formats = new Date\Formats([
	'date' => new Date\Formatters\IntlDateFormatter($intlFormatter),
]);

$date = new \DateTime('2023-06-13 12:30:40');
$formats->get('date')->format($date); // 12. 6. 2023 12:30:40
```

## Nette integration

Config
```neon
services:
	format.date:
		factory: h4kuna\Format\Date\Formatters\DateTimeFormatter('j. n. Y')
		autowired: false
	format.time:
		factory: h4kuna\Format\Date\Formatters\DateTimeFormatter('H:i:s')
		autowired: false

	# Accessor with all formats
	number.formats: h4kuna\Format\Date\FormatsAccessor(
		date: @format.date
		time: @format.time
	)

	latte.latteFactory:
		setup:
			- addFilter('date', @format.date)
			- addFilter('time', @format.time)
```

In template
```latte
{=(new DateTime())|date}<br>
{=(new DateTime())|time}
```
