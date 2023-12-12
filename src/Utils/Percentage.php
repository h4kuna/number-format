<?php declare(strict_types=1);

namespace h4kuna\Format\Utils;

/**
 * Parameter $percentage is float represent percent, for example 17.5% -> 17.5
 */
final class Percentage
{
	/**
	 * 17.5% -> 0.175
	 */
	public static function smallRatio(float $percentage): float
	{
		return $percentage / 100;
	}


	/**
	 * 17.5% -> 1.175
	 */
	public static function ratio(float $smallRatio): float
	{
		return $smallRatio + 1.0;
	}


	/**
	 * N + 17.5% -> N * 1.175
	 */
	public static function with(float $ratio, float $number): float
	{
		return $number * $ratio;
	}


	/**
	 * N * 1.175 - N
	 */
	public static function diffWith(float $ratio, float $number): float
	{
		return self::with($ratio, $number) - $number;
	}


	/**
	 * If you have price with percentage, and you need price without percentage.
	 * N / 1.175
	 */
	public static function without(float $ratio, float $number): float
	{
		return $number / $ratio;
	}


	/**
	 * N - (N / 1.175)
	 */
	public static function diffWithout(float $ratio, float $number): float
	{
		return $number - self::without($ratio, $number);
	}


	/**
	 *  N - ((N * 17.5) / 100)
	 */
	public static function deduct(float $percentage, float $number): float
	{
		return $number - self::diffDeduct($percentage, $number);
	}


	/**
	 * (N * 17.5) / 100
	 */
	public static function diffDeduct(float $percentage, float $number): float
	{
		return self::smallRatio($number * $percentage);
	}
}
