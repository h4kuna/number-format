<?php declare(strict_types=1);

namespace h4kuna\Number\Utils;

use h4kuna\Number\Exceptions;

class Parameters
{
	/**
	 * @var array<string, \ReflectionParameter>
	 */
	private static $parameters = [];


	/**
	 * @param array<string, mixed>|mixed $data
	 * @throws \ReflectionException
	 */
	public static function canExtract(&$data, string $method, int $index = 0): bool
	{
		if (!is_array($data)) {
			return false;
		}

		$parameter = self::getParameterReflection($method, $index);
		$key = $parameter->getName();
		if (!isset($data[$key])) {
			$data[$key] = $parameter->getDefaultValue();
		}

		return true;
	}


	private static function getParameterReflection(string $method, int $index): \ReflectionParameter
	{
		$key = $method . '.' . $index;
		if (!isset(self::$parameters[$key])) {
			$parameters = (new \ReflectionMethod($method))->getParameters();
			if (!isset($parameters[$index])) {
				throw new Exceptions\InvalidArgumentException(sprintf('Parameter on this index "%s" does not exists.', $index));
			}
			self::$parameters[$key] = $parameters[$index];
		}
		return self::$parameters[$key];
	}

}
