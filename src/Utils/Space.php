<?php declare(strict_types=1);

namespace h4kuna\Format\Utils;

final class Space
{
	/**
	 * @var string utf-8 &nbsp;
	 */
	public const NBSP = "\xc2\xa0";

	public const AS_NULL = "\x00";


	public static function nbsp(string $value): string
	{
		return strtr($value, [' ' => self::NBSP]);
	}


	public static function white(string $value): string
	{
		return strtr($value, [self::NBSP => ' ']);
	}

}
