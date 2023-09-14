<?php declare(strict_types=1);

namespace h4kuna\Format\Date;

use DateTimeInterface;
use h4kuna\Format\Utils\Service;

interface Formatter extends Service
{
	function format(?DateTimeInterface $dateTime): string;
}
