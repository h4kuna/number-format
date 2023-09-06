<?php declare(strict_types=1);

namespace h4kuna\Format\Tests\Date\Formatters;

use DateTime;
use DateTimeInterface;
use h4kuna\Format\Date\Formatters\IntlDateFormatter;
use h4kuna\Format\Tests\TestCase;
use h4kuna\Format\Utils\Space;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

/**
 * @testCase
 */
final class IntlDateFormatterTest extends TestCase
{

	/**
	 * @dataProvider provideFormat
	 * @param array{nbsp: bool, emptyValue: string} $parameters
	 */
	public function testFormat(array $parameters, string $expected, ?DateTimeInterface $date): void
	{
		$formatter = new \IntlDateFormatter('cs_CZ', \IntlDateFormatter::MEDIUM, \IntlDateFormatter::MEDIUM);

		$intlDateFormatter = new IntlDateFormatter($formatter, ...$parameters);
		Assert::same($expected, $intlDateFormatter->format($date));
	}


	/**
	 * @dataProvider provideFormat
	 * @param array{nbsp: bool, emptyValue: string} $parameters
	 */
	public function testModify(array $parameters, string $expected, ?DateTimeInterface $date): void
	{
		$formatter = new \IntlDateFormatter('cs_CZ', \IntlDateFormatter::MEDIUM, \IntlDateFormatter::MEDIUM);

		$intlDateFormatter = (new IntlDateFormatter($formatter))->modify(...$parameters);
		Assert::same($expected, $intlDateFormatter->format($date));
	}


	protected function setUp(): void
	{
		setlocale(LC_TIME, 'cs_CZ.utf8');
	}


	/**
	 * @return array<mixed>
	 */
	protected function provideFormat(): array
	{
		return [
			[
				['nbsp' => false],
				'2. 1. 1986 0:00:00',
				new DateTime('1986-01-02'),
			],
			[
				[],
				Space::nbsp('2. 1. 1986 0:00:00'),
				new DateTime('1986-01-02'),
			],
			[
				['emptyValue' => '-', 'nbsp' => false],
				'2. 1. 1986 0:00:00',
				new DateTime('1986-01-02'),
			],
			[
				['emptyValue' => '-', 'nbsp' => false],
				'-',
				null,
			],
			[
				['emptyValue' => '- -'],
				Space::nbsp('- -'),
				null,
			],
		];
	}

}

(new IntlDateFormatterTest())->run();
