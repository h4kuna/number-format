<?php declare(strict_types=1);

namespace h4kuna\Format\Number;

/**
 * @phpstan-import-type TRoundCallback from NumberFormat
 */
final class Round
{
	public const RESET = -1;
	public const DEFAULT = 0;
	public const BY_CEIL = 1;

	public const BY_FLOOR = 2;


	/**
	 * @return TRoundCallback
	 */
	public static function create(int $round = self::DEFAULT): callable
	{
		return match ($round) {
			// self::ceil(...)
			self::BY_CEIL => static fn (
				float $number,
				int $precision
			): float => self::ceil($number, $precision),
			self::BY_FLOOR => static fn (
				float $number,
				int $precision
			): float => self::floor($number, $precision),
			default => static fn (float $number, int $precision): float => self::standard($number, $precision),
		};
	}


	private static function ceil(float $number, int $precision): float
	{
		$move = 10 ** $precision;

		return ceil($number * $move) / $move;
	}


	private static function floor(float $number, int $precision): float
	{
		$move = 10 ** $precision;

		return floor($number * $move) / $move;
	}


	private static function standard(float $number, int $precision): float
	{
		return round($number, $precision);
	}
}
