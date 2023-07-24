<?php declare(strict_types=1);

namespace h4kuna\Number\Tests;

use h4kuna\Number\Format;
use h4kuna\Number\NumberFormat;
use h4kuna\Number\Parameters\Format\ZeroClear;
use h4kuna\Number\Utils\Round;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
final class NumberFormatTest extends TestCase
{

	/**
	 * @return array<mixed>
	 */
	protected function provideFormat(): array
	{
		return [
			[
				['decimals' => 3],
				'1,000',
				1,
			],
			[
				['decimalPoint' => '.'],
				self::nbsp('1 000.00'),
				1000,
			],
			[
				['thousandsSeparator' => '.'],
				'1.000,00',
				1000,
			],
			[
				['nbsp' => false],
				'1 000,00',
				1000,
			],
			[
				['zeroClear' => ZeroClear::DECIMALS],
				'1',
				'1.00',
			],
			[
				['emptyValue' => '-'],
				'-',
				null,
			],
			[
				['unit' => 'g'],
				self::nbsp('1 000,00 g'),
				1000,
			],
			[
				['unit' => 'g', 'showUnitIfEmpty' => false],
				self::nbsp('0,00'),
				0,
			],
			[
				['unit' => '$', 'mask' => '⎵1'],
				self::nbsp('$1 000,00'),
				1000,
			],
			[
				['round' => Round::BY_FLOOR],
				self::nbsp('1,00'),
				1.005,
			],
			[
				['zeroIsEmpty' => true],
				'',
				0,
			],
			[
				['zeroIsEmpty' => false],
				'0,00',
				0,
			],
			[
				['zeroClear' => ZeroClear::DECIMALS_EMPTY],
				'0',
				0,
			],
			[
				['zeroClear' => ZeroClear::DECIMALS_EMPTY],
				'0,10',
				0.1,
			],
		];
	}


	/**
	 * @dataProvider provideFormat
	 * @param array{decimals?: int} $parameters
	 */
	public function testFormat(array $parameters, string $expected, float|int|null|string $number): void
	{
		$numberFormat = new NumberFormat(...$parameters);
		Assert::same($expected, $numberFormat->format($number));
	}


	/**
	 * @dataProvider provideFormat
	 * @param array{decimals?: int} $parameters
	 */
	public function testModify(array $parameters, string $expected, float|int|null|string $number): void
	{
		$nf = new NumberFormat();
		$numberFormat = $nf->modify(...$parameters);
		Assert::same($expected, $numberFormat->format($number));
	}


	private static function nbsp(string $value): string
	{
		return str_replace(' ', Format::NBSP, $value);
	}

}

(new NumberFormatTest())->run();
