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
	public /* readonly ?callable */ $roundCallback;

	public /* readonly */ string $thousandsSeparator;

	private /* readonly */ string $maskReplaced;


	/**
	 * @param string $mask - Mask must contains number 1 and character ⎵ for dynamic unit.
	 */
	public function __construct(
		public /* readonly */ int $decimals = 2,
		public /* readonly */ string $decimalPoint = ',',
		string $thousandsSeparator = ' ',
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
		$this->roundCallback = $this->initRoundCallback($round);
		$this->maskReplaced = $this->initMaskReplaced();
		$this->thousandsSeparator = $this->initThousandsSeparator($thousandsSeparator);
	}


	private function initRoundCallback(null|int|callable $round): callable
	{
		if ($round === null || $round === Round::RESET) {
			return Round::create();
		} elseif (is_int($round)) {
			return Round::create($round);
		}

		return $round;
	}


	private function initMaskReplaced(): string
	{
		if ($this->unit !== '' && $this->mask !== '') {
			$replace = ['⎵' => $this->unit];
			if ($this->nbsp) {
				$replace[' '] = Space::NBSP;
			}

			return strtr($this->mask, $replace);
		}
		return '';
	}


	private function initThousandsSeparator(string $thousandsSeparator): string
	{
		if ($this->nbsp && str_contains($thousandsSeparator, ' ')) {
			return Space::nbsp($thousandsSeparator);
		} elseif ($this->nbsp === false && str_contains($thousandsSeparator, Space::NBSP)) {
			return Space::white($thousandsSeparator);
		}

		return $thousandsSeparator;
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
		return new self(
			$decimals ?? $this->decimals,
			$decimalPoint ?? $this->decimalPoint,
			$thousandsSeparator ?? $this->thousandsSeparator,
			$nbsp ?? $this->nbsp,
			$zeroClear ?? $this->zeroClear,
			$emptyValue ?? $this->emptyValue,
			$zeroIsEmpty ?? $this->zeroIsEmpty,
			$unit ?? $this->unit,
			$showUnitIfEmpty ?? $this->showUnitIfEmpty,
			$mask ?? $this->mask,
			$round ?? $this->roundCallback,
		);
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
