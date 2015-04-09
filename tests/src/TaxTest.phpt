<?php

namespace h4kuna\Number;

use Nette\Utils\Html,
    Tester\TestCase,
    Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

/**
 * @author Milan Matějček
 */
class TaxTest extends TestCase
{

    /** @var Tax */
    private $tax;

    protected function setUp()
    {
        $this->tax = $this->createTax();
    }

    public function testTaxation()
    {

        $this->tax->setVatIO(TRUE, TRUE);
        Assert::same(100, $this->tax->taxation(100));

        // IN is without vat and OUT is with vat, 100 * 1.21 = 121
        $this->tax->setVatIO(FALSE, TRUE);
        Assert::equal(114.0, $this->tax->taxation(100, 14));
        Assert::equal(114.0, $this->tax->taxation(100, 1.14));
        Assert::equal(114.0, $this->tax->taxation(100, 0.14));
        Assert::equal(114.0, $this->tax->taxation(100, Vat::create(14)));
        Assert::equal(121.0, $this->tax->taxation(100));

        $this->tax->setVatIO(TRUE, FALSE);
        Assert::equal(82.644628, round($this->tax->taxation(100), 6));

        $this->tax->setVatIO(FALSE, FALSE);
        Assert::same(100, $this->tax->taxation(100));
    }

    public function testOnOffVat()
    {
        $this->tax->setVatIO(TRUE, FALSE);
        $this->tax->vatOn();
        Assert::same(100, $this->tax->taxation(100));

        $this->tax->reset();
        Assert::equal(82.644628, round($this->tax->taxation(100), 6));

        $this->tax->setVatIO(FALSE, TRUE);
        $this->tax->vatOff();
        Assert::same(100, $this->tax->taxation(100));

        $this->tax->reset();
        Assert::equal(121.0, $this->tax->taxation(100));
    }

    public function testIsVatOn()
    {

        Assert::same(FALSE, $this->tax->isVatOn());

        $this->tax->vatOn();
        Assert::same(TRUE, $this->tax->isVatOn());

        $this->tax->setVatIO(FALSE, TRUE);
        Assert::same(TRUE, $this->tax->isVatOn());

        $this->tax->setVatIO(FALSE, FALSE);
        Assert::same(FALSE, $this->tax->isVatOn());
    }

    public function testWithOutVat()
    {

        $this->tax->setVatIO(FALSE, FALSE);
        Assert::equal(121.0, $this->tax->withVat(100));

        $this->tax->setVatIO(TRUE, TRUE);
        Assert::equal(82.644628, round($this->tax->withoutVat(100), 6));
    }

    /**
     *
     * @return Tax
     */
    private function createTax()
    {
        $tax = new Tax(21);
        return $tax;
    }

}


$test = new TaxTest;
$test->run();