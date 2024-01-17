<?php declare(strict_types=1);

namespace h4kuna\Format\Number;

use h4kuna\Format\Utils;

abstract class Percentage
{
	public float $ratio;

	public float $smallRatio;

	private ?Formatter $format = null;


	public function __construct(public float $percentage)
	{
		$this->smallRatio = Utils\Percentage::smallRatio($this->percentage);
		$this->ratio = Utils\Percentage::ratio($this->smallRatio);
	}


	public function modify(float $percentage): static
	{
		$self = new static($percentage);
		$self->format = $this->format;

		return $self;
	}


	public function setFormatter(Formatter $formatter): void
	{
		$this->format = $formatter;
	}


	public function with(float $number): float
	{
		return Utils\Percentage::with($this->ratio, $number);
	}


	abstract public function diff(float $number): float;


	public function __toString()
	{
		if ($this->format === null) {
			return (string) $this->percentage;
		}

		return $this->format->format($this->percentage);
	}

}
