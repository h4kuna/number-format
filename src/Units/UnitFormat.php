<?php declare(strict_types=1);

namespace h4kuna\Number\Units;

use h4kuna\Number;

class UnitFormat
{

	/**
	 * @var array<string, Number\NumberFormat>
	 */
	private array $formats = [];


	public function __construct(
		private string $symbol,
		private Unit $unit,
		private Number\NumberFormat $numberFormat,
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


	public function fromString(string $value, string $unitTo = Unit::BASE): string
	{
		$unitValue = $this->unit->fromString($value, $unitTo);

		return $this->format($unitValue->value, $unitValue->unit);
	}


	private function format(float $value, string $unit): string
	{
		if (isset($this->formats[$unit]) === false) {
			$this->formats[$unit] = $this->numberFormat->modify(unit: $unit);
		}

		return $this->formats[$unit]->format($value);
	}

}
