<?php declare(strict_types=1);

namespace h4kuna\Number;

if (false) {
	/** @deprecated use NumberFormat */
	class NumberFormatState
	{
	}

	/** @deprecated use NumberFormat */
	class UnitFormatState
	{
	}

	/** @deprecated use NumberFormat */
	class UnitPersistentFormatState
	{
	}
} elseif (!class_exists(NumberFormatState::class)) {
	class_alias(NumberFormat::class, NumberFormatState::class);
	class_alias(NumberFormat::class, UnitFormatState::class);
	class_alias(NumberFormat::class, UnitPersistentFormatState::class);
}


