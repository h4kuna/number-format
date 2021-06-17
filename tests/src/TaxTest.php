<?php declare(strict_types=1);

namespace h4kuna\Number\Tests;

use h4kuna\Number\Percent;
use h4kuna\Number\Tax;
use h4kuna\Number\Tests\TestCase;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
final class TaxTest extends TestCase
{

	public function testApi(): void
	{
		$tax = new Tax(20);
		Assert::same(120.0, $tax->add(100));
		Assert::same(100.0, $tax->deduct(120));
		Assert::same(20.0, $tax->diff(120));
		Assert::same(20.0, $tax->getVat());

		Assert::same('20', (string) new Tax(new Percent(20)));
	}

}

(new TaxTest)->run();
