<?php

namespace h4kuna\Number\Units;

use h4kuna\Number,
	Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

class UnitTest extends \Tester\TestCase
{

	public function testConvert()
	{
		$unit = new Unit;
		$unitValue = $unit->convert(1.0, $unit::BASE);
		Assert::same(1.0, $unitValue->value);
		Assert::same($unit::BASE, $unitValue->unit);

		$unitValue = $unit->convert(100000.0, $unit::KILO);
		Assert::same(100.0, $unitValue->value);
		Assert::same($unit::KILO, $unitValue->unit);

		$unitValue = $unit->convert(1, $unit::MILI);
		Assert::same(1000, $unitValue->value);
		Assert::same($unit::MILI, $unitValue->unit);

		$unitValue = $unit->convert(0);
		Assert::same(0, $unitValue->value);
		Assert::same($unit::BASE, $unitValue->unit);
		Assert::same(0, $unit->convert('0.0')->value);
		Assert::same(0, $unit->convert('0')->value);
		Assert::same(0, $unit->convert(0.0)->value);
		Assert::same(0, $unit->convert(null)->value);
	}


	public function testDiffBase()
	{
		$unit = new Unit(Unit::KILO);
		$unitValue = $unit->convert(1, $unit::KILO);
		Assert::same(1, $unitValue->value);
		Assert::same($unit::KILO, $unitValue->unit);

		$unitValue = $unit->convert(1, $unit::BASE);
		Assert::same(1000, $unitValue->value);
		Assert::same($unit::BASE, $unitValue->unit);

		$unitValue = $unit->convert(1, $unit::MEGA);
		Assert::same(0.001, $unitValue->value);
		Assert::same($unit::MEGA, $unitValue->unit);
	}


	public function testConvertAuto()
	{
		$unit = new Unit;
		$unitValue = $unit->convert(10.0);
		Assert::same(10.0, $unitValue->value);
		Assert::same($unit::BASE, $unitValue->unit);

		$unitValue = $unit->convert(100000.0);
		Assert::same(100.0, $unitValue->value);
		Assert::same($unit::KILO, $unitValue->unit);

		$unitValue = $unit->convert(1e18);
		Assert::equal(1000.0, $unitValue->value);
		Assert::same($unit::PETA, $unitValue->unit);

		$unitValue = $unit->convert(1e-15);
		Assert::equal(0.001, $unitValue->value);
		Assert::same($unit::PICO, $unitValue->unit);
	}


	public function testFromString()
	{
		$unit = new Unit;
		$unitValue = $unit->fromString('128M');
		Assert::same(128000000, $unitValue->value);
		Assert::same($unit::BASE, $unitValue->unit);

		$unitValue = $unit->fromString('+128M');
		Assert::same(128000000, $unitValue->value);

		$unitValue = $unit->fromString('-128M');
		Assert::same(-128000000, $unitValue->value);

		$unitValue = $unit->fromString('128.M');
		Assert::same(128000000.0, $unitValue->value);

		$unitValue = $unit->fromString('128,M');
		Assert::same(128000000.0, $unitValue->value);

		$unitValue = $unit->fromString('128.12M');
		Assert::same(128120000.0, $unitValue->value);

		$unitValue = $unit->fromString('128,12M');
		Assert::same(128120000.0, $unitValue->value);

		$unitValue = $unit->fromString('0.12M');
		Assert::same(120000.0, $unitValue->value);

		$unitValue = $unit->fromString(',12M');
		Assert::same(120000.0, $unitValue->value);

		$unitValue = $unit->fromString('1 000 M');
		Assert::same(1000000000, $unitValue->value);

		Assert::exception(function () use ($unit) {
			$unit->fromString('M');
		}, Number\InvalidArgumentException::class);

		Assert::exception(function () use ($unit) {
			$unit->fromString('128');
		}, Number\InvalidArgumentException::class);
	}

}

(new UnitTest())->run();
