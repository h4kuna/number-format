<?php declare(strict_types = 1);

namespace h4kuna\Format\Tests\Number\Units;

use h4kuna\Format\Exceptions\InvalidArgumentException;
use h4kuna\Format\Number\Units\Unit;
use h4kuna\Format\Tests\TestCase;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

/**
 * @testCase
 */
final class UnitTest extends TestCase
{

	public function testConvert(): void
	{
		$unit = new Unit();
		$unitValue = $unit->convert(1.0, $unit::BASE);
		Assert::same(1.0, $unitValue->value);
		Assert::same($unit::BASE, $unitValue->unit);

		$unitValue = $unit->convert(100_000.0, $unit::KILO);
		Assert::same(100.0, $unitValue->value);
		Assert::same($unit::KILO, $unitValue->unit);

		$unitValue = $unit->convert(1, $unit::MILI);
		Assert::same(1000.0, $unitValue->value);
		Assert::same($unit::MILI, $unitValue->unit);

		$unitValue = $unit->convert(0);
		Assert::same(0.0, $unitValue->value);
		Assert::same($unit::BASE, $unitValue->unit);
		Assert::same(0.0, $unit->convert(0.0)->value);
	}

	public function testGetter(): void
	{
		$unit = new Unit();
		Assert::same($unit::UNITS, $unit->getUnits());
		Assert::same($unit::BASE, $unit->getFrom());
	}

	public function testNotAllowedUnit(): void
	{
		$unit = new Unit();
		Assert::exception(static fn () => $unit->convert(0, 'foo'), InvalidArgumentException::class);

		Assert::exception(static fn () => new Unit('foo'), InvalidArgumentException::class);
		$unit = new Unit();

		Assert::exception(static fn () => $unit->convertFrom(0, null, 'foo'), InvalidArgumentException::class);

		Assert::exception(static fn () => $unit->convertFrom(0, 'foo'), InvalidArgumentException::class);
	}

	public function testDiffBase(): void
	{
		$unit = new Unit(Unit::KILO);
		$unitValue = $unit->convert(1, $unit::KILO);
		Assert::same(1.0, $unitValue->value);
		Assert::same($unit::KILO, $unitValue->unit);

		$unitValue = $unit->convert(1, $unit::BASE);
		Assert::same(1000.0, $unitValue->value);
		Assert::same($unit::BASE, $unitValue->unit);

		$unitValue = $unit->convert(1, $unit::MEGA);
		Assert::same(0.001, $unitValue->value);
		Assert::same($unit::MEGA, $unitValue->unit);
		Assert::same('0.001 M', (string) $unitValue);
	}

	public function testConvertAuto(): void
	{
		$unit = new Unit();
		$unitValue = $unit->convert(10.0);
		Assert::same(10.0, $unitValue->value);
		Assert::same($unit::BASE, $unitValue->unit);

		$unitValue = $unit->convert(100_000.0);
		Assert::same(100.0, $unitValue->value);
		Assert::same($unit::KILO, $unitValue->unit);

		$unitValue = $unit->convert(1e18);
		Assert::equal(1000.0, $unitValue->value);
		Assert::same($unit::PETA, $unitValue->unit);

		$unitValue = $unit->convert(1e-15);
		Assert::equal(0.001, $unitValue->value);
		Assert::same($unit::PICO, $unitValue->unit);
	}

	public function testFromString(): void
	{
		$unit = new Unit();
		$unitValue = $unit->fromString('128M');
		Assert::same(128_000_000.0, $unitValue->value);
		Assert::same($unit::BASE, $unitValue->unit);

		$unitValue = $unit->fromString('+128M');
		Assert::same(128_000_000.0, $unitValue->value);

		$unitValue = $unit->fromString('-128M');
		Assert::same(-128_000_000.0, $unitValue->value);

		$unitValue = $unit->fromString('128.M');
		Assert::same(128_000_000.0, $unitValue->value);

		$unitValue = $unit->fromString('128,M');
		Assert::same(128_000_000.0, $unitValue->value);

		$unitValue = $unit->fromString('128.12M');
		Assert::same(128_120_000.0, $unitValue->value);

		$unitValue = $unit->fromString('128,12M');
		Assert::same(128_120_000.0, $unitValue->value);

		$unitValue = $unit->fromString('0.12M');
		Assert::same(120_000.0, $unitValue->value);

		$unitValue = $unit->fromString(',12M');
		Assert::same(120_000.0, $unitValue->value);

		$unitValue = $unit->fromString('1 000 M');
		Assert::same(1_000_000_000.0, $unitValue->value);

		Assert::exception(static function () use ($unit): void {
			$unit->fromString('M');
		}, InvalidArgumentException::class);

		Assert::exception(static function () use ($unit): void {
			$unit->fromString('128');
		}, InvalidArgumentException::class);
	}

}

(new UnitTest())->run();
