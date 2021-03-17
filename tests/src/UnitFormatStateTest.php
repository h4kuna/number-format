<?php declare(strict_types=1);

namespace h4kuna\Number;

use Tester\TestCase;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

class UnitFormatStateTest extends TestCase
{

	public function testDefault(): void
	{
		$nf = new UnitFormatState(new NumberFormatState());
		Assert::same('1,00' . NumberFormatState::NBSP . 'kg', $nf->format(1, 'kg'));
	}


	public function testMask(): void
	{
		$nf = new UnitFormatState(new NumberFormatState(), 'U1');
		Assert::same('kg1,00', $nf->format(1, 'kg'));

		$nf = new UnitFormatState(new NumberFormatState(), '1-U');
		Assert::same('1,00-g', $nf->format(1, 'g'));
	}


	/**
	 * @throws \h4kuna\Number\Exceptions\InvalidMask
	 */
	public function testInvalidMask(): void
	{
		new UnitFormatState(new NumberFormatState(), '1U-1U');
	}


	public function testNbsp(): void
	{
		$nf = new UnitFormatState(new NumberFormatState(), '1 U', true, false);
		Assert::same('1,00 kg', $nf->format(1, 'kg'));
	}


	public function testEmptyValue(): void
	{
		$nf = new UnitFormatState(new NumberFormatState(2, ',', null, false, '-'), '1U');
		Assert::same('-kg', $nf->format(null, 'kg'));

		$nf = new UnitFormatState(new NumberFormatState(2, ',', null, false, '-'), '1U', false);
		Assert::same('-', $nf->format(null, 'kg'));
	}
}

(new UnitFormatStateTest())->run();
