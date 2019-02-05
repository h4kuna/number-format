<?php declare(strict_types=1);

namespace h4kuna\Number;

class UnitPersistentFormatState implements NumberFormat
{

	/** @var UnitFormatState */
	private $unitFormatState;

	/** @var string */
	private $unit;


	public function __construct(UnitFormatState $unitFormatState, string $unit)
	{
		$this->unitFormatState = $unitFormatState;
		$this->unit = $unit;
	}


	public function format($number, string $unit = ''): string
	{
		return $this->unitFormatState->format($number, $this->unit);
	}

}
