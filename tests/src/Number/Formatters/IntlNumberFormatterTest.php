<?php declare(strict_types=1);

namespace h4kuna\Format\Tests\Number\Formatters;

use h4kuna\Format\Number\Formatters\IntlNumberFormatter;
use h4kuna\Format\Tests\TestCase;
use h4kuna\Format\Utils\Space;
use NumberFormatter;
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
		$numberFormatter = new NumberFormatter('cs_CZ', NumberFormatter::DECIMAL);
		$numberFormat = new IntlNumberFormatter($numberFormatter, ...$parameters);
		Assert::same(Space::nbsp($expected), $numberFormat->format($number));
	}


	/**
	 * @dataProvider provideFormat
	 * @param array{emptyValue: string, zeroIsEmpty: bool} $parameters
	 * @param string|int|float|null $number
	 */
	public function testModify(array $parameters, string $expected, $number): void
	{
		$numberFormatter = new NumberFormatter('cs_CZ', NumberFormatter::DECIMAL);
		$numberFormat = (new IntlNumberFormatter($numberFormatter))->modify(...$parameters);
		Assert::same(Space::nbsp($expected), $numberFormat->format($number));
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
