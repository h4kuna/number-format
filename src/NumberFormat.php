<?php declare(strict_types=1);

namespace h4kuna\Number;

use h4kuna\Number\Exceptions\InvalidStateException;

class NumberFormat
{
	public const DISABLE_INT_ONLY = -1;

	public const ZERO_CLEAR = 1;
	public const ZERO_IS_EMPTY = 2;

	public const ROUND_DEFAULT = 0;
	public const ROUND_BY_CEIL = 1;
	public const ROUND_BY_FLOOR = 2;

	/** @var string utf-8 &nbsp; */
	public const NBSP = "\xc2\xa0";

	private string $thousandsSeparator;

	private int $decimals;

	private string $decimalPoint;

	private ?string $emptyValue = null;

	private int $flag = 0;

	private int $intOnly = 0;

	private \Closure $roundCallback;

	private ?string $mask = null;

	private bool $showUnit = true;

	private bool $nbsp;

	/** @var array{from: array<string>, to: array<string>} */
	private array $replace = [
		'from' => ['1', '⎵'],
		'to' => ['', ''],
	];


	/**
	 * @param array<string, bool|int|string|null>|int $decimals
	 */
	public function __construct(
		$decimals = 2,
		string $decimalPoint = ',',
		string $thousandsSeparator = ' ',
		bool $nbsp = true,
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
		$this->decimals = $decimals;
		$this->decimalPoint = $decimalPoint;
		$this->thousandsSeparator = $thousandsSeparator;
		$this->nbsp = $nbsp;

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

		$this->roundCallback = self::createRound($round);
	}


	/**
	 * @param string $mask - Mask must contains number 1 and unit (€, kg, km) or letter upper U for dynamic unit for active third parameter in method format().
	 */
	public function enableExtendFormat(
		string $mask = '1 ⎵',
		bool $showUnit = true
	): void
	{
		if ($this->mask !== null) {
			throw new InvalidStateException('Only onetime is allowed setup.');
		}
		$this->mask = $mask;
		$this->showUnit = $showUnit;

		if ($this->nbsp) {
			$this->replace['from'][] = ' ';
			$this->replace['to'][] = self::NBSP;
		}
	}


	/**
	 * @param string|int|float|null $number
	 */
	public function format($number, ?int $decimals = null, ?string $unit = null): string
	{
		if (((float) $number) === 0.0) {
			if ($this->emptyValue === null) {
				$number = 0.0;
			} elseif ($this->flag & self::ZERO_IS_EMPTY || !is_numeric($number)) {
				return $this->stringFormat($this->emptyValue, $unit);
			}
		} elseif ($this->intOnly !== 0) {
			$number = intval($number) / $this->intOnly;
		}

		$decimals ??= $this->decimals;
		$formatted = number_format(($this->roundCallback)((float) $number, $decimals), max(0, $decimals), $this->decimalPoint, $this->thousandsSeparator);

		if ($this->flag & self::ZERO_CLEAR && $decimals > 0) {
			$formatted = rtrim(rtrim($formatted, '0'), $this->decimalPoint);
		}

		return $this->stringFormat($formatted, $unit);
	}


	private static function createRound(int $round): \Closure
	{
		if ($round === self::ROUND_BY_FLOOR) {
			return static function (float $number, int $precision): float {
				$move = 10 ** $precision;

				return floor($number * $move) / $move;
			};
		} elseif ($round === self::ROUND_BY_CEIL) {
			return static function (float $number, int $precision): float {
				$move = 10 ** $precision;

				return ceil($number * $move) / $move;
			};
		}

		return static function (float $number, int $precision): float {
			return round($number, $precision);
		};
	}


	private function stringFormat(string $formatted, ?string $unit = null): string
	{
		if ($this->mask === null || ($this->showUnit === false && $this->emptyValue === $formatted || $unit === '')) {
			return $this->replaceNbsp($formatted);
		}

		$this->replace['to'][0] = $formatted;
		$this->replace['to'][1] = $unit;

		return str_replace($this->replace['from'], $this->replace['to'], $this->mask);
	}


	private function replaceNbsp(string $formatted): string
	{
		return $this->nbsp === true ? str_replace(' ', self::NBSP, $formatted) : $formatted;
	}

}
