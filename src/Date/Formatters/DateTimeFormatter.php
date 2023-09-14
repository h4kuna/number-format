<?php declare(strict_types=1);

namespace h4kuna\Format\Date\Formatters;

use DateTimeInterface;
use h4kuna\Format\Date\Formatter;
use h4kuna\Format\Utils\Space;

final class DateTimeFormatter implements Formatter
{
	private string $emptyValueSpace;

	private string $formatSpace;


	public function __construct(
		public string $format,
		public bool $nbsp = true,
		public string $emptyValue = '',
	)
	{
		$this->initSpace();
	}


	private function initSpace(): void
	{
		$this->emptyValueSpace = $this->nbsp ? Space::nbsp($this->emptyValue) : $this->emptyValue;
		$this->formatSpace = $this->nbsp ? Space::nbsp($this->format) : $this->format;
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
		return $dateTime === null ? $this->emptyValueSpace : $dateTime->format($this->formatSpace);
	}

	public function __invoke(?DateTimeInterface $dateTime): string
	{
		return $this->format($dateTime);
	}

}
