<?php declare(strict_types = 1);

namespace h4kuna\Format\Date;

use DateTimeInterface;

/**
 * methods format and __invoke keep same parameters and return type
 */
interface Formatter
{

	public function __invoke(?DateTimeInterface $dateTime): string;

	public function format(?DateTimeInterface $dateTime): string;

}
