<?php declare(strict_types=1);

namespace h4kuna\Number\Units;

class Byte extends Unit
{

	public const UNITS = [
		Unit::BASE => 0,
		Unit::KILO => 3,
		Unit::MEGA => 6,
		Unit::GIGA => 9,
		Unit::TERA => 12,
		Unit::PETA => 15,
	];

	protected function convertUnit(float $number, int $indexFrom, int $indexTo): float
	{
		return $number * pow(1024, ($indexFrom - $indexTo) / 3);
	}

}
