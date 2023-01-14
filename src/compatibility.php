<?php declare(strict_types=1);

namespace h4kuna\Number;

if (false) {
	/** @deprecated use NumberFormat */
	class NumberFormatState
	{
	}
} elseif (!class_exists(NumberFormatState::class)) {
	class_alias(NumberFormat::class, NumberFormatState::class);
}


