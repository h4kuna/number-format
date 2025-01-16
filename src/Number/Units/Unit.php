<?php declare(strict_types=1);

namespace h4kuna\Format\Number\Units;

use h4kuna\Format;

class Unit
{
	/** @var array<string, string> */
	private const REPLACE = [' ' => '', ',' => '.', '+' => ''];

	/** @var string */
	public const PETA = 'P';
	/** @var string */
	public const TERA = 'T';
	/** @var string */
	public const GIGA = 'G';
	/** @var string */
	public const MEGA = 'M';
	/** @var string */
	public const KILO = 'k';
	/** @var string */
	public const BASE = '';
	/** @var string */
	public const DECI = 'd';
	/** @var string */
	public const CENTI = 'c';
	/** @var string */
	public const MILI = 'm';
	/** @var string */
	public const MICRO = 'µ';
	/** @var string */
	public const NANO = 'n';
	/** @var string */
	public const PICO = 'p';

	/** @var non-empty-array<string, int> */
	public const UNITS = [
		self::PICO => -12,
		self::NANO => -9,
		self::MICRO => -6,
		self::MILI => -3,
		self::CENTI => -2,
		self::DECI => -1,
		self::BASE => 0,
		self::KILO => 3,
		self::MEGA => 6,
		self::GIGA => 9,
		self::TERA => 12,
		self::PETA => 15,
	];

	/**
	 * These values must be sort ascending! See self::UNITS
	 * @var non-empty-array<string, int>
	 */
	protected array $allowedUnits;


	/**
	 * @param array<string, int> $allowedUnits
	 */
	public function __construct(private string $from = self::BASE, array $allowedUnits = [])
	{
		if ($allowedUnits === []) {
			$this->allowedUnits = static::UNITS;
		}
		$this->checkUnit($this->from);
	}


	private function checkUnit(string $unit): void
	{
		if (!isset($this->allowedUnits[$unit])) {
			throw new Format\Exceptions\InvalidArgumentException(sprintf('Unit: "%s let\'s set own.', $unit));
		}
	}


	/**
	 * @return array<string, int>
	 */
	public function getUnits(): array
	{
		return $this->allowedUnits;
	}


	public function getFrom(): string
	{
		return $this->from;
	}


	public function convert(float $number, ?string $unitTo = null): Format\Number\UnitValue
	{
		return $this->convertFrom($number, null, $unitTo);
	}


	/**
	 * @param string|null $unitFrom - NULL mean defined in constructor
	 * @param string|null $unitTo - NULL mean automatic
	 */
	public function convertFrom(float $number, ?string $unitFrom, ?string $unitTo = null): Format\Number\UnitValue
	{
		if ($unitFrom === null) {
			$unitFrom = $this->from;
		} else {
			$this->checkUnit($unitFrom);
		}

		if ($unitTo !== null) {
			$this->checkUnit($unitTo);
		}

		if ($number === 0.0) {
			$number = 0;
			$unitTo = $unitFrom;
		} elseif ($unitFrom !== $unitTo && $unitTo !== null) {
			$number = $this->convertUnit($number, $this->allowedUnits[$unitFrom], $this->allowedUnits[$unitTo]);
		} elseif ($unitTo === null) {
			return $this->autoConvert($number, $unitFrom);
		}

		return self::createUnitValue($number, $unitTo);
	}


	protected function convertUnit(float $number, int $indexFrom, int $indexTo): float
	{
		return $number * pow(10, $indexFrom - $indexTo);
	}


	private function autoConvert(float $number, string $unitFrom): Format\Number\UnitValue
	{
		$result = [];
		foreach ($this->allowedUnits as $unit => $index) {
			if ($this->allowedUnits[$unitFrom] === $index) {
				$temp = $number;
			} else {
				$temp = $this->convertUnit($number, $this->allowedUnits[$unitFrom], $index);
			}

			if ($temp < 1.0) {
				if ($result === []) {
					$result = [$temp, $unit];
				}
				break;
			}
			$result = [$temp, $unit];
		}
		return self::createUnitValue($result[0], $result[1]);
	}


	private static function createUnitValue(float $value, string $unit): Format\Number\UnitValue
	{
		return new Format\Number\UnitValue(
			$value,
			$unit
		);
	}


	public function fromString(string $value, string $unitTo = self::BASE): Format\Number\UnitValue
	{
		$result = preg_match('/^(?P<number>-?\d*(?:\.(?:\d*)?)?)(?P<unit>[a-z]+)$/i', self::prepareNumber($value), $find);
		if ($result === false || isset($find['number']) === false || $find['number'] === '') {
			throw new Format\Exceptions\InvalidArgumentException('Bad string, must be number and unit. Example "128M". Your: ' . $value);
		}
		return $this->convertFrom((float) $find['number'], $find['unit'] ?? null, $unitTo);
	}


	private static function prepareNumber(string $value): string
	{
		return strtr($value, self::REPLACE);
	}

}
