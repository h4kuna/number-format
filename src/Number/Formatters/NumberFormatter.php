<?php declare(strict_types=1);

namespace h4kuna\Format\Number\Formatters;

use h4kuna\Format\Number\Formatter;
use h4kuna\Format\Number\NumberFormat;
use h4kuna\Format\Number\Parameters\ZeroClear;
use h4kuna\Format\Number\Round;
use h4kuna\Format\Utils\Space;

class NumberFormatter implements Formatter
{
	/**
	 * @var callable(float, int): float|null
	 */
	public /* readonly */ $roundCallback;

	private string $maskReplaced = '';


	/**
	 * @param string $mask - Mask must contains number 1 and character ⎵ for dynamic unit.
	 */
	public function __construct(
		public /* readonly */ int $decimals = 2,
		public /* readonly */ string $decimalPoint = ',',
		public /* readonly */ string $thousandsSeparator = ' ',
		public /* readonly */ bool $nbsp = true,
		public /* readonly */ int $zeroClear = ZeroClear::NO,
		public /* readonly */ string $emptyValue = Space::AS_NULL,
		public /* readonly */ bool $zeroIsEmpty = false,
		public /* readonly */ string $unit = '',
		public /* readonly */ bool $showUnitIfEmpty = true,
		public /* readonly */ string $mask = '1 ⎵',
		null|int|callable $round = null,
	)
	{
		$this->roundCallback = self::makeRoundCallback($round, $this->decimals);
		$this->initMaskReplaced();
		$this->initThousandsSeparator();
	}


	private static function makeRoundCallback(null|int|callable $round, int $decimals): ?callable
	{
		if ($decimals < 0 && $round === null) {
			return Round::create();
		} elseif ($round === null) {
			return null;
		} elseif (is_int($round)) {
			return Round::create($round);
		}

		return $round;
	}


	private function initMaskReplaced(): void
	{
		if ($this->unit !== '' && $this->mask !== '') {
			$replace = ['⎵' => $this->unit];
			if ($this->nbsp) {
				$replace[' '] = Space::NBSP;
			}

			$this->maskReplaced = strtr($this->mask, $replace);
		} else {
			$this->maskReplaced = '';
		}
	}


	private function initThousandsSeparator(): void
	{
		if ($this->nbsp && str_contains($this->thousandsSeparator, ' ')) {
			$this->thousandsSeparator = Space::nbsp($this->thousandsSeparator);
		} elseif ($this->nbsp === false && str_contains($this->thousandsSeparator, Space::NBSP)) {
			$this->thousandsSeparator = Space::white($this->thousandsSeparator);
		}
	}


	public function modify(
		?int $decimals = null,
		?string $decimalPoint = null,
		?string $thousandsSeparator = null,
		?bool $nbsp = null,
		?int $zeroClear = null,
		?string $emptyValue = null,
		?bool $zeroIsEmpty = null,
		?string $unit = null,
		?bool $showUnitIfEmpty = null,
		?string $mask = null,
		null|int|callable $round = null,
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
		$that->roundCallback = $round === null ? $that->roundCallback : self::makeRoundCallback($round, $this->decimals);

		if ($mask !== null || $unit !== null) {
			$that->mask = $mask ?? $that->mask;
			$that->unit = $unit ?? $that->unit;
		}
		$that->initMaskReplaced();
		$that->initThousandsSeparator();

		return $that;
	}


	public function format(string|int|float|null $number): string
	{
		return NumberFormat::unit(
			$number,
			$this->decimals,
			$this->decimalPoint,
			$this->thousandsSeparator,
			false,
			$this->emptyValue,
			$this->zeroIsEmpty,
			$this->zeroClear,
			$this->maskReplaced,
			$this->showUnitIfEmpty,
			$this->roundCallback,
		);
	}


	public function __invoke(float|int|string|null $number): string
	{
		return $this->format($number);
	}

}
