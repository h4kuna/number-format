<?php declare(strict_types=1);

namespace h4kuna\Number\Tests;

use h4kuna\Number\NumberFormat;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
final class NumberFormatTest extends TestCase
{

	public function testDefault(): void
	{
		$nf = new NumberFormat();
		$nf->enableExtendFormat();
		Assert::same('1,00' . NumberFormat::NBSP . 'kg', $nf->format(1, null, 'kg'));
	}


	public function testMask(): void
	{
		$nf = new NumberFormat();
		$nf->enableExtendFormat('⎵1');
		Assert::same('kg1,00', $nf->format(1, null, 'kg'));

		$nf = new NumberFormat();
		$nf->enableExtendFormat('1-⎵');
		Assert::same('1,00-g', $nf->format(1, null, 'g'));
	}


	public function testVariableUnitMakeNothing(): void
	{
		$nf = new NumberFormat();
		Assert::same('1,655', $nf->format(1.654987, 3, 'kg'));
	}


	public function testVariableDecimalsUnit(): void
	{
		$nf = new NumberFormat(['nbsp' => false]);
		$nf->enableExtendFormat();
		Assert::same('1,655 kg', $nf->format(1.654987, 3, 'kg'));
	}


	public function testVariableDecimalsCUD(): void
	{
		$nf = new NumberFormat(['nbsp' => false]);
		$nf->enableExtendFormat('1 CUD');
		Assert::same('1,655 CUD', $nf->format(1.654987, 3));
	}


	public function testVariableDecimalsThirdParameterIsDisabled(): void
	{
		$nf = new NumberFormat(['nbsp' => false]);
		$nf->enableExtendFormat('1 CUD');
		Assert::same('1,655 CUD', $nf->format(1.654987, 3, 'be'));
	}


	public function testVariableDecimals(): void
	{
		$nf = new NumberFormat();
		Assert::same('1,655', $nf->format(1.654987, 3));
	}


	public function testNbsp(): void
	{
		$nf = new NumberFormat(['nbsp' => false]);
		$nf->enableExtendFormat();
		Assert::same('1,00 kg', $nf->format(1, null, 'kg'));
	}


	public function testNbspEveryWhere(): void
	{
		$nf = new NumberFormat(['thousandsSeparator' => ' ']);
		$nf->enableExtendFormat('1 ⎵ Foo');
		Assert::same(str_replace('%s', NumberFormat::NBSP, '1%s000,00%skg%sFoo'), $nf->format(1000, null, 'kg'));
	}


	public function testEmptyValue(): void
	{
		$nf = new NumberFormat(2, ',', ' ', true, false, '-');
		$nf->enableExtendFormat('1⎵');
		Assert::same('-kg', $nf->format(null, null, 'kg'));

		$nf = new NumberFormat(2, ',', ' ', true, false, '-');
		$nf->enableExtendFormat('1⎵', false);
		Assert::same('-', $nf->format(null, null, 'kg'));
	}

}

(new NumberFormatTest())->run();
