<?php declare(strict_types=1);

namespace h4kuna\Format\Date;

use DateTimeInterface;

interface Formatter
{
	function format(?DateTimeInterface $dateTime): string;
}
