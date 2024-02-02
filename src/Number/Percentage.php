<?php declare(strict_types=1);

namespace h4kuna\Format\Number;

use h4kuna\Format\Utils;

/**
 * properties become readonly
 */
class Percentage implements \Stringable
{
	public float $ratio;

	public float $smallRatio;

	public float $percentage;


	final public function __construct(float|int $percentage, public ?Formatter $format = null)
	{
		$this->percentage = (float) $percentage;
		$this->smallRatio = Utils\Percentage::smallRatio($this->percentage);
		$this->ratio = Utils\Percentage::ratio($this->smallRatio);
	}


	public function modify(float $percentage, ?Formatter $format = null): static
	{
		return new static($percentage, $format ?? $this->format);
	}


	public function with(float $number): float
	{
		return Utils\Percentage::with($number, $this->ratio);
	}


	/**
	 * @deprecated use diff()
	 */
	public function withDiff(float $number): float
	{
		return Utils\Percentage::with($number, $this->smallRatio);
	}


	public function without(float $number): float
	{
		return Utils\Percentage::without($number, $this->ratio);
	}


	public function withoutDiff(float $number): float
	{
		return Utils\Percentage::withoutDiff($number, $this->ratio);
	}


	public function diff(float $number): float
	{
		return Utils\Percentage::with($number, $this->smallRatio);
	}


	public function deduct(float $number): float
	{
		return Utils\Percentage::deduct($number, $this->smallRatio);
	}


	public function toString(): string
	{
		if ($this->format === null) {
			return (string) $this->percentage;
		}

		return $this->format->format($this->percentage);
	}


	public function __toString(): string
	{
		return $this->toString();
	}

}
