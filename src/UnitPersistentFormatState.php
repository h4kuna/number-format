<?php declare(strict_types=1);

namespace h4kuna\Number;

class UnitPersistentFormatState
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

	public function format($number): string
	{
		return $this->unitFormatState->format($number, $this->unit);
	}

}
