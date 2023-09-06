<?php declare(strict_types=1);

namespace h4kuna\Format\Tests\Number\Formatters;

use h4kuna\Format\Number\Formatters\NumberFormatter;
use h4kuna\Format\Number\Parameters\ZeroClear;
use h4kuna\Format\Number\Round;
use h4kuna\Format\Tests\TestCase;
use h4kuna\Format\Utils\Space;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';

/**
 * @testCase
 */
final class NumberFormatterTest extends TestCase
{

	/**
	 * @dataProvider provideFormat
	 * @param array{decimals?: int} $parameters
	 */
	public function testFormat(array $parameters, string $expected, float|int|null|string $number): void
	{
		$numberFormat = new NumberFormatter(...$parameters);
		Assert::same($expected, $numberFormat->format($number));
	}


	/**
	 * @dataProvider provideFormat
	 * @param array{decimals?: int} $parameters
	 */
	public function testModify(array $parameters, string $expected, float|int|null|string $number): void
	{
		$nf = new NumberFormatter();
		$numberFormat = $nf->modify(...$parameters);
		Assert::same($expected, $numberFormat->format($number));
	}


	public function testRoundCallback(): void
	{
		$numberFormat = new NumberFormatter(round: fn (float $number, int $precision) => round($number, $precision));
		Assert::same('1,01', $numberFormat->format('1.005'));
	}


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
				['decimals' => -1],
				'120',
				123,
			],
			[
				['decimalPoint' => '.'],
				Space::nbsp('1 000.00'),
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
				Space::nbsp('1 000,00 g'),
				1000,
			],
			[
				['unit' => 'g', 'showUnitIfEmpty' => false],
				Space::nbsp('0,00'),
				0,
			],
			[
				['unit' => '$', 'mask' => '⎵1'],
				Space::nbsp('$1 000,00'),
				1000,
			],
			[
				['round' => Round::BY_FLOOR],
				Space::nbsp('1,00'),
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

}

(new NumberFormatterTest())->run();
