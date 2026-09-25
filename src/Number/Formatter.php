<?php declare(strict_types = 1);

namespace h4kuna\Format\Number;

/**
 * methods format and __invoke keep same parameters and return type
 */
interface Formatter
{

	public function __invoke(string|int|float|null $number): string;

	public function format(string|int|float|null $number): string;

}
