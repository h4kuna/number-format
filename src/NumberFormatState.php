<?php declare(strict_types=1);

namespace h4kuna\Number;

class NumberFormatState implements NumberFormat
{
	public const DISABLE_INT_ONLY = -1;

	public const ZERO_CLEAR = 1;
	public const ZERO_IS_EMPTY = 2;

	public const ROUND_DEFAULT = 0;
	public const ROUND_BY_CEIL = 1;
	public const ROUND_BY_FLOOR = 2;

	/** @var string utf-8 &nbsp; */
	public const NBSP = "\xc2\xa0";

	/** @var string */
	private $thousandsSeparator;

	/** @var int */
	private $precision = 0;

	/** @var string */
	private $decimalPoint;

	/** @var string|null */
	private $emptyValue;

	/** @var int */
	private $flag = 0;

	/** @var int */
	private $intOnly = 0;

	/** @var \Closure */
	private $roundCallback;


	/**
	 * @param array<string, bool|int|string|null>|int $decimals
	 */
	public function __construct(
		$decimals = 2,
		string $decimalPoint = ',',
		?string $thousandsSeparator = null,
		bool $zeroIsEmpty = false,
		?string $emptyValue = null,
		bool $zeroClear = false,
		int $intOnly = self::DISABLE_INT_ONLY,
		int $round = self::ROUND_DEFAULT
	)
	{
		if (Utils\Parameters::canExtract($decimals, __METHOD__)) {
			extract($decimals);
		}
		$this->precision = $decimals;
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

		if ($intOnly > self::DISABLE_INT_ONLY) {
			$this->intOnly = 10 ** $intOnly;
		}

		$this->roundCallback = self::createRound($round, $decimals);
	}


	private static function createRound(int $round, int $precision): \Closure
	{
		if ($round === self::ROUND_BY_FLOOR) {
			return static function (float $number) use ($precision): float {
				$move = 10 ** $precision;
				return floor($number * $move) / $move;
			};
		} elseif ($round === self::ROUND_BY_CEIL) {
			return static function (float $number) use ($precision): float {
				$move = 10 ** $precision;
				return ceil($number * $move) / $move;
			};
		}
		return static function (float $number) use ($precision): float {
			return round($number, $precision);
		};
	}


	public function getEmptyValue(): ?string
	{
		return $this->emptyValue;
	}


	public function format($number, string $unit = ''): string
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

		$cb = $this->roundCallback;
		$formatted = number_format($cb((float) $number), max(0, $this->precision), $this->decimalPoint, $this->thousandsSeparator);

		if ($this->flag & self::ZERO_CLEAR && $this->precision > 0) {
			return rtrim(rtrim($formatted, '0'), $this->decimalPoint);
		}

		return $formatted;
	}

}
