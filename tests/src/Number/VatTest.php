<?php declare(strict_types=1);

namespace h4kuna\Format\Tests\Number;

use h4kuna\Format\Number\Vat;
use h4kuna\Format\Tests\TestCase;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class VatTest extends TestCase
{

	public function testVat(): void
	{
		$vat = new Vat(20);
		Assert::same(120.0, $vat->with(100));
		Assert::same(100.0, $vat->without(120));
		Assert::same(20.0, $vat->diff(120));
		Assert::same(20.0, $vat->percentage);
		Assert::same(1.20, $vat->ratio);
		Assert::same(0.20, $vat->smallRatio);
		Assert::same('20', (string) $vat);
	}

}

(new VatTest)->run();
