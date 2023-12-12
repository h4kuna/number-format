<?php declare(strict_types=1);

namespace h4kuna\Format\Tests\Number;

use h4kuna\Format\Number\Discount;
use h4kuna\Format\Tests\TestCase;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class DiscountTest extends TestCase
{

	public function testDiscount(): void
	{
		$discount = new Discount(20);
		Assert::same(120.0, $discount->with(100));
		Assert::same(96.0, $discount->deduct(120));
		Assert::same(24.0, $discount->diff(120));
		Assert::same(20.0, $discount->diffWith(100));
		Assert::same(20.0, $discount->percentage);
		Assert::same(1.20, $discount->ratio);
		Assert::same(0.20, $discount->smallRatio);
		Assert::same('20', (string) $discount);
	}

}

(new DiscountTest)->run();
