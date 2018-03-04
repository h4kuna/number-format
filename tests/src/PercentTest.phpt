<?php

namespace h4kuna\Number;

use Tester\TestCase;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

class PercentTest extends TestCase
{

	public function testAdd()
	{
		$percent = new Percent(20);
		Assert::same(120.0, $percent->add(100));
	}

	public function testDeduct()
	{
		$percent = new Percent(20);
		Assert::same(96.0, $percent->deduct(120));
	}

	public function testDiff()
	{
		$percent = new Percent(20);
		Assert::same(24.0, $percent->diff(120));
		Assert::same(20.0, $percent->getPercent());
	}

}

(new PercentTest)->run();