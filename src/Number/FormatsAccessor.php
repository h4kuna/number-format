<?php declare(strict_types = 1);

namespace h4kuna\Format\Number;

interface FormatsAccessor
{

	public function get(string|int $key): Formatter;

}
