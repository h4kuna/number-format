<?php

namespace h4kuna\Number;

interface NumberFormat
{

	function format($number, string $unit = ''): string;

}
