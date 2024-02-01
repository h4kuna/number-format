<?php declare(strict_types=1);

namespace h4kuna\Format\Tests\Utils;

use h4kuna\Format\Tests\TestCase;
use h4kuna\Format\Utils\Percentage;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

final class PercentageTest extends TestCase
{

	/**
	 * @dataProvider providePercentage
	 */
	public function testPercentage(float $result, float $part, float $total): void
	{
		Assert::same($result, Percentage::calculate($part, $total));
	}


	/**
	 * @return array<mixed>
	 */
	protected function providePercentage(): array
	{
		return [
			[0.0, 1.0, 0.0],
			[0.0, 0.0, 1.0],
			[-100.0, 1.0, -1.0],
			[120, 108, 90],
			[120, 120, 100],
			[120, -120, -100],
		];
	}


	/**
	 * @dataProvider provideCalculateRemainder
	 */
	public function testCalculateRemainder(float $result, float $part, float $total): void
	{
		Assert::same($result, Percentage::calculateRemainder($part, $total));
	}


	/**
	 * @return array<mixed>
	 */
	protected function provideCalculateRemainder(): array
	{
		return [
			[100.0, 1.0, 0.0],
			[100.0, 0.0, 1.0],
			[200.0, 1.0, -1.0],
			[20, 80, 100],
			[-20, 120, 100],
			[-20, -120, -100],
		];
	}


	/**
	 * @dataProvider provideSmallRatio
	 */
	public function testSmallRatio(float $expected, float $percentage): void
	{
		Assert::same($expected, Percentage::smallRatio($percentage));
	}


	/**
	 * @return array<mixed>
	 */
	protected function provideSmallRatio(): array
	{
		return [
			[0, 0],
			[0.2, 20],
			[-0.2, -20],
		];
	}


	/**
	 * @dataProvider provideRatio
	 */
	public function testRatio(float $expected, float $percentage): void
	{
		Assert::same($expected, Percentage::ratio($percentage));
	}


	/**
	 * @return array<mixed>
	 */
	protected function provideRatio(): array
	{
		return [
			[1.2, 0.2],
			[1.0, 0.0],
			[0.8, -0.2],
		];
	}


	/**
	 * @dataProvider provideDeduct
	 */
	public function testDeduct(float $expected, float $percentage, float $number): void
	{
		Assert::same($expected, Percentage::deduct($number, $percentage));
	}


	/**
	 * @return array<mixed>
	 */
	protected function provideDeduct(): array
	{
		return [
			[80, 0.2, 100],
			[96, 0.2, 120],
			[0, 0.2, 0],
		];
	}


	/**
	 * @dataProvider provideWithout
	 */
	public function testWithout(float $expected, float $ratio, float $number): void
	{
		Assert::same($expected, Percentage::without($number, $ratio));
	}


	/**
	 * @return array<mixed>
	 */
	protected function provideWithout(): array
	{
		return [
			[100, 1.2, 120],
			[0, 1.2, 0],
		];
	}
}

(new PercentageTest())->run();
