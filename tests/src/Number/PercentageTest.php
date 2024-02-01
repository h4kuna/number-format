<?php

declare(strict_types=1);

namespace h4kuna\Format\Tests\Number;

use h4kuna\Format\Number\Formatter;
use h4kuna\Format\Number\Formatters\NumberFormatter;
use h4kuna\Format\Number\Percentage;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../../bootstrap.php';

final class PercentageTest extends TestCase
{

	public function testBasic(): void
	{
		$percentage = new Percentage(20, new NumberFormatter(nbsp: false, unit: '%'));
		Assert::same(120.0, $percentage->with(100));
		Assert::same(20.0, $percentage->withDiff(100));
		Assert::same(100.0, $percentage->without(120));
		Assert::same(20.0, $percentage->withoutDiff(120));
		Assert::same(96.0, $percentage->deduct(120));
		Assert::same(24.0, $percentage->diff(120));
		Assert::same(20.0, $percentage->diff(100));
		Assert::same(20.0, $percentage->percentage);
		Assert::same(1.20, $percentage->ratio);
		Assert::same(0.20, $percentage->smallRatio);
		Assert::same('21,50 %', $percentage->modify(21.5)->toString());
		Assert::same('20,00 %', (string) $percentage);
		Assert::type(Formatter::class, $percentage->format);

		Assert::same('20', (string) new Percentage(20));
	}

}

(new PercentageTest())->run();
