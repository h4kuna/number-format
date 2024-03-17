<?php declare(strict_types=1);

namespace h4kuna\Format\Number\NativePhp;

use Locale;

final class NumberFormatterFactory
{
	private string $locale;


	public function __construct(?string $locale = null)
	{
		$this->locale = $locale ?? Locale::getDefault();
	}


	/**
	 * @template T of NumberFormatter
	 * @param int|class-string<T> $style
	 * @return T
	 */
	public function create(
		?int $decimals = null,
		?string $locale = null,
		int|string $style = NumberFormatter::DECIMAL
	): NumberFormatter
	{
		$locale ??= $this->locale;
		$formatter = is_int($style)
			? new NumberFormatter($locale, $style)
			: new $style($locale);

		if ($decimals !== null) {
			$formatter->setAttribute(NumberFormatter::FRACTION_DIGITS, $decimals);
		}

		return $formatter;
	}


	public function currency(?string $locale = null): NumberFormatter
	{
		return $this->create(null, $locale, NumberFormatter::CURRENCY);
	}


	public function ordinal(?string $locale = null): NumberFormatter
	{
		return $this->create(null, $locale, NumberFormatter::ORDINAL);
	}


	public function percent(?int $decimals = 0, ?string $locale = null): NumberFormatterPercent
	{
		return $this->create($decimals, $locale, NumberFormatterPercent::class);
	}


	public function spell(?string $locale = null): NumberFormatter
	{
		return $this->create(null, $locale, NumberFormatter::SPELLOUT);
	}
}
