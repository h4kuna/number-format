<?php declare(strict_types=1);

namespace h4kuna\Format\Number\Units;

use h4kuna\Format;

final class ByteFormat extends UnitFormat
{
	public function __construct(Format\Number\Formats|Format\Number\Formatter|null $formats = null)
	{
		parent::__construct('B', new Byte(), $formats);
	}
}
