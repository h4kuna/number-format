<?php


namespace h4kuna\Number;

use Tester\TestCase,
	Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';


class UnitFormatStateTest extends TestCase
{
	public function testDefault()
	{
		$nf = new UnitFormatState(new NumberFormatState());
		Assert::same('1,00' . NumberFormatState::NBSP . 'kg', $nf->format(1, 'kg'));
	}


	public function testMask()
	{
		$nf = new UnitFormatState(new NumberFormatState(), 'U1');
		Assert::same('kg1,00', $nf->format(1, 'kg'));

		$nf = new UnitFormatState(new NumberFormatState(), '1-U');
		Assert::same('1,00-g', $nf->format(1, 'g'));
	}


	/**
	 * @throws \h4kuna\Number\InvalidMaskException
	 */
	public function testInvalidMask()
	{
		new UnitFormatState(new NumberFormatState(), '1U-1U');
	}


	public function testNbsp()
	{
		$nf = new UnitFormatState(new NumberFormatState(), '1 U', TRUE, FALSE);
		Assert::same('1,00 kg', $nf->format(1, 'kg'));
	}


	public function testEmptyValue()
	{
		$nf = new UnitFormatState(new NumberFormatState(2, ',', NULL, FALSE, '-'), '1U');
		Assert::same('-kg', $nf->format(NULL, 'kg'));

		$nf = new UnitFormatState(new NumberFormatState(2, ',', NULL, FALSE, '-'), '1U', FALSE);
		Assert::same('-', $nf->format(NULL, 'kg'));
	}
}


(new UnitFormatStateTest())->run();