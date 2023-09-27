<?php declare(strict_types=1);

namespace h4kuna\Format\Tests\Date;

use DateTime;
use DateTimeZone;
use h4kuna\Format\Date\Formats;
use h4kuna\Format\Date\Formatters\DateTimeFormatter;
use h4kuna\Format\Date\Formatters\IntlDateFormatter;
use h4kuna\Format\Utils\Space;
use IntlGregorianCalendar;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class FormatsTest extends TestCase
{

	public function testFormat(): void
	{
		$formats = new Formats();
		$formats->setDefault(fn () => new DateTimeFormatter('j. n. Y'));

		Assert::same(Space::nbsp('2. 1. 1986'), $formats->get('date')->format(new DateTime('1986-01-02')));
	}


	public function testUnknownFormat(): void
	{
		$formats = new Formats();

		Assert::same(Space::nbsp('1986-01-02 01:02:03'), $formats->get('date')->format(new DateTime('1986-01-02 01:02:03')));
	}


	public function testIntlFormatter(): void
	{
		$formats = new Formats();
		$timezone = new DateTimeZone('Europe/Prague');
		$locale = 'cs_CZ';
		$formats->add('date', new IntlDateFormatter(new \IntlDateFormatter($locale, \IntlDateFormatter::MEDIUM, \IntlDateFormatter::MEDIUM, $timezone, IntlGregorianCalendar::createInstance($timezone, $locale))));

		Assert::same(Space::nbsp('2. 1. 1986 0:00:00'), $formats->get('date')->format(new DateTime('1986-01-02', $timezone)));
	}


	protected function setUp()
	{
		setlocale(LC_TIME, 'cs_CZ.utf8');
	}

}

(new FormatsTest())->run();
