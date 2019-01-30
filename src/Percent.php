<?php

namespace h4kuna\Number;

final class Percent
{

	/** @var float */
	private $ratio;

	/** @var float */
	private $percent;


	public function __construct(float $percent)
	{
		$this->percent = $percent;
		$this->ratio = ($percent / 100) + 1;
	}


	/**
	 * @example 19.5% = float 19.5
	 * @return float
	 */
	public function getPercent(): float
	{
		return $this->percent;
	}


	/**
	 * @example 19.5% = float 1.195
	 */
	public function getRatio(): float
	{
		return $this->ratio;
	}


	public function add(float $number): float
	{
		return $this->getRatio() * $number;
	}


	public function deduct(float $number): float
	{
		return $number - $this->diff($number);
	}


	public function diff(float $number): float
	{
		return ($number / 100) * $this->percent;
	}


	public function __toString()
	{
		return (string) $this->percent;
	}

}
