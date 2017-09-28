<?php

namespace h4kuna\Number\Units;

use h4kuna\Number,
	h4kuna\Number\Utils;

class UnitFormat
{

	/** @var string */
	private $symbol;

	/** @var Unit */
	private $unit;

	/** @var Number\UnitFormatState */
	private $unitFormatState;


	public function __construct($symbol, Unit $unit, Number\UnitFormatState $unitFormatState)
	{
		$this->symbol = $symbol;
		$this->unit = $unit;
		$this->unitFormatState = $unitFormatState;
	}


	/**
	 * @param int|float|string $number
	 * @param string|null $unitTo - null mean automatic
	 * @return Utils\UnitValue
	 */
	public function convert($number, $unitTo = null)
	{
		return $this->convertFrom($number, null, $unitTo);
	}


	/**
	 * @param int|float $number
	 * @param string $unitFrom - null mean defined in constructor
	 * @param string|null $unitTo - null mean automatic
	 * @return Utils\UnitValue
	 */
	public function convertFrom($number, $unitFrom, $unitTo = null)
	{
		$unitValue = $this->unit->convertFrom($number, $unitFrom, $unitTo);
		return $this->format($unitValue->value, $unitValue->unit . $this->symbol);
	}


	/**
	 * @param string $value
	 * @param string $unitTo
	 * @return Utils\UnitValue
	 */
	public function fromString($value, $unitTo = Unit::BASE)
	{
		$unitValue = $this->unit->fromString($value, $unitTo);
		return $this->format($unitValue->value, $unitValue->unit . $this->unit);
	}


	private function format($value, $unit)
	{
		return $this->unitFormatState->format($value, $unit);
	}

}