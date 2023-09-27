<?php declare(strict_types=1);

namespace h4kuna\Format\Date;

use Closure;
use h4kuna\DataType\Collection\LazyBuilder;
use h4kuna\Format\Date\Formatters\DateTimeFormatter;

/**
 * @extends LazyBuilder<Formatter>
 */
class Formats extends LazyBuilder implements FormatsAccessor
{

	public function get(int|string $key): Formatter
	{
		return parent::get($key);
	}


	protected function createDefaultCallback($object = null): Closure
	{
		return static fn (): DateTimeFormatter => new DateTimeFormatter('Y-m-d H:i:s');
	}

}
