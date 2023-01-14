<?php declare(strict_types=1);

namespace h4kuna\Number;

class NumberFormatFactory
{

	/**
	 * @param array<string, bool|int|string|null>|string $mask - can be array like named parameters ['decimalPoint' => '.']
	 */
	public function createUnitPersistent(
		string $unit,
		$mask = '1 ⎵',
		bool $showUnit = true,
		bool $nbsp = true,
		int $decimals = 2,
		string $decimalPoint = ',',
		string $thousandsSeparator = ' ',
		bool $zeroIsEmpty = false,
		?string $emptyValue = null,
		bool $zeroClear = false,
		int $intOnly = NumberFormat::DISABLE_INT_ONLY,
		int $round = NumberFormat::ROUND_DEFAULT
	): NumberFormat
	{
		if (Utils\Parameters::canExtract($mask, __METHOD__, 1)) {
			extract($mask);
		}

		return $this->createUnit(str_replace('⎵', $unit, $mask), $showUnit, $nbsp, $decimals, $decimalPoint, $thousandsSeparator, $zeroIsEmpty, $emptyValue, $zeroClear, $intOnly, $round);
	}


	/**
	 * @param array<string, bool|int|string|null>|string $mask - can be array like named parameters ['decimalPoint' => '.']
	 */
	public function createUnit(
		$mask = '1 ⎵',
		bool $showUnit = true,
		bool $nbsp = true,
		int $decimals = 2,
		string $decimalPoint = ',',
		string $thousandsSeparator = ' ',
		bool $zeroIsEmpty = false,
		?string $emptyValue = null,
		bool $zeroClear = false,
		int $intOnly = NumberFormat::DISABLE_INT_ONLY,
		int $round = NumberFormat::ROUND_DEFAULT
	): NumberFormat
	{
		if (Utils\Parameters::canExtract($mask, __METHOD__)) {
			extract($mask);
		}
		$nf = $this->createNumber($decimals, $decimalPoint, $thousandsSeparator, $nbsp, $zeroIsEmpty, $emptyValue, $zeroClear, $intOnly, $round);
		$nf->enableExtendFormat($mask, $showUnit);

		return $nf;
	}


	/**
	 * @param array<string, bool|int|string|null>|int $decimals - can be array like named parameters ['decimalPoint' => '.']
	 */
	public function createNumber(
		$decimals = 2,
		string $decimalPoint = ',',
		string $thousandsSeparator = ' ',
		bool $nbsp = true,
		bool $zeroIsEmpty = false,
		?string $emptyValue = null,
		bool $zeroClear = false,
		int $intOnly = NumberFormat::DISABLE_INT_ONLY,
		int $round = NumberFormat::ROUND_DEFAULT
	): NumberFormat
	{
		return new NumberFormat($decimals, $decimalPoint, $thousandsSeparator, $nbsp, $zeroIsEmpty, $emptyValue, $zeroClear, $intOnly, $round);
	}

}
