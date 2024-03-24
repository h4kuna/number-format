<?php declare(strict_types=1);

namespace h4kuna\Format\Tests\Number\Formatters;

use h4kuna\Format\Number\Formatters\IntlNumberFormatter;
use h4kuna\Format\Number\NativePhp\NumberFormatterFactory;
use h4kuna\Format\Tests\TestCase;
use h4kuna\Format\Utils\Space;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';

/**
 * @testCase
 */
final class IntlNumberFormatterTest extends TestCase
{

	/**
	 * @dataProvider provideFormat
	 * @param array{emptyValue: string, zeroIsEmpty: bool} $parameters
	 * @param string|int|float|null $number
	 */
	public function testFormat(array $parameters, string $expected, $number): void
	{
		$numberFormatter = self::createNumberFormatter()->create();
		$numberFormat = new IntlNumberFormatter($numberFormatter, ...$parameters);
		Assert::same(Space::nbsp($expected), $numberFormat->format($number));
	}


	public function testCurrency(): void
	{
		$numberFormatterFactory = self::createNumberFormatter();

		$numberFormatter = $numberFormatterFactory->currency('cs_CZ');
		$numberFormat = new IntlNumberFormatter($numberFormatter);
		Assert::same(Space::nbsp('1 000,46 Kč'), $numberFormat->format(1000.456));

		$numberFormatter = $numberFormatterFactory->currency('en_GB');
		$numberFormat = new IntlNumberFormatter($numberFormatter);
		Assert::same(Space::nbsp('£1,000.46'), $numberFormat->format(1000.456));
	}


	/**
	 * @dataProvider provideFormat
	 * @param array{emptyValue: string, zeroIsEmpty: bool} $parameters
	 * @param string|int|float|null $number
	 */
	public function testModify(array $parameters, string $expected, $number): void
	{
		$numberFormatter = self::createNumberFormatter()->create();
		$numberFormat = (new IntlNumberFormatter($numberFormatter))->modify(...$parameters);
		Assert::same(Space::nbsp($expected), $numberFormat->format($number));
	}


	private static function createNumberFormatter(): NumberFormatterFactory
	{
		return new NumberFormatterFactory('cs_CZ');
	}


	/**
	 * @return array<mixed>
	 */
	protected function provideFormat(): array
	{
		return [
			[
				[],
				'1 000,123',
				1000.1234,
			],
			[
				[],
				'1 000,124',
				1000.1235,
			],
			[
				['zeroIsEmpty' => true],
				'',
				0,
			],
			[
				['zeroIsEmpty' => true, 'emptyValue' => '?'],
				'?',
				0,
			],
			[
				['zeroIsEmpty' => false, 'emptyValue' => '?'],
				'0',
				0,
			],
			[
				['zeroIsEmpty' => false, 'emptyValue' => '?'],
				'?',
				null,
			],
			[
				['zeroIsEmpty' => false, 'emptyValue' => '?'],
				'?',
				'',
			],
			[
				['zeroIsEmpty' => false, 'emptyValue' => '? ?'],
				Space::nbsp('? ?'),
				null,
			],
			[
				[],
				'11,1',
				11.1,
			],
		];
	}

}

(new IntlNumberFormatterTest())->run();
