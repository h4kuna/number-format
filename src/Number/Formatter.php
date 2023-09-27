<?php declare(strict_types=1);

namespace h4kuna\Format\Number;

interface Formatter
{
	function format(string|int|float|null $number): string;
}
