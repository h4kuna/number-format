<?php declare(strict_types=1);

namespace h4kuna\Number\Utils;

use h4kuna\Number\Exceptions\InvalidStateException;
use h4kuna\Number\NumberFormat;

class Formats
{
	/** @var null|callable(array<string, mixed>, ?self, ?string): NumberFormat */
	private $default = null;

	/**
	 * @var array<string, NumberFormat>
	 */
	private array $formats = [];


	/**
	 * @param array<string, NumberFormat|callable(self): NumberFormat> $factories
	 */
	public function __construct(
		private array $factories = [],
	)
	{
	}


	public function setDefault(callable|NumberFormat $default): void
	{
		if ($this->default !== null) {
			throw new InvalidStateException('Default format could be setup only onetime.');
		} elseif ($default instanceof NumberFormat) {
			$default = static fn ($options): NumberFormat => $default->modify(...$options);
		}

		$this->default = $default;
	}


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


	public function getDefault(): callable
	{
		if ($this->default === null) {
			$this->default = static function (
				array $options = [],
				?self $that = null,
				?string $key = null
			): NumberFormat {
				return new NumberFormat(...$options);
			};
		}

		return $this->default;
	}

}
