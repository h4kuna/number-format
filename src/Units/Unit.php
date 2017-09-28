<?php

namespace h4kuna\Number\Units;

use h4kuna\Number,
	h4kuna\Number\Utils;

class Unit
{

	const
		PETA = 'P',
		TERA = 'T',
		GIGA = 'G',
		MEGA = 'M',
		KILO = 'k',
		BASE = '',
		DECI = 'd',
		CENTI = 'c',
		MILI = 'm',
		MICRO = 'µ',
		NANO = 'n',
		PICO = 'p';

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
		self::PETA => 15
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


	/**
	 * @return array
	 */
	public function getUnits()
	{
		return $this->allowedUnits;
	}


	/**
	 * @return string
	 */
	public function getFrom()
	{
		return $this->from;
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
		if ($unitFrom === null) {
			$unitFrom = $this->from;
		} else {
			$this->checkUnit($unitFrom);
		}

		($unitTo !== null) && $this->checkUnit($unitTo);

		if (!(float) $number) {
			$number = 0;
			$unitTo = $unitFrom;
		} elseif ($unitFrom !== $unitTo && $unitTo !== null) {
			$number = $this->convertUnit($number, $this->allowedUnits[$unitFrom], $this->allowedUnits[$unitTo]);
		} elseif ($unitTo === null) {
			return $this->autoConvert($number, $unitFrom);
		}

		return self::createUnitValue($number, $unitTo);
	}


	/**
	 * @param string $value
	 * @param string $unitTo
	 * @return Utils\UnitValue
	 */
	public function fromString($value, $unitTo = self::BASE)
	{
		if (!preg_match('/^(?P<number>(?:-)?\d*(?:(?:\.)(?:\d*)?)?)(?P<unit>[a-z]+)$/i', self::prepareNumber($value), $find) || !$find['number']) {
			throw new Number\InvalidArgumentException('Bad string, must be numer and unit. Example "128M". Your: ' . $value);
		}
		return $this->convertFrom($find['number'], $find['unit'], $unitTo);
	}


	protected function convertUnit($number, $indexFrom, $indexTo)
	{
		return $number * pow(10, $indexFrom - $indexTo);
	}


	private function autoConvert($number, $unitFrom)
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


	private function checkUnit($unit)
	{
		if (!isset($this->allowedUnits[$unit])) {
			throw new Number\InvalidArgumentException('Unit: "' . $unit . ' let\'s set own.');
		}
	}


	/**
	 * @param float $value
	 * @param string $unit
	 * @return Utils\UnitValue
	 */
	public static function createUnitValue($value, $unit)
	{
		return new Utils\UnitValue([
			'value' => $value,
			'unit' => $unit
		]);
	}


	private static function prepareNumber($value)
	{
		static $replace = [' ' => '', ',' => '.', '+' => ''];
		return strtr($value, $replace);
	}

}

