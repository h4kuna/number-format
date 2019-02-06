<?php declare(strict_types=1);

namespace h4kuna\Number;

class NumberFormatFactory
{

	/**
	 * @param array|int $decimals - can be array like named parameters ['decimalPoint' => '.']
	 * @param string $decimalPoint
	 * @param string $thousandsSeparator
	 * @param bool $zeroIsEmpty
	 * @param string|null $emptyValue
	 * @param bool $zeroClear
	 * @param int $intOnly
	 */
	public function createNumber($decimals = 2, string $decimalPoint = ',', ?string $thousandsSeparator = null, bool $zeroIsEmpty = false, ?string $emptyValue = null, bool $zeroClear = false, int $intOnly = NumberFormatState::DISABLE_INT_ONLY): NumberFormatState
	{
		return new NumberFormatState($decimals, $decimalPoint, $thousandsSeparator, $zeroIsEmpty, $emptyValue, $zeroClear, $intOnly);
	}


	/**
	 * @param array|string $mask - can be array like named parameters ['decimalPoint' => '.']
	 * @param bool $showUnit
	 * @param bool $nbsp
	 * @param int $decimals
	 * @param string $decimalPoint
	 * @param string $thousandsSeparator
	 * @param bool $zeroIsEmpty
	 * @param string|null $emptyValue
	 * @param bool $zeroClear
	 * @param int $intOnly
	 */
	public function createUnit($mask = '1 U', bool $showUnit = true, bool $nbsp = true, int $decimals = 2, string $decimalPoint = ',', ?string $thousandsSeparator = null, bool $zeroIsEmpty = false, ?string $emptyValue = null, bool $zeroClear = false, int $intOnly = NumberFormatState::DISABLE_INT_ONLY): UnitFormatState
	{
		if (Utils\Parameters::canExtract($mask, __METHOD__, 0)) {
			extract($mask);
		}
		$nf = $this->createNumber($decimals, $decimalPoint, $thousandsSeparator, $zeroIsEmpty, $emptyValue, $zeroClear, $intOnly);
		return new UnitFormatState($nf, $mask, $showUnit, $nbsp);
	}


	/**
	 * @param string $unit
	 * @param array|string $mask - can be array like named parameters ['decimalPoint' => '.']
	 * @param bool $showUnit
	 * @param bool $nbsp
	 * @param int $decimals
	 * @param string $decimalPoint
	 * @param string $thousandsSeparator
	 * @param bool $zeroIsEmpty
	 * @param string|null $emptyValue
	 * @param bool $zeroClear
	 * @param int $intOnly
	 */
	public function createUnitPersistent(string $unit, $mask = '1 U', bool $showUnit = true, bool $nbsp = true, int $decimals = 2, string $decimalPoint = ',', ?string $thousandsSeparator = null, bool $zeroIsEmpty = false, ?string $emptyValue = null, bool $zeroClear = false, int $intOnly = NumberFormatState::DISABLE_INT_ONLY): UnitPersistentFormatState
	{
		if (Utils\Parameters::canExtract($mask, __METHOD__, 1)) {
			extract($mask);
		}
		$uf = $this->createUnit($mask, $showUnit, $nbsp, $decimals, $decimalPoint, $thousandsSeparator, $zeroIsEmpty, $emptyValue, $zeroClear, $intOnly);
		return new UnitPersistentFormatState($uf, $unit);
	}

}
