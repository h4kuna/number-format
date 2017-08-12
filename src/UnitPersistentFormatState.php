<?php

namespace h4kuna\Number;

class UnitPersistentFormatState
{
	/** @var UnitFormatState */
	private $unitFormatState;

	/** @var string */
	private $unit;

	public function __construct(UnitFormatState $unitFormatState, $unit)
	{
		$this->unitFormatState = $unitFormatState;
		$this->unit = (string) $unit;
	}

	public function format($number)
	{
		return $this->unitFormatState->format($number, $this->unit);
	}

}