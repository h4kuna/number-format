<?php declare(strict_types=1);

namespace h4kuna\Number\Units;

use h4kuna\Number;
use h4kuna\Number\Utils;

class Unit
{

	const PETA = 'P';
	const TERA = 'T';
	const GIGA = 'G';
	const MEGA = 'M';
	const KILO = 'k';
	const BASE = '';
	const DECI = 'd';
	const CENTI = 'c';
	const MILI = 'm';
	const MICRO = 'µ';
	const NANO = 'n';
	const PICO = 'p';

	const UNITS = [
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

	/** @var string */
	private $from;

	/**
	 * These values must be sort ascending! See self::UNITS
	 * @var array
	 */
	protected $allowedUnits;

	public function __construct($from = self::BASE, array $allowedUnits = null)
	{
		$this->from = $from;
		if ($allowedUnits === null) {
			$this->allowedUnits = static::UNITS;
		}
	}

	public function getUnits(): array
	{
		return $this->allowedUnits;
	}

	public function getFrom(): string
	{
		return $this->from;
	}


	/**
	 * @param float $number
	 * @param string|NULL $unitTo - NULL mean automatic
	 * @return Utils\UnitValue
	 */
	public function convert(float $number, ?string $unitTo = null): Utils\UnitValue
	{
		return $this->convertFrom($number, null, $unitTo);
	}


	/**
	 * @param float $number
	 * @param string|null $unitFrom - NULL mean defined in constructor
	 * @param string|null $unitTo - NULL mean automatic
	 * @return Utils\UnitValue
	 */
	public function convertFrom(float $number, ?string $unitFrom, ?string $unitTo = null): Utils\UnitValue
	{
		if ($unitFrom === null) {
			$unitFrom = $this->from;
		} else {
			$this->checkUnit($unitFrom);
		}

		($unitTo !== null) && $this->checkUnit($unitTo);

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

	public function fromString(string $value, string $unitTo = self::BASE): Utils\UnitValue
	{
		if (!preg_match('/^(?P<number>(?:-)?\d*(?:(?:\.)(?:\d*)?)?)(?P<unit>[a-z]+)$/i', self::prepareNumber($value), $find) || !$find['number']) {
			throw new Number\InvalidArgumentException('Bad string, must be number and unit. Example "128M". Your: ' . $value);
		}
		return $this->convertFrom((float) $find['number'], $find['unit'], $unitTo);
	}

	protected function convertUnit(float $number, int $indexFrom, int $indexTo): float
	{
		return $number * pow(10, $indexFrom - $indexTo);
	}

	private function autoConvert(float $number, string $unitFrom): Utils\UnitValue
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

	private function checkUnit(string $unit): void
	{
		if (!isset($this->allowedUnits[$unit])) {
			throw new Number\InvalidArgumentException('Unit: "' . $unit . ' let\'s set own.');
		}
	}

	public static function createUnitValue(float $value, string $unit): Utils\UnitValue
	{
		return new Utils\UnitValue([
			'value' => $value,
			'unit' => $unit,
		]);
	}

	private static function prepareNumber(string $value): string
	{
		static $replace = [' ' => '', ',' => '.', '+' => ''];
		return strtr($value, $replace);
	}

}

