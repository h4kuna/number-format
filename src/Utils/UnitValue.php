<?php declare(strict_types=1);

namespace h4kuna\Number\Utils;

use h4kuna\DataType\Immutable\Messenger;

/**
 * @property-read float $value
 * @property-read string $unit
 */
final class UnitValue extends Messenger
{

	public function __toString()
	{
		return $this['value'] . ' ' . $this['unit'];
	}

}
