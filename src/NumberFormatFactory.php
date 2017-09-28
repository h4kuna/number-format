<?php

namespace h4kuna\Number;

class NumberFormatFactory
{

	/**
	 * @param int $decimals - can be array like named parameters ['decimalPoint' => '.']
	 * @param string $decimalPoint
	 * @param string $thousandsSeparator
	 * @param bool $zeroIsEmpty
	 * @param string|null $emptyValue
	 * @param bool $zeroClear
	 * @param int|null $intOnly
	 * @return NumberFormatState
	 */
	public function createNumber($decimals = 2, $decimalPoint = ',', $thousandsSeparator = null, $zeroIsEmpty = false, $emptyValue = null, $zeroClear = false, $intOnly = null)
	{
		return new NumberFormatState($decimals, $decimalPoint, $thousandsSeparator, $zeroIsEmpty, $emptyValue, $zeroClear, $intOnly);
	}


	/**
	 * @param string $mask - can be array like named parameters ['decimalPoint' => '.']
	 * @param bool $showUnit
	 * @param bool $nbsp
	 * @param int $decimals
	 * @param string $decimalPoint
	 * @param string $thousandsSeparator
	 * @param bool $zeroIsEmpty
	 * @param string|null $emptyValue
	 * @param bool $zeroClear
	 * @param int|null $intOnly
	 * @return UnitFormatState
	 */
	public function createUnit($mask = '1 U', $showUnit = true, $nbsp = true, $decimals = 2, $decimalPoint = ',', $thousandsSeparator = null, $zeroIsEmpty = false, $emptyValue = null, $zeroClear = false, $intOnly = null)
	{
		if (Utils\Parameters::canExtract($mask, __METHOD__, 0)) {
			extract($mask);
		}
		$nf = $this->createNumber($decimals, $decimalPoint, $thousandsSeparator, $zeroIsEmpty, $emptyValue, $zeroClear, $intOnly);
		return new UnitFormatState($nf, $mask, $showUnit, $nbsp);
	}


	/**
	 * @param string $unit
	 * @param string $mask - can be array like named parameters ['decimalPoint' => '.']
	 * @param bool $showUnit
	 * @param bool $nbsp
	 * @param int $decimals
	 * @param string $decimalPoint
	 * @param string $thousandsSeparator
	 * @param bool $zeroIsEmpty
	 * @param string|null $emptyValue
	 * @param bool $zeroClear
	 * @param int|null $intOnly
	 * @return UnitPersistentFormatState
	 */
	public function createUnitPersistent($unit, $mask = '1 U', $showUnit = true, $nbsp = true, $decimals = 2, $decimalPoint = ',', $thousandsSeparator = null, $zeroIsEmpty = false, $emptyValue = null, $zeroClear = false, $intOnly = null)
	{
		if (Utils\Parameters::canExtract($mask, __METHOD__, 1)) {
			extract($mask);
		}
		$uf = $this->createUnit($mask, $showUnit, $nbsp, $decimals, $decimalPoint, $thousandsSeparator, $zeroIsEmpty, $emptyValue, $zeroClear, $intOnly);
		return new UnitPersistentFormatState($uf, $unit);
	}

}