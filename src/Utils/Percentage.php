<?php declare(strict_types=1);

namespace h4kuna\Format\Utils;

/**
 * Parameter $percentage is float represent percent, for example 17.5% -> 17.5
 */
final class Percentage
{
	/**
	 * S = 17.5% -> 0.175
	 */
	public static function smallRatio(float $percentage): float
	{
		return $percentage / 100;
	}


	/**
	 * R = 17.5% -> 1.175
	 */
	public static function ratio(float $smallRatio): float
	{
		return $smallRatio + 1.0;
	}


	/**
	 * total: 100
	 * part: 70
	 * result: 70%
	 */
	public static function calculate(float $part, float $total): float
	{
		return self::without($part, $total) * 100;
	}


	/**
	 * total: 100
	 * part: 70
	 * result: 30%
	 */
	public static function calculateRemainder(float $part, float $total): float
	{
		return 100 - self::calculate($part, $total);
	}


	/**
	 * N + 17.5% -> N * R
	 * 17.5% from N -> N * S
	 */
	public static function with(float $number, float $ratio): float
	{
		return $number * $ratio;
	}


	/**
	 * Safe division
	 * N / R
	 */
	public static function without(float $number, float $ratio): float
	{
		if ($ratio === 0.0) {
			return 0.0;
		}
		return $number / $ratio;
	}


	/**
	 * N - (N / R)
	 */
	public static function withoutDiff(float $number, float $ratio): float
	{
		return $number - self::without($number, $ratio);
	}


	/**
	 *  N - (S * N)
	 */
	public static function deduct(float $number, float $smallRatio): float
	{
		return $number - self::with($number, $smallRatio);
	}

}
