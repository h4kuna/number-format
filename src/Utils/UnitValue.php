<?php declare(strict_types=1);

namespace h4kuna\Number\Utils;

final class UnitValue
{
	public /* readonly */ float $value;

	public /* readonly */ string $unit;


	public function __construct(float $value, string $unit)
	{
		$this->value = $value;
		$this->unit = $unit;
	}


	public function __toString()
	{
		return $this->value . ' ' . $this->unit;
	}

}
