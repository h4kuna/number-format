<?php declare(strict_types=1);

namespace h4kuna\Format\Number\Units;

use h4kuna\Format;

class UnitFormat
{
	public function __construct(
		private string $symbol,
		private Unit $unit,
		private Format\Number\Formats $formats,
	)
	{
	}


	public function convert(float $number, ?string $unitTo = null): string
	{
		return $this->convertFrom($number, null, $unitTo);
	}


	/**
	 * @param string|null $unitFrom - null mean defined in constructor
	 * @param string|null $unitTo - null mean automatic
	 */
	public function convertFrom(float $number, ?string $unitFrom, ?string $unitTo = null): string
	{
		$unitValue = $this->unit->convertFrom($number, $unitFrom, $unitTo);

		return $this->format($unitValue->value, $unitValue->unit . $this->symbol);
	}


	private function format(float $value, string $unit): string
	{
		return $this->formats->get($unit)->format($value);
	}


	public function fromString(string $value, string $unitTo = Unit::BASE): string
	{
		$unitValue = $this->unit->fromString($value, $unitTo);

		return $this->format($unitValue->value, $unitValue->unit);
	}

}
