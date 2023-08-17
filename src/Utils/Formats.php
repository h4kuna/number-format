<?php declare(strict_types=1);

namespace h4kuna\Number\Utils;

use Closure;
use h4kuna\Number\Exceptions\InvalidStateException;
use h4kuna\Number\NumberFormat;

class Formats
{
	/** @var null|Closure(array<string, mixed>, ?self, ?string): NumberFormat */
	private ?Closure $default = null;

	/**
	 * @var array<string, NumberFormat>
	 */
	private array $formats = [];


	/**
	 * @param array<string, NumberFormat|Closure(self): NumberFormat> $factories
	 */
	public function __construct(
		private array $factories = [],
	)
	{
	}


	public function setDefault(Closure $setup): void
	{
		if ($this->default !== null) {
			throw new InvalidStateException('Default format could be setup only onetime.');
		}

		$this->default = $setup;
	}


	public function add(string $key, NumberFormat|Closure $setup): void
	{
		if ($setup instanceof Closure) {
			$this->factories[$key] = $setup;
		} else {
			$this->formats[$key] = $setup;
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
				$format = $service instanceof Closure ? $service($this) : $service;
			} else {
				$format = $this->getDefault()(['unit' => $key], $this, $key);
			}
			$this->formats[$key] = $format;
			unset($this->factories[$key]);
		}

		return $this->formats[$key];
	}


	public function getDefault(): Closure
	{
		if ($this->default === null) {
			$this->default = static function(array $options = [], ?self $that = null, ?string $key = null): NumberFormat {
				return new NumberFormat(...$options);
			};
		}

		return $this->default;
	}

}
