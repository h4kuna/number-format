<?php declare(strict_types = 1);

namespace h4kuna\Format\Number\Units;

use h4kuna\Format\Number\Formats;
use h4kuna\Format\Number\Formatter;

final class ByteFormat extends UnitFormat
{

	public function __construct(Formats|Formatter|null $formats = null)
	{
		parent::__construct('B', new Byte(), $formats);
	}

}
