<?php declare(strict_types=1);

namespace h4kuna\Format\Number\Formatters;

use h4kuna\Format\Number\Formatter;
use h4kuna\Format\Utils\Space;
use NumberFormatter;

/**
 * NumberFormatter default settings:
 *  - zeroClear: ZeroClear::DECIMALS
 *  - nbsp: true
 */
final class IntlNumberFormatter implements Formatter
{
	public function __construct(
		private NumberFormatter $formatter,
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
		return new static(
			$this->formatter,
			$emptyValue ?? $this->emptyValue,
			$zeroIsEmpty ?? $this->zeroIsEmpty,
		);
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


	public function __invoke(float|int|string|null $number): string
	{
		return $this->format($number);
	}

}
