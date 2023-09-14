<?php declare(strict_types=1);

namespace h4kuna\Format\Date\Formatters;

use DateTimeInterface;
use h4kuna\Format\Date\Formatter;
use h4kuna\Format\Utils\Space;

final class IntlDateFormatter implements Formatter
{
	private string $emptyValueSpace;


	public function __construct(
		private \IntlDateFormatter $formatter,
		public bool $nbsp = true,
		public string $emptyValue = '',
	)
	{
		$this->initSpace();
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
		$that = clone $this;
		$that->nbsp = $nbsp ?? $that->nbsp;
		$that->emptyValue = $emptyValue ?? $that->emptyValue;
		$that->initSpace();

		return $that;
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


	public function __invoke(?DateTimeInterface $dateTime): string
	{
		return $this->format($dateTime);
	}

}
