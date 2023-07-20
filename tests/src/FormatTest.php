<?php declare(strict_types=1);

namespace h4kuna\Number\Tests;

use h4kuna\Number\Format;
use h4kuna\Number\Utils\Round;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
final class FormatTest extends TestCase
{
	/**
	 * @return array<int, array<mixed>>
	 */
	protected function provideNumber(): array
	{
		return [
			[
				'0,00',
				[0],
			],
			[
				'0,0',
				[0, 1],
			],
			[
				'0.0',
				[0, 1, '.'],
			],
			[
				'1,000.0',
				[1000, 1, '.', ','],
			],
			[
				'1 230',
				[1234, -1],
			],
			[
				'1 240',
				[1234, -1, 'roundCallback' => Round::create(Round::BY_CEIL)],
			],
			[
				'1 230',
				[1234, -1, 'roundCallback' => Round::create(Round::BY_FLOOR)],
			],
			[
				'1 240',
				[1235, -1],
			],
			[
				'1 240',
				[1235, -1, 'roundCallback' => Round::create(Round::BY_CEIL)],
			],
			[
				'1 230',
				[1235, -1, 'roundCallback' => Round::create(Round::BY_FLOOR)],
			],
			[
				'0,568',
				[0.5678, 3, 'roundCallback' => Round::create(Round::BY_CEIL)],
			],
			[
				'0,567',
				[0.5678, 3, 'roundCallback' => Round::create(Round::BY_FLOOR)],
			],
			[
				'0,568',
				[0.5671, 3, 'roundCallback' => Round::create(Round::BY_CEIL)],
			],
			[
				'0,567',
				[0.5671, 3, 'roundCallback' => Round::create(Round::BY_FLOOR)],
			],
		];
	}


	/**
	 * @dataProvider provideNumber
	 * @param array<float> $input
	 */
	public function testNumber(string $expected, array $input): void
	{
		Assert::same($expected, Format::number(...$input));
	}


	/**
	 * @return array<int, array<int, array<int|string, bool|float|int|string|null>|string>>
	 */
	protected static function provideUnit(): array
	{
		return [
			[
				'0,00',
				[0],
			],
			[
				'',
				[null],
			],
			[
				'',
				[''],
			],
			[
				'kg1,00',
				[
					'number' => '1',
					'mask' => 'kg1',
				],
			],
			[
				'1 655 kg',
				[
					'number' => 1655,
					'mask' => '1 kg',
					'decimals' => 0,
					'nbsp' => false,
				],
			],
			[
				self::nbsp('1 655 kg'),
				[
					'number' => 1655,
					'mask' => '1 kg',
					'decimals' => 0,
					'nbsp' => true,
				],
			],
			[
				'1 655',
				[
					'number' => 1655,
					'decimals' => 0,
					'nbsp' => false,
				],
			],
			[
				self::nbsp('1 655'),
				[
					'number' => 1655,
					'decimals' => 0,
					'nbsp' => true,
				],
			],
			[
				'-kg',
				[
					'number' => 0,
					'emptyValue' => '-',
					'zeroIsEmpty' => true,
					'mask' => '1kg',
				],
			],
			[
				'1,00',
				[
					'number' => 1,
					'zeroIsEmpty' => true,
				],
			],
			[
				'-',
				[
					'number' => 0,
					'emptyValue' => '-',
					'zeroIsEmpty' => true,
					'showUnitIfEmpty' => false,
					'mask' => '1kg',
				],
			],
			[
				'-',
				[
					'number' => 0,
					'emptyValue' => '-',
					'zeroIsEmpty' => true,
				],
			],
			[
				'-',
				[
					'number' => null,
					'emptyValue' => '-',
				],
			],
			[
				'-',
				[
					'number' => '',
					'emptyValue' => '-',
				],
			],
			[
				'0,00',
				[
					'number' => 0,
				],
			],
			[
				'0,00',
				[
					'number' => 0,
					'emptyValue' => '',
				],
			],

			// zeroIsEmpty
			[
				'',
				[
					'number' => 0,
					'zeroIsEmpty' => true,
				],
			],
			// zero clear
			[
				'1,005',
				[
					'number' => 1.005,
					'decimals' => 3,
					'nbsp' => false,
					'zeroClear' => true,
				],
			],
			[
				'1,05',
				[
					'number' => 1.05,
					'decimals' => 3,
					'nbsp' => false,
					'zeroClear' => true,
				],
			],
			[
				'1,05 kg',
				[
					'number' => '1.050',
					'decimals' => 3,
					'mask' => '1 kg',
					'nbsp' => false,
					'zeroClear' => true,
				],
			],
		];
	}


	/**
	 * @dataProvider provideUnit
	 * @param array<float|int|string|null> $input
	 */
	public function testUnit(string $expected, array $input): void
	{
		Assert::same($expected, Format::unit(...$input));
	}


	private static function nbsp(string $value): string
	{
		return str_replace(' ', Format::NBSP, $value);
	}
}

(new FormatTest())->run();
