<?php declare(strict_types = 1);

namespace h4kuna\Format\Number\NativePhp;

use NumberFormatter as PhpNumberFormatter;

class NumberFormatter extends PhpNumberFormatter
{

	public function format(
		float|int $num,
		int $type = 0,
	): string
	{
		return (string) parent::format($num, $type);
	}

}
