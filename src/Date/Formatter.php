<?php declare(strict_types=1);

namespace h4kuna\Format\Date;

use DateTimeInterface;

/**
 * methods format and __invoke keep same parameters and return type
 */
interface Formatter
{
	function format(?DateTimeInterface $dateTime): string;


	function __invoke(?DateTimeInterface $dateTime): string;
}
