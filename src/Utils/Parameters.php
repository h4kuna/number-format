<?php declare(strict_types=1);

namespace h4kuna\Number\Utils;

class Parameters
{

	private static $parameters = [];

	public static function canExtract(& $data, string $method, int $index = 0): bool
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
				throw new \InvalidArgumentException('Parameter on this index "' . $index . '" does not exists.');
			}
			self::$parameters[$key] = $parameters[$index];
		}
		return self::$parameters[$key];
	}

}
