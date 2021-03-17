<?php declare(strict_types=1);

namespace h4kuna\Number;

interface NumberFormat
{

	/**
	 * @param float|int|string|null $number
	 */
	function format($number, string $unit = ''): string;

}
