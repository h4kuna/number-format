<?php declare(strict_types=1);

namespace h4kuna\Number\Utils;

use h4kuna\Number\Exceptions\InvalidStateException;
use h4kuna\Number\NumberFormat;

/**
 * @phpstan-type constructorNumberFormat array{decimals?: int, decimalPoint?: string, thousandsSeparator?: string, nbsp?: bool, zeroClear?: int, emptyValue?: string, zeroIsEmpty?: bool, unit?: string, showUnitIfEmpty?: bool, mask?: string}
 * @phpstan-type formatCallback callable(static $self): NumberFormat
 * @phpstan-type defaultCallback callable(constructorNumberFormat $options, static $self, string $key): NumberFormat
 */
class Formats
{
	/** @var null|defaultCallback */
	private $default = null;

	/**
	 * @var array<string, NumberFormat>
	 */
	private array $formats = [];


	/**
	 * @param array<string, formatCallback|NumberFormat> $factories
	 */
	public function __construct(
		private array $factories = [],
	)
	{
	}


	/**
	 * @param defaultCallback|NumberFormat $default
	 */
	public function setDefault(callable|NumberFormat $default): void
	{
		if ($this->default !== null) {
			throw new InvalidStateException('Default format could be setup only onetime.');
		} elseif ($default instanceof NumberFormat) {
			$default = static fn (array $options): NumberFormat => $default->modify(...$options);
		}

		$this->default = $default;
	}


	/**
	 * @param formatCallback|NumberFormat $setup
	 */
	public function add(string $key, callable|NumberFormat $setup): void
	{
		if ($setup instanceof NumberFormat) {
			$this->formats[$key] = $setup;
		} else {
			$this->factories[$key] = $setup;
		}
	}


	public function has(string $key): bool
	{
		return isset($this->formats[$key]) || isset($this->factories[$key]);
	}


	public function get(string $key): NumberFormat
	{
		if (isset($this->formats[$key]) === false) {
			if (isset($this->factories[$key])) {
				$service = $this->factories[$key];
				$format = is_callable($service) ? $service($this) : $service;
			} else {
				$format = $this->getDefault()(['unit' => $key], $this, $key);
			}
			$this->formats[$key] = $format;
			unset($this->factories[$key]);
		}

		return $this->formats[$key];
	}


	/**
	 * @return defaultCallback
	 */
	public function getDefault(): callable
	{
		if ($this->default === null) {
			$this->default = static fn (array $options): NumberFormat => new NumberFormat(...$options);
		}

		return $this->default;
	}

}
