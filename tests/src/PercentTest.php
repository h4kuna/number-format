<?php declare(strict_types=1);

namespace h4kuna\Number\Tests;

use h4kuna\Number\Percent;
use h4kuna\Number\Tests\TestCase;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
final class PercentTest extends TestCase
{

	public function testAdd(): void
	{
		$percent = new Percent(20);
		Assert::same(120.0, $percent->add(100));
	}


	public function testDeduct(): void
	{
		$percent = new Percent(20);
		Assert::same(96.0, $percent->deduct(120));
	}


	public function testDiff(): void
	{
		$percent = new Percent(20);
		Assert::same(24.0, $percent->diff(120));
		Assert::same(20.0, $percent->getPercent());
	}

}

(new PercentTest)->run();
