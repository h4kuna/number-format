<?php

namespace h4kuna\Number;

use Tester\TestCase,
	Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';


class TaxTest extends TestCase
{

	public function testApi()
	{
		$tax = new Tax(20);
		Assert::same(120.0, $tax->addVat(100));
		Assert::same(100.0, $tax->removeVat(120));
		Assert::same(20.0, $tax->diff(120));
		Assert::same(20, $tax->getVat());

		Assert::same('20', (string) new Tax(new Percent(20)));
	}

}


(new TaxTest)->run();
