<?php declare(strict_types=1);

namespace h4kuna\Number;

use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../bootstrap.php';

class NumberFormatStateTest extends TestCase
{

	public function testDefault()
	{
		$nf = new NumberFormatState();
		Assert::same('1,00', $nf->format('1'));
		Assert::same('1,00', $nf->format(1));
		Assert::same('-1,00', $nf->format(-1));
		Assert::same('0,00', $nf->format(0));
		Assert::same('1,10', $nf->format(1.1));
		Assert::same('1,11', $nf->format(1.111));
		Assert::same('1,12', $nf->format(1.115));
		Assert::same('1' . $nf::NBSP . '000,00', $nf->format(1000));
		Assert::same('0,00', $nf->format(null));
	}


	public function testNamedParameters()
	{
		$nf = new NumberFormatState([
			'emptyValue' => '-',
			'zeroIsEmpty' => true,
			'decimalPoint' => '.',
			'thousandsSeparator' => ',',
			'decimals' => 3,
			'intOnly' => 4,
			'zeroClear' => true,
		]);
		Assert::same('1,000.01', $nf->format(10000100));
		Assert::same('1,000.001', $nf->format(10000011));
		Assert::same('-', $nf->format(0));
	}


	public function testDecimal()
	{
		$nf = new NumberFormatState(1);
		Assert::same('1,0', $nf->format(1));

		$nf = new NumberFormatState(0);
		Assert::same('1', $nf->format(1));

		$nf = new NumberFormatState(-1);
		Assert::same('10', $nf->format(11));
		Assert::same('20', $nf->format(15));
		Assert::same('20', $nf->format('15'));
	}


	public function testFloor()
	{
		$nf = new NumberFormatState(['decimals' => 1, 'round' => NumberFormatState::ROUND_BY_FLOOR]);
		Assert::same('1,1', $nf->format(1.15));
		Assert::same('1,1', $nf->format(1.14));
		Assert::same('1,2', $nf->format(1.21));

		$nf = new NumberFormatState(['decimals' => 0, 'round' => NumberFormatState::ROUND_BY_FLOOR]);
		Assert::same('1', $nf->format(1));
		Assert::same('1', $nf->format(1.5));
		Assert::same('1', $nf->format(1.4));
		Assert::same('0', $nf->format(0.9));

		$nf = new NumberFormatState(['decimals' => -1, 'round' => NumberFormatState::ROUND_BY_FLOOR]);
		Assert::same('10', $nf->format(11));
		Assert::same('10', $nf->format(15));
		Assert::same('20', $nf->format('20'));
	}


	public function testCeil()
	{
		$nf = new NumberFormatState(['decimals' => 1, 'round' => NumberFormatState::ROUND_BY_CEIL]);
		Assert::same('1,1', $nf->format(1.1));
		Assert::same('1,2', $nf->format(1.15));
		Assert::same('1,2', $nf->format(1.14));
		Assert::same('1,3', $nf->format(1.21));

		$nf = new NumberFormatState(['decimals' => 0, 'round' => NumberFormatState::ROUND_BY_CEIL]);
		Assert::same('1', $nf->format(1));
		Assert::same('2', $nf->format(1.5));
		Assert::same('2', $nf->format(1.4));
		Assert::same('1', $nf->format(0.9));

		$nf = new NumberFormatState(['decimals' => -1, 'round' => NumberFormatState::ROUND_BY_CEIL]);
		Assert::same('20', $nf->format(11));
		Assert::same('20', $nf->format(15));
		Assert::same('20', $nf->format('20'));
	}


	public function testDecimalPointThousandSep()
	{
		$nf = new NumberFormatState(2, '.', ',');
		Assert::same('1,000.00', $nf->format(1000));

		$nf = new NumberFormatState(2, NumberFormatState::NBSP . ',');
		Assert::same('1' . $nf::NBSP . ',00', $nf->format(1));
	}


	public function testZeroIsEmpty()
	{
		$nf = new NumberFormatState(2, ',', ' ', true, '-');
		Assert::same('-', $nf->format(0));
		Assert::same('-', $nf->format(0.0));
		Assert::same('-', $nf->format('0'));
		Assert::same('-', $nf->format('0.0'));
		Assert::same('-', $nf->format(null));

		$nf = new NumberFormatState(2, ',', ' ', true, null);
		Assert::same('', $nf->format(null));
		Assert::same('', $nf->format(0));
		Assert::same('', $nf->format(0.0));
		Assert::same('', $nf->format('0'));
		Assert::same('', $nf->format('0.0'));

		$nf = new NumberFormatState(2, ',', ' ', false, null);
		Assert::same('0,00', $nf->format(null));
	}


	public function testEmptyValue()
	{
		$nf = new NumberFormatState(2, ',', ' ', false, '-');
		Assert::same('-', $nf->format(null));
		Assert::same('-', $nf->format(''));
		Assert::same('0,00', $nf->format(0));
		Assert::same('0,00', $nf->format(0.0));
		Assert::same('0,00', $nf->format('0'));
		Assert::same('0,00', $nf->format('0.0'));
	}


	public function testZeroClear()
	{
		$nf = new NumberFormatState(4, ',', ' ', false, null, true);
		Assert::same('0', $nf->format(0));
		Assert::same('0', $nf->format(0.0));
		Assert::same('0', $nf->format('0'));
		Assert::same('0', $nf->format('0.0'));
		Assert::same('1,1', $nf->format(1.100));
		Assert::same('1,112', $nf->format(1.11195));
	}


	public function testIntOnly()
	{
		$nf = new NumberFormatState(2, ',', ' ', false, null, false, 4);
		Assert::same('0,00', $nf->format(0));
		Assert::same('1,00', $nf->format(10000));
		Assert::same('1 000,35', $nf->format(10003500));
		Assert::same('1 001,00', $nf->format(10009999));
		Assert::same('-1,00', $nf->format(-10000));
	}


	public function testIntOnlyZero()
	{
		$nf = new NumberFormatState(2, ',', ' ', false, null, false, 0);
		Assert::same('0,00', $nf->format(0));
		Assert::same('10 000,00', $nf->format(10000));
		Assert::same('10 003 500,00', $nf->format(10003500));
	}

}

(new NumberFormatStateTest())->run();
