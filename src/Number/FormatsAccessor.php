<?php declare(strict_types=1);

namespace h4kuna\Format\Number;

interface FormatsAccessor
{
	function get(string|int $key): Formatter;
}
