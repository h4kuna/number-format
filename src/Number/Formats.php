<?php declare(strict_types=1);

namespace h4kuna\Format\Number;

use Closure;
use h4kuna\DataType\Collection\LazyBuilder;
use h4kuna\Format\Number\Formatters\NumberFormatter;

/**
 * @extends LazyBuilder<NumberFormatter>
 */
class Formats extends LazyBuilder implements FormatsAccessor
{

	public function get(int|string $key): Formatter
	{
		return parent::get($key);
	}


	protected function createDefaultCallback($object = null): Closure
	{
		if ($object === null) {
			$object = new NumberFormatter();
		}
		return static function (string|int $key) use ($object): NumberFormatter {
			return $object->modify(unit: (string) $key);
		};
	}
}
