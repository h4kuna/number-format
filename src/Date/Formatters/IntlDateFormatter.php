<?php declare(strict_types = 1);

namespace h4kuna\Format\Date\Formatters;

use DateTimeInterface;
use h4kuna\Format\Date\Formatter;
use h4kuna\Format\Utils\Space;
use IntlDateFormatter as PhpIntlDateFormatter;
use function assert;
use function is_string;

final class IntlDateFormatter implements Formatter
{

	private string $emptyValueSpace;


	public function __construct(
		private PhpIntlDateFormatter $formatter,
		public bool $nbsp = true,
		public string $emptyValue = '',
	)
	{
		$this->initSpace();
	}

	public function __invoke(?DateTimeInterface $dateTime): string
	{
		return $this->format($dateTime);
	}

	private function initSpace(): void
	{
		$this->emptyValueSpace = $this->nbsp ? Space::nbsp($this->emptyValue) : $this->emptyValue;
	}

	public function modify(
		?bool $nbsp = null,
		?string $emptyValue = null,
	): self
	{
		return new static($this->formatter, $nbsp ?? $this->nbsp, $emptyValue ?? $this->emptyValue);
	}

	public function format(?DateTimeInterface $dateTime): string
	{
		if ($dateTime === null) {
			$result = $this->emptyValueSpace;
		} else {
			$result = $this->formatter->format($dateTime);
			assert(is_string($result));
		}

		return $this->nbsp ? Space::nbsp($result) : $result;
	}

}
