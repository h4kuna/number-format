<?php


namespace h4kuna\Number\Utils;


class Parameters
{
	private static $parameters = [];


	public static function canExtract(& $data, $method, $index = 0)
	{
		if (!is_array($data)) {
			return FALSE;
		}

		$parameter = self::getParameterReflection($method, $index);
		$key = $parameter->getName();
		if (!isset($data[$key])) {
			$data[$key] = $parameter->getDefaultValue();
		}

		return TRUE;
	}


	/**
	 * @param string $method
	 * @return \ReflectionParameter
	 */
	private static function getParameterReflection($method, $index)
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