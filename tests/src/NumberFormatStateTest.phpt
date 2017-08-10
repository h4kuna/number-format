<?php

namespace h4kuna\Number;

use Tester\TestCase,
	Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';


class NumberFormatStateTest extends TestCase
{

	public function testDefault()
	{
		$nf = new NumberFormatState();
		Assert::same('1,00', $nf->format(1));
		Assert::same('-1,00', $nf->format(-1));
		Assert::same('0,00', $nf->format(0));
		Assert::same('1,10', $nf->format(1.1));
		Assert::same('1,11', $nf->format(1.111));
		Assert::same('1,12', $nf->format(1.115));
		Assert::same('1' . $nf::NBSP . '000,00', $nf->format(1000));
		Assert::same('0,00', $nf->format(NULL));
	}


	public function testNamedParameters()
	{
		$nf = new NumberFormatState([
			'emptyValue' => '-',
			'zeroIsEmpty' => TRUE,
			'decimalPoint' => '.',
			'thousandsSeparator' => ',',
			'decimals' => 3,
			'intOnly' => 4,
			'zeroClear' => TRUE,
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
		$nf = new NumberFormatState(2, ',', ' ', TRUE, '-');
		Assert::same('-', $nf->format(0));
		Assert::same('-', $nf->format(0.0));
		Assert::same('-', $nf->format('0'));
		Assert::same('-', $nf->format('0.0'));
		Assert::same('-', $nf->format(NULL));

		$nf = new NumberFormatState(2, ',', ' ', TRUE, NULL);
		Assert::same('', $nf->format(NULL));
		Assert::same('', $nf->format(0));
		Assert::same('', $nf->format(0.0));
		Assert::same('', $nf->format('0'));
		Assert::same('', $nf->format('0.0'));

		$nf = new NumberFormatState(2, ',', ' ', FALSE, NULL);
		Assert::same('0,00', $nf->format(NULL));
	}


	public function testEmptyValue()
	{
		$nf = new NumberFormatState(2, ',', ' ', FALSE, '-');
		Assert::same('-', $nf->format(NULL));
		Assert::same('-', $nf->format(''));
		Assert::same('0,00', $nf->format(0));
		Assert::same('0,00', $nf->format(0.0));
		Assert::same('0,00', $nf->format('0'));
		Assert::same('0,00', $nf->format('0.0'));
	}


	public function testZeroClear()
	{
		$nf = new NumberFormatState(4, ',', ' ', NULL, FALSE, TRUE);
		Assert::same('0', $nf->format(0));
		Assert::same('0', $nf->format(0.0));
		Assert::same('0', $nf->format('0'));
		Assert::same('0', $nf->format('0.0'));
		Assert::same('1,1', $nf->format(1.100));
		Assert::same('1,112', $nf->format(1.11195));
	}


	public function testIntOnly()
	{
		$nf = new NumberFormatState(2, ',', ' ', NULL, FALSE, FALSE, 4);
		Assert::same('0,00', $nf->format(0));
		Assert::same('1,00', $nf->format(10000));
		Assert::same('1 000,35', $nf->format(10003500));
		Assert::same('1 001,00', $nf->format(10009999));
		Assert::same('-1,00', $nf->format(-10000));
	}

}


(new NumberFormatStateTest())->run();
