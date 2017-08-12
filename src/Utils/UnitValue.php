<?php

namespace h4kuna\Number\Utils;

/**
 * @property-read float $value
 * @property-read string $unit
 */
final class UnitValue extends Crate
{
	public function __toString()
	{
		return $this['value'] . ' ' . $this['unit'];
	}
}
