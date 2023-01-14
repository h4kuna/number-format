<?php declare(strict_types=1);

namespace h4kuna\Number;

class Tax
{
	private Percent $vat;


	/**
	 * @param int|float|Percent $vat
	 */
	public function __construct($vat)
	{
		if (!$vat instanceof Percent) {
			$vat = new Percent($vat);
		}
		$this->vat = $vat;
	}


	public function getVat(): float
	{
		return $this->vat->getPercent();
	}


	public function add(float $number): float
	{
		return $this->vat->add($number);
	}


	public function diff(float $number): float
	{
		return $number - $this->deduct($number);
	}


	public function deduct(float $number): float
	{
		return $number / $this->vat->getRatio();
	}


	public function __toString()
	{
		return (string) $this->vat;
	}

}
