<?php declare(strict_types=1);

namespace h4kuna\Format\Number\NativePhp;

class NumberFormatter extends \NumberFormatter
{
	public function format(float|int $num, int $type = 0): string
	{
		return (string) parent::format($num, $type);
	}
}
