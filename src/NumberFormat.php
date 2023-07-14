<?php declare(strict_types=1);

namespace h4kuna\Number;

use h4kuna\Number\Utils\Round;

class NumberFormat
{
	private ?\Closure $roundCallback;

	private string $maskReplaced = '';


	/**
	 * @param string $mask - Mask must contains number 1 and character ⎵ for dynamic unit.
	 */
	public function __construct(
		private int $decimals = 2,
		private string $decimalPoint = ',',
		private string $thousandsSeparator = ' ',
		private bool $nbsp = true,
		private bool $zeroClear = false,
		private string $emptyValue = Format::AS_NULL,
		private bool $zeroIsEmpty = false,
		private string $unit = '',
		private bool $showUnitIfEmpty = true,
		private string $mask = '1 ⎵',
		null|int|\Closure $round = null,
	)
	{
		$this->roundCallback = $this->makeRoundCallback($round);
		$this->initMaskReplaced();
	}


	public function modify(
		?int $decimals = null,
		?string $decimalPoint = null,
		?string $thousandsSeparator = null,
		?bool $nbsp = null,
		?bool $zeroClear = null,
		?string $emptyValue = null,
		?bool $zeroIsEmpty = null,
		?string $unit = null,
		?bool $showUnitIfEmpty = null,
		?string $mask = null,
		null|int|\Closure $round = null,
	): self
	{
		$that = clone $this;
		$that->decimals = $decimals ?? $this->decimals;
		$that->decimalPoint = $decimalPoint ?? $that->decimalPoint;
		$that->thousandsSeparator = $thousandsSeparator ?? $that->thousandsSeparator;
		$that->nbsp = $nbsp ?? $that->nbsp;
		$that->zeroClear = $zeroClear ?? $that->zeroClear;
		$that->emptyValue = $emptyValue ?? $that->emptyValue;
		$that->zeroIsEmpty = $zeroIsEmpty ?? $that->zeroIsEmpty;
		$that->showUnitIfEmpty = $showUnitIfEmpty ?? $that->showUnitIfEmpty;
		$that->roundCallback = $round === null ? $that->roundCallback : $this->makeRoundCallback($round);

		if ($mask !== null || $unit !== null) {
			$that->mask = $mask ?? $that->mask;
			$that->unit = $unit ?? $that->unit;
			$that->initMaskReplaced();
		}

		return $that;
	}


	public function format(string|int|float|null $number): string
	{
		return Format::unit(
			$number,
			$this->decimals,
			$this->decimalPoint,
			$this->thousandsSeparator,
			$this->nbsp,
			$this->emptyValue,
			$this->zeroIsEmpty,
			$this->zeroClear,
			$this->maskReplaced,
			$this->showUnitIfEmpty,
			$this->roundCallback,
		);
	}


	private function makeRoundCallback(null|int|\Closure $round): ?\Closure
	{
		if ($round === null) {
			return null;
		} elseif (is_int($round)) {
			return Round::create($round);
		}

		return $round;
	}


	private function initMaskReplaced(): void
	{
		$this->maskReplaced = $this->unit === '' || $this->mask === '' ? '' : str_replace('⎵', $this->unit, $this->mask);
	}

}
