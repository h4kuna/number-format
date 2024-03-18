<?php declare(strict_types=1);

namespace h4kuna\Format\Number;

/**
 * methods format and __invoke keep same parameters and return type
 */
interface Formatter
{
	function format(string|int|float|null $number): string;


	function __invoke(string|int|float|null $number): string;
}
