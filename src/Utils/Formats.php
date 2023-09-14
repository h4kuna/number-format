<?php declare(strict_types=1);

namespace h4kuna\Format\Utils;

use h4kuna\Format\Exceptions\InvalidStateException;

/**
 * @template T
 * @phpstan-type formatCallback callable(static $self): T
 * @phpstan-type defaultCallback callable(string|int $key, static $self, mixed $options): T
 */
class Formats
{
	/** @var null|defaultCallback */
	private $default = null;

	/**
	 * @var array<string|int, T>
	 */
	private array $formats = [];


	/**
	 * @param array<string|int, formatCallback|T> $factories
	 */
	public function __construct(
		private array $factories = [],
	)
	{
	}


	/**
	 * @param formatCallback|T $setup
	 */
	public function add(string|int $key, $setup): void
	{
		if (self::isCallback($setup)) {
			$this->factories[$key] = $setup;
			unset($this->formats[$key]);
		} else {
			$this->formats[$key] = $setup;
		}
	}


	public function has(string|int $key): bool
	{
		return isset($this->formats[$key]) || isset($this->factories[$key]);
	}


	/**
	 * @return T
	 */
	public function get(string|int $key)
	{
		if (isset($this->formats[$key]) === false) {
			if (isset($this->factories[$key])) {
				$service = $this->factories[$key];
				$format = self::isCallback($service) ? $service($this) : $service;
			} else {
				$format = $this->getDefault()($key, $this, null);
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
			$this->default = $this->createDefaultCallback();
		}

		return $this->default;
	}


	/**
	 * @param defaultCallback|T $default
	 */
	public function setDefault($default): void
	{
		if ($this->default !== null) {
			throw new InvalidStateException('Default format could be setup only onetime.');
		} elseif (self::isCallback($default) === false) {
			$default = $this->createDefaultCallback($default);
		}

		$this->default = $default;
	}


	/**
	 * @return ($service is callable ? true : false)
	 */
	private static function isCallback(mixed $service): bool
	{
		return is_callable($service) && ($service instanceof Service === false);
	}


	/**
	 * @param T|null $object
	 * @return defaultCallback
	 */
	protected function createDefaultCallback($object = null): callable
	{
		throw new InvalidStateException('Default format is not setup.');
	}

}
