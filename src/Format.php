<?php declare(strict_types=1);

namespace h4kuna\Number;

use h4kuna\Number\Parameters\Format\ZeroClear;
use h4kuna\Number\Utils\Round;

final class Format
{
	/**
	 * @var string utf-8 &nbsp;
	 */
	public const NBSP = "\xc2\xa0";

	public const AS_NULL = "\x00";


	/**
	 * @param callable(float, int): float|null $roundCallback
	 */
	public static function unit(
		string|int|float|null $number,
		int $decimals = 2,
		string $decimalPoint = ',',
		string $thousandsSeparator = ' ',
		bool $nbsp = true,
		string $emptyValue = self::AS_NULL, // must be a string, for immutable behavior of NumberFormat
		bool $zeroIsEmpty = false,
		int $zeroClear = ZeroClear::NO,
		string $mask = '',
		bool $showUnitIfEmpty = true,
		?callable $roundCallback = null
	): string
	{
		$isNumeric = is_numeric($number);
		$castNumber = $isNumeric ? (float) $number : 0.0;
		$isZero = $castNumber === 0.0;

		if ($isZero && ($isNumeric === false || $zeroIsEmpty === true)) {
			$formatted = $emptyValue === self::AS_NULL ? '' : $emptyValue;
		} else {
			if ($zeroClear === ZeroClear::DECIMALS_EMPTY && $decimals > 0 && ((int) $castNumber) == $castNumber) {
				$decimals = 0;
			}

			$formatted = self::base($castNumber, $decimals, $decimalPoint, $thousandsSeparator, $roundCallback);

			if ($zeroClear === ZeroClear::DECIMALS && $decimals > 0) {
				$formatted = self::zeroClear($formatted, $decimalPoint);
			}
		}

		return self::replace(
			$nbsp,
			$formatted,
			$isZero && $showUnitIfEmpty === false ? '' : $mask
		);
	}


	/**
	 * @param callable(float, int): float|null $roundCallback
	 */
	public static function base(
		float $number,
		int $decimals = 2,
		string $decimalPoint = ',',
		string $thousandsSeparator = ' ',
		?callable $roundCallback = null
	): string
	{
		$round = $decimals;
		if ($decimals < 0) {
			$decimals = 0;
			$roundCallback ??= Round::create();
		}

		if ($roundCallback !== null) {
			$number = ($roundCallback)($number, $round);
		}

		return number_format($number, $decimals, $decimalPoint, $thousandsSeparator);
	}


	private static function zeroClear(string $formattedNumber, string $decimalPoint): string
	{
		return rtrim(rtrim($formattedNumber, '0'), $decimalPoint);
	}


	private static function replace(bool $nbsp, string $formattedNumber, string $mask): string
	{
		if ($mask === '') {
			return $nbsp === true ?
				str_replace(' ', self::NBSP, $formattedNumber) :
				$formattedNumber;
		}

		return $nbsp === true ?
			str_replace(['1', ' '], [$formattedNumber, self::NBSP], $mask) :
			str_replace('1', $formattedNumber, $mask);
	}

}
