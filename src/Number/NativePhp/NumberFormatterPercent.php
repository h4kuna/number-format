<?php declare(strict_types=1);

namespace h4kuna\Format\Number\NativePhp;

use h4kuna\Format\Utils\Percentage;

final class NumberFormatterPercent extends NumberFormatter
{
	public function __construct(string $locale)
	{
		parent::__construct($locale, self::PERCENT);
	}


	public function format(float|int $num, int $type = 0): string
	{
		return parent::format(Percentage::smallRatio((float) $num), $type);
	}

}
