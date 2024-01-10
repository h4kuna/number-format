<?php declare(strict_types=1);

namespace h4kuna\Format\Number;

use h4kuna\Format\Utils;

/**
 * properties will become readonly
 */
class Vat extends Percentage
{

	public function diff(float $number): float
	{
		return Utils\Percentage::diffWithout($this->ratio, $number);
	}


	public function without(float $number): float
	{
		return Utils\Percentage::without($number, $this->ratio);
	}

}
