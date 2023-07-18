<?php declare(strict_types=1);

namespace h4kuna\Number\Utils;

use h4kuna\Number\Exceptions\InvalidStateException;
use h4kuna\Number\NumberFormat;

class Formats
{
	private ?NumberFormat $default = null;


	/** @param array<string, NumberFormat> $formats */
	public function __construct(
		private array $formats = [],
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


	public function add(string $key, NumberFormat $setup): void
	{
		$this->formats[$key] = $setup;
	}


	public function has(string $key): bool
	{
		return isset($this->formats[$key]);
	}


	public function get(string $key): NumberFormat
	{
		if (isset($this->formats[$key]) === false) {
			$this->formats[$key] = $this->getDefault()->modify(unit: $key);
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
