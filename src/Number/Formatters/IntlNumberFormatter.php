<?php declare(strict_types=1);

namespace h4kuna\Format\Number\Formatters;

use h4kuna\Format\Number\Formatter;
use h4kuna\Format\Utils\Space;

/**
 * NumberFormatter default settings:
 *  - zeroClear: ZeroClear::DECIMALS
 *  - nbsp: true
 */
final class IntlNumberFormatter implements Formatter
{
	public function __construct(
		private \NumberFormatter $formatter,
		public string $emptyValue = '',
		public bool $zeroIsEmpty = false,
	)
	{
		$this->emptyValue = Space::nbsp($this->emptyValue);
	}


	public function modify(
		?string $emptyValue = null,
		?bool $zeroIsEmpty = null,
	): self
	{
		$that = clone $this;
		$that->zeroIsEmpty = $zeroIsEmpty ?? $that->zeroIsEmpty;
		$that->emptyValue = $emptyValue === null ? $this->emptyValue : Space::nbsp($emptyValue);

		return $that;
	}


	public function format(string|int|float|null $number): string
	{
		if (is_numeric($number) === false || ($this->zeroIsEmpty && (int) $number === 0)) {
			return $this->emptyValue;
		}

		$result = $this->formatter->format(is_string($number) ? (float) $number : $number);
		assert(is_string($result));

		return $result;
	}
}
