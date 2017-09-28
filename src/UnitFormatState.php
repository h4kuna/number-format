<?php

namespace h4kuna\Number;

class UnitFormatState
{

	/** @var NumberFormatState */
	private $numberFormatState;

	/** @var string */
	private $mask;

	/** @var bool */
	private $showUnit;


	public function __construct(NumberFormatState $numberFormatState, $mask = '1 U', $showUnit = true, $nbsp = true)
	{
		self::validMask($mask);
		$this->numberFormatState = $numberFormatState;
		$this->showUnit = $showUnit;
		$this->mask = $nbsp ? str_replace(' ', $numberFormatState::NBSP, $mask) : $mask;
	}


	public function format($number, $unit)
	{
		$formatted = $this->numberFormatState->format($number);
		if ($this->showUnit === false && $this->numberFormatState->getEmptyValue() === $formatted) {
			return $formatted;
		}
		return str_replace(['1', 'U'], [$formatted, (string) $unit], $this->mask);
	}


	private static function validMask($mask)
	{
		if (substr_count($mask, 'U') !== 1 || substr_count($mask, '1') !== 1) {
			throw new InvalidMaskException('Mask must containt number 1 and letter upper U both onetime.');
		}
	}

}