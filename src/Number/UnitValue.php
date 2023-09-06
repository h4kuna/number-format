<?php declare(strict_types=1);

namespace h4kuna\Format\Number;

use Stringable;

/**
 * @internal
 */
final class UnitValue implements Stringable
{

	public function __construct(public /* readonly */ float $value, public /* readonly */ string $unit)
	{
	}


	public function __toString()
	{
		return $this->value . ' ' . $this->unit;
	}

}
