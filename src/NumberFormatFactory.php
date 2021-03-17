<?php declare(strict_types=1);

namespace h4kuna\Number;

class NumberFormatFactory
{

	/**
	 * @param array<string, bool|int|string|null>|string $mask - can be array like named parameters ['decimalPoint' => '.']
	 */
	public function createUnitPersistent(
		string $unit,
		$mask = '1 U',
		bool $showUnit = true,
		bool $nbsp = true,
		int $decimals = 2,
		string $decimalPoint = ',',
		?string $thousandsSeparator = null,
		bool $zeroIsEmpty = false,
		?string $emptyValue = null,
		bool $zeroClear = false,
		int $intOnly = NumberFormatState::DISABLE_INT_ONLY,
		int $round = NumberFormatState::ROUND_DEFAULT
	): UnitPersistentFormatState
	{
		if (Utils\Parameters::canExtract($mask, __METHOD__, 1)) {
			extract($mask);
		}
		$uf = $this->createUnit($mask, $showUnit, $nbsp, $decimals, $decimalPoint, $thousandsSeparator, $zeroIsEmpty, $emptyValue, $zeroClear, $intOnly, $round);
		return new UnitPersistentFormatState($uf, $unit);
	}


	/**
	 * @param array<string, bool|int|string|null>|string $mask - can be array like named parameters ['decimalPoint' => '.']
	 */
	public function createUnit(
		$mask = '1 U',
		bool $showUnit = true,
		bool $nbsp = true,
		int $decimals = 2,
		string $decimalPoint = ',',
		?string $thousandsSeparator = null,
		bool $zeroIsEmpty = false,
		?string $emptyValue = null,
		bool $zeroClear = false,
		int $intOnly = NumberFormatState::DISABLE_INT_ONLY,
		int $round = NumberFormatState::ROUND_DEFAULT
	): UnitFormatState
	{
		if (Utils\Parameters::canExtract($mask, __METHOD__, 0)) {
			extract($mask);
		}
		$nf = $this->createNumber($decimals, $decimalPoint, $thousandsSeparator, $zeroIsEmpty, $emptyValue, $zeroClear, $intOnly, $round);
		return new UnitFormatState($nf, $mask, $showUnit, $nbsp);
	}


	/**
	 * @param array<string, bool|int|string|null>|int $decimals - can be array like named parameters ['decimalPoint' => '.']
	 */
	public function createNumber(
		$decimals = 2,
		string $decimalPoint = ',',
		?string $thousandsSeparator = null,
		bool $zeroIsEmpty = false,
		?string $emptyValue = null,
		bool $zeroClear = false,
		int $intOnly = NumberFormatState::DISABLE_INT_ONLY,
		int $round = NumberFormatState::ROUND_DEFAULT
	): NumberFormatState
	{
		return new NumberFormatState($decimals, $decimalPoint, $thousandsSeparator, $zeroIsEmpty, $emptyValue, $zeroClear, $intOnly, $round);
	}

}
