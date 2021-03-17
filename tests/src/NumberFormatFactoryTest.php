<?php declare(strict_types=1);

namespace h4kuna\Number;

use Tester\TestCase;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

class NumberFormatFactoryTest extends TestCase
{

	public function testUnit(): void
	{
		$nff = new NumberFormatFactory();
		$uf = $nff->createUnit('1U');
		Assert::same('1,00kg', $uf->format(1, 'kg'));

		$uf = $nff->createUnit(['mask' => '1U', 'decimalPoint' => '.']);
		Assert::same('1.00kg', $uf->format(1, 'kg'));
	}


	public function testNumber(): void
	{
		$nff = new NumberFormatFactory();
		$nf = $nff->createNumber(2, '.');
		Assert::same('1.00', $nf->format(1));
	}


	public function testUnitPersistent(): void
	{
		$nff = new NumberFormatFactory();
		$cf = $nff->createUnitPersistent('CZK', ['decimalPoint' => '.', 'nbsp' => false, 'decimals' => 1]);
		Assert::same('1.0 CZK', $cf->format(1));
	}
}

(new NumberFormatFactoryTest())->run();
