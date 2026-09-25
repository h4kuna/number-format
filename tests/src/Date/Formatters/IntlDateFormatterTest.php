<?php declare(strict_types = 1);

namespace h4kuna\Format\Tests\Date\Formatters;

use DateTime;
use DateTimeInterface;
use h4kuna\Format\Date\Formatters\IntlDateFormatter;
use h4kuna\Format\Tests\TestCase;
use h4kuna\Format\Utils\Space;
use IntlDateFormatter as PhpIntlDateFormatter;
use Tester\Assert;
use function setlocale;
use const LC_TIME;

require __DIR__ . '/../../../bootstrap.php';

/**
 * @testCase
 */
final class IntlDateFormatterTest extends TestCase
{

	/**
	 * @param array{nbsp: bool, emptyValue: string} $parameters
	 *
	 * @dataProvider provideFormat
	 */
	public function testFormat(
		array $parameters,
		string $expected,
		?DateTimeInterface $date,
	): void
	{
		$formatter = new PhpIntlDateFormatter('cs_CZ', PhpIntlDateFormatter::MEDIUM, PhpIntlDateFormatter::MEDIUM);

		$intlDateFormatter = new IntlDateFormatter($formatter, ...$parameters);
		Assert::same($expected, $intlDateFormatter->format($date));
	}

	/**
	 * @param array{nbsp: bool, emptyValue: string} $parameters
	 *
	 * @dataProvider provideFormat
	 */
	public function testModify(
		array $parameters,
		string $expected,
		?DateTimeInterface $date,
	): void
	{
		$formatter = new PhpIntlDateFormatter('cs_CZ', PhpIntlDateFormatter::MEDIUM, PhpIntlDateFormatter::MEDIUM);

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
