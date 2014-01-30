<?php

namespace Tests;

use PHPUnit_Framework_TestCase;
use h4kuna\Vat;

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * @author Milan Matějček
 */
class VatTest extends PHPUnit_Framework_TestCase {

    public function testCreate() {
        // one instance
        Vat::create(21);
        Vat::create(0.21);
        $vat = Vat::create(1.21);
        $this->assertSame(1.21, $vat->getUpDecimal());
        $this->assertSame(0.21, $vat->getDownDecimal());
        $this->assertSame(21, $vat->getPercent());

        // second instance
        $vat = Vat::create(14);
        $this->assertSame(1.14, $vat->getUpDecimal());
        $this->assertSame(0.14, $vat->getDownDecimal());
        $this->assertSame(14, $vat->getPercent());

        // created instances
        $this->assertSame(2, count(Vat::getInstances()));
    }

}
