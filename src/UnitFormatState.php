<?php declare(strict_types=1);

namespace h4kuna\Number;

use h4kuna\Number\Exceptions\InvalidMask;

class UnitFormatState
{

	/** @var NumberFormatState */
	private $numberFormatState;

	/** @var string */
	private $mask;

	/** @var bool */
	private $showUnit;

	public function __construct(NumberFormatState $numberFormatState, string $mask = '1 U', bool $showUnit = true, bool $nbsp = true)
	{
		$this->numberFormatState = $numberFormatState;
		$this->showUnit = $showUnit;
		$this->mask = self::validMask($nbsp, $mask, $numberFormatState);
	}


	public function format($number, string $unit): string
	{
		$formatted = $this->numberFormatState->format($number);
		if ($this->showUnit === false && $this->numberFormatState->getEmptyValue() === $formatted) {
			return $formatted;
		}
		if ($unit !== '') {
			return str_replace(['1', 'U'], [$formatted, $unit], $this->mask);
		}
		return $formatted;
	}


	private static function validMask(bool $nbsp, string $mask, NumberFormatState $numberFormatState): string
	{
		if (substr_count($mask, 'U') !== 1 || substr_count($mask, '1') !== 1) {
			throw new InvalidMask('Mask must contains number 1 and letter upper U both onetime.');
		}
		return $nbsp ? str_replace(' ', $numberFormatState::NBSP, $mask) : $mask;
	}

}
