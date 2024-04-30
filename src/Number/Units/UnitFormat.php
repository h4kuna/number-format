<?php declare(strict_types=1);

namespace h4kuna\Format\Number\Units;

use h4kuna\Format;

class UnitFormat
{
	private Format\Number\Formats $formats;


	public function __construct(
		private string $symbol,
		private Unit $unit,
		Format\Number\Formats|Format\Number\Formatter|null $formats = null,
	)
	{
		if ($formats === null) {
			$formats = self::createFormats();
		} elseif ($formats instanceof Format\Number\Formatter) {
			$formatter = $formats;
			$formats = self::createFormats();
			$formats->setDefault($formatter); // @phpstan-ignore-line
		}
		$this->formats = $formats;
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


	private static function createFormats(): Format\Number\Formats
	{
		return new Format\Number\Formats();
	}

}
