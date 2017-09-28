<?php

namespace h4kuna\Number;

final class Percent
{

	/** @var float */
	private $ratio;

	/** @var float */
	private $percent;


	public function __construct($percent)
	{
		$this->percent = $percent;
		$this->ratio = ($percent / 100) + 1;
	}


	/**
	 * @example 19.5% = float 19.5
	 * @return float|int
	 */
	public function getPercent()
	{
		return $this->percent;
	}


	/**
	 * @example 19.5% = float 1.195
	 * @return float
	 */
	public function getRatio()
	{
		return $this->ratio;
	}


	/**
	 * @param int|float $number
	 * @return float
	 */
	public function add($number)
	{
		return $this->getRatio() * $number;
	}


	/**
	 * @param int|float $number
	 * @return float
	 */
	public function deduct($number)
	{
		return $number - $this->diff($number);
	}


	/**
	 * @param int|float $number
	 * @return float
	 */
	public function diff($number)
	{
		return ($number / 100) * $this->percent;
	}


	public function __toString()
	{
		return (string) $this->percent;
	}
}
