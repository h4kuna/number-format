<?php declare(strict_types=1);

namespace h4kuna\Number\Units;

use h4kuna\Number;

class UnitFormat
{

	/** @var string */
	private $symbol;

	/** @var Unit */
	private $unit;

	/** @var Number\UnitFormatState */
	private $unitFormatState;

	public function __construct(string $symbol, Unit $unit, Number\UnitFormatState $unitFormatState)
	{
		$this->symbol = $symbol;
		$this->unit = $unit;
		$this->unitFormatState = $unitFormatState;
	}

	public function convert(float $number, ?string $unitTo = null): string
	{
		return $this->convertFrom($number, null, $unitTo);
	}


	/**
	 * @param float $number
	 * @param string|null $unitFrom - NULL mean defined in constructor
	 * @param string|null $unitTo - NULL mean automatic
	 * @return string
	 */
	public function convertFrom(float $number, ?string $unitFrom, ?string $unitTo = null): string
	{
		$unitValue = $this->unit->convertFrom($number, $unitFrom, $unitTo);
		return $this->format($unitValue->value, $unitValue->unit . $this->symbol);
	}

	public function fromString(string $value, string $unitTo = Unit::BASE): string
	{
		$unitValue = $this->unit->fromString($value, $unitTo);
		return $this->format($unitValue->value, $unitValue->unit);
	}

	private function format(float $value, string $unit): string
	{
		return $this->unitFormatState->format($value, $unit);
	}

}