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

	/** @var string|NULL */
	private $emptyValue;

	/** @var int */
	private $flag = 0;

	/** @var int|NULL */
	private $intOnly;


	public function __construct($decimals = 2, $decimalPoint = ',', $thousandsSeparator = NULL, $zeroIsEmpty = FALSE, $emptyValue = NULL, $zeroClear = FALSE, $intOnly = NULL)
	{
		if (Utils\Parameters::canExtract($decimals, __METHOD__)) {
			extract($decimals);
		}
		$this->decimals = (int) $decimals;
		$this->decimalPoint = (string) $decimalPoint;
		$this->thousandsSeparator = $thousandsSeparator === NULL ? self::NBSP : (string) $thousandsSeparator;

		if ($emptyValue !== NULL) {
			$this->emptyValue = (string) $emptyValue;
		}

		if ($zeroClear) {
			$this->flag |= self::ZERO_CLEAR;
		}

		if ($zeroIsEmpty) {
			$this->flag |= self::ZERO_IS_EMPTY;
			if ($this->emptyValue === NULL) {
				$this->emptyValue = '';
			}
		}

		if ($intOnly !== NULL && $intOnly > 0) {
			$this->intOnly = pow(10, (int) $intOnly);
		}
	}


	/**
	 * @return NULL|string
	 */
	public function getEmptyValue()
	{
		return $this->emptyValue;
	}


	/**
	 * Render number
	 * @param int|float|string|NULL $number
	 * @return string
	 */
	public function format($number)
	{
		if (((float) $number) === 0.0) {
			if ($this->emptyValue === NULL) {
				$number = 0;
			} elseif ($this->flag & self::ZERO_IS_EMPTY || !is_numeric($number)) {
				return $this->emptyValue;
			}
		}

		if ($number != 0 && $this->intOnly !== NULL) {
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
