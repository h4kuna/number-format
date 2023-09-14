<?php declare(strict_types=1);

namespace h4kuna\Format\Date;

use h4kuna\Format\Date\Formatters\DateTimeFormatter;
use h4kuna\Format\Utils;

/**
 * @extends Utils\Formats<Formatter>
 */
class Formats extends Utils\Formats implements FormatsAccessor
{

	public function get(int|string $key): Formatter
	{
		return parent::get($key);
	}


	protected function createDefaultCallback($object = null): callable
	{
		return static fn (): DateTimeFormatter => new DateTimeFormatter('Y-m-d H:i:s');
	}

}
