<?php declare(strict_types=1);

namespace h4kuna\Format\Number;

use h4kuna\Format\Utils\Service;

interface Formatter extends Service
{
	function format(string|int|float|null $number): string;
}
