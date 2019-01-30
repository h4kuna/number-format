<?php declare(strict_types=1);

namespace h4kuna\Number;

class NumberFormatState
{

	const ZERO_CLEAR = 1;
	const ZERO_IS_EMPTY = 2;

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

	/** @var int */
	private $intOnly = 0;

	public function __construct($decimals = 2, string $decimalPoint = ',', ?string $thousandsSeparator = null, bool $zeroIsEmpty = false, ?string $emptyValue = null, bool $zeroClear = false, int $intOnly = 0)
	{
		if (Utils\Parameters::canExtract($decimals, __METHOD__)) {
			extract($decimals);
		}
		$this->decimals = $decimals;
		$this->decimalPoint = $decimalPoint;
		$this->thousandsSeparator = $thousandsSeparator === null ? self::NBSP : $thousandsSeparator;

		if ($emptyValue !== null) {
			$this->emptyValue = $emptyValue;
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

		if ($intOnly > 0) {
			$this->intOnly = pow(10, $intOnly);
		}
	}


	public function getEmptyValue(): ?string
	{
		return $this->emptyValue;
	}


	/**
	 * Render number
	 * @param int|float|string|null $number
	 * @return string
	 */
	public function format($number): string
	{
		if (((float) $number) === 0.0) {
			if ($this->emptyValue === null) {
				$number = 0.0;
			} elseif ($this->flag & self::ZERO_IS_EMPTY || !is_numeric($number)) {
				return $this->emptyValue;
			}
		}

		if ($number != 0 && $this->intOnly !== 0) {
			$number = $number / $this->intOnly;
		}

		$decimals = $this->decimals;
		if ($decimals < 0) {
			$number = round($number, $decimals);
			$decimals = 0;
		}

		$formatted = number_format((float) $number, $decimals, $this->decimalPoint, $this->thousandsSeparator);

		if ($this->flag & self::ZERO_CLEAR && $decimals > 0) {
			return rtrim(rtrim($formatted, '0'), $this->decimalPoint);
		}

		return $formatted;
	}

}
