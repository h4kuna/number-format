<?php declare(strict_types=1);

namespace h4kuna\Format\Number;

use h4kuna\Format\Number\Parameters\ZeroClear;
use h4kuna\Format\Utils\Space;

final class NumberFormat
{

	/**
	 * @param callable(float, int): float|null $roundCallback
	 */
	public static function unit(
		string|int|float|null $number,
		int $decimals = 2,
		string $decimalPoint = ',',
		string $thousandsSeparator = ' ',
		bool $nbsp = true,
		string $emptyValue = Space::AS_NULL, // must be a string, for immutable behavior of NumberFormat
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
			$formatted = $emptyValue === Space::AS_NULL ? '' : $emptyValue;
		} else {
			$castNumber = ($roundCallback ?? Round::create())($castNumber, $decimals);
			if (($zeroClear === ZeroClear::DECIMALS_EMPTY && $decimals > 0 && ((int) $castNumber) == $castNumber) || $decimals < 0) {
				$decimals = 0;
			}

			$formatted = self::base($castNumber, $decimals, $decimalPoint, $thousandsSeparator);

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
			return $nbsp === true
				? Space::nbsp($formattedNumber)
				: $formattedNumber;
		}

		return $nbsp === true ?
			str_replace(['1', ' '], [$formattedNumber, Space::NBSP], $mask) :
			str_replace('1', $formattedNumber, $mask);
	}

}
