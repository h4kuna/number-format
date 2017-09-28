<?php

namespace h4kuna\Number;

class NumberFormatState
{

	const
		ZERO_CLEAR = 1,
		ZERO_IS_EMPTY = 2;

	/** @var string utf-8 &nbsp; */
	const NBSP = "\xc2\xa0";

	/** @var string */
	private $thousandsSeparator;

	/** @var int */
	private $decimals = 0;

	/** @var string */
	private $decimalPoint;

	/** @var string|null */
	private $emptyValue;

	/** @var int */
	private $flag = 0;

	/** @var int|null */
	private $intOnly;


	public function __construct($decimals = 2, $decimalPoint = ',', $thousandsSeparator = null, $zeroIsEmpty = false, $emptyValue = null, $zeroClear = false, $intOnly = null)
	{
		if (Utils\Parameters::canExtract($decimals, __METHOD__)) {
			extract($decimals);
		}
		$this->decimals = (int) $decimals;
		$this->decimalPoint = (string) $decimalPoint;
		$this->thousandsSeparator = $thousandsSeparator === null ? self::NBSP : (string) $thousandsSeparator;

		if ($emptyValue !== null) {
			$this->emptyValue = (string) $emptyValue;
		}

		if ($zeroClear) {
			$this->flag |= self::ZERO_CLEAR;
		}

		if ($zeroIsEmpty) {
			$this->flag |= self::ZERO_IS_EMPTY;
			if ($this->emptyValue === null) {
				$this->emptyValue = '';
			}
		}

		if ($intOnly !== null && $intOnly > 0) {
			$this->intOnly = pow(10, (int) $intOnly);
		}
	}


	/**
	 * @return null|string
	 */
	public function getEmptyValue()
	{
		return $this->emptyValue;
	}


	/**
	 * Render number
	 * @param int|float|string|null $number
	 * @return string
	 */
	public function format($number)
	{
		if (((float) $number) === 0.0) {
			if ($this->emptyValue === null) {
				$number = 0;
			} elseif ($this->flag & self::ZERO_IS_EMPTY || !is_numeric($number)) {
				return $this->emptyValue;
			}
		}

		if ($number != 0 && $this->intOnly !== null) {
			$number = ((int) $number) / $this->intOnly;
		}

		$decimals = $this->decimals;
		if ($decimals < 0) {
			$number = round($number, $decimals);
			$decimals = 0;
		}

		$formatted = number_format($number, $decimals, $this->decimalPoint, $this->thousandsSeparator);

		if ($this->flag & self::ZERO_CLEAR && $decimals > 0) {
			return rtrim(rtrim($formatted, '0'), $this->decimalPoint);
		}

		return $formatted;
	}

}
