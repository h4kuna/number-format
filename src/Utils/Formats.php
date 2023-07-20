<?php declare(strict_types=1);

namespace h4kuna\Number\Utils;

use h4kuna\Number\Exceptions\InvalidStateException;
use h4kuna\Number\NumberFormat;

class Formats
{
	private ?NumberFormat $default = null;

	/**
	 * @var array<string, NumberFormat>
	 */
	private array $formats = [];


	/**
	 * @param array<string, NumberFormat|\Closure(self): NumberFormat> $factories
	 */
	public function __construct(
		private array $factories = [],
	)
	{
	}


	public function setDefault(NumberFormat $setup): void
	{
		if ($this->default !== null) {
			throw new InvalidStateException('Default format could be setup only onetime.');
		}

		$this->default = $setup;
	}


	public function add(string $key, NumberFormat|\Closure $setup): void
	{
		if ($setup instanceof \Closure) {
			$this->factories[$key] = $setup;
		} else {
			$this->formats[$key] = $setup;
		}
	}


	public function has(string $key): bool
	{
		return isset($this->formats[$key]);
	}


	public function get(string $key): NumberFormat
	{
		if (isset($this->formats[$key]) === false) {
			if (isset($this->factories[$key])) {
				$service = $this->factories[$key];
				$format = $service instanceof \Closure ? $service($this) : $service;
			} else {
				$format = $this->getDefault()->modify(unit: $key);
			}
			$this->formats[$key] = $format;
			unset($this->factories[$key]);
		}

		return $this->formats[$key];
	}


	protected function getDefault(): NumberFormat
	{
		if ($this->default === null) {
			$this->default = new NumberFormat();
		}

		return $this->default;
	}

}
