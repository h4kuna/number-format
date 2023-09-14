<?php declare(strict_types=1);

namespace h4kuna\Format\Number;

use h4kuna\Format\Number\Formatters\NumberFormatter;
use h4kuna\Format\Utils;

/**
 * @extends Utils\Formats<NumberFormatter>
 */
class Formats extends Utils\Formats implements FormatsAccessor
{

	public function get(int|string $key): Formatter
	{
		return parent::get($key);
	}


	protected function createDefaultCallback($object = null): callable
	{
		if ($object === null) {
			$object = new NumberFormatter();
		}
		return static function (string|int $key) use ($object): NumberFormatter {
			return $object->modify(unit: (string) $key);
		};
	}
}
