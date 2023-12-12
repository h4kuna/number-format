<?php declare(strict_types=1);

namespace h4kuna\Format\Number;

use h4kuna\Format\Utils;

/**
 * properties will become readonly
 */
final class Discount extends Percentage
{

	public function diff(float $number): float
	{
		return Utils\Percentage::diffDeduct($this->percentage, $number);
	}


	public function deduct(float $number): float
	{
		return Utils\Percentage::deduct($this->percentage, $number);
	}


	public function diffWith(float $number): float
	{
		return Utils\Percentage::diffWith($this->ratio, $number);
	}

}
