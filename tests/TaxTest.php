<?php

namespace Tests;

use h4kuna\Tax;
use h4kuna\Vat;
use PHPUnit_Framework_TestCase;

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * @author Milan Matějček
 */
class TaxTest extends PHPUnit_Framework_TestCase {

    public function testTaxation() {
        $tax = $this->createTax();
        $tax->setVatIO(TRUE, TRUE);
        $this->assertSame(100, $tax->taxation(100));

        // IN is without vat and OUT is with vat, 100 * 1.21 = 121
        $tax->setVatIO(FALSE, TRUE);
        $this->assertEquals(114, $tax->taxation(100, 14));
        $this->assertEquals(114, $tax->taxation(100, 1.14));
        $this->assertEquals(114, $tax->taxation(100, 0.14));
        $this->assertEquals(114, $tax->taxation(100, Vat::create(14)));
        $this->assertEquals(121, $tax->taxation(100));

        $tax->setVatIO(TRUE, FALSE);
        $this->assertEquals(82.644628, round($tax->taxation(100), 6));

        $tax->setVatIO(FALSE, FALSE);
        $this->assertSame(100, $tax->taxation(100));
    }

    public function testOnOffVat() {
        $tax = $this->createTax();
        $tax->setVatIO(TRUE, FALSE);
        $tax->vatOn();
        $this->assertSame(100, $tax->taxation(100));

        $tax->reset();
        $this->assertEquals(82.644628, round($tax->taxation(100), 6));

        $tax->setVatIO(FALSE, TRUE);
        $tax->vatOff();
        $this->assertSame(100, $tax->taxation(100));

        $tax->reset();
        $this->assertEquals(121, $tax->taxation(100));
    }

    public function testIsVatOn() {
        $tax = $this->createTax();
        $this->assertSame(FALSE, $tax->isVatOn());

        $tax->vatOn();
        $this->assertSame(TRUE, $tax->isVatOn());

        $tax->setVatIO(FALSE, TRUE);
        $this->assertSame(TRUE, $tax->isVatOn());

        $tax->setVatIO(FALSE, FALSE);
        $this->assertSame(FALSE, $tax->isVatOn());
    }

    public function testWithOutVat() {
        $tax = $this->createTax();
        $tax->setVatIO(FALSE, FALSE);
        $this->assertEquals(121, $tax->withVat(100));

        $tax->setVatIO(TRUE, TRUE);
        $this->assertEquals(82.644628, round($tax->withoutVat(100), 6));
    }


    /**
     *
     * @return Tax
     */
    private function createTax() {
        $tax = new Tax(21);
        return $tax;
    }

}
