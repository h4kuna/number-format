<?php

namespace h4kuna\Number\Utils;

use h4kuna\Number;

class Crate implements \ArrayAccess
{
	/** @var array */
	private $data;

	public function __construct(array $data)
	{
		$this->data = $data;
	}

	final public function __get($name)
	{
		return $this->data[$name];
	}

	final public function offsetGet($offset)
	{
		return $this->data[$offset];
	}

	final public function offsetSet($offset, $value)
	{
		throw new Number\FrozenMethodException();
	}

	final public function offsetUnset($offset)
	{
		throw new Number\FrozenMethodException();
	}

	final public function offsetExists($offset)
	{
		throw new Number\FrozenMethodException();
	}

}