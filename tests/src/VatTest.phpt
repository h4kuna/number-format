<?php

namespace h4kuna\Number;

use Tester\TestCase,
    Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

/**
 * @author Milan Matějček
 */
class VatTest extends TestCase
{

    public function testCreate()
    {
        // one instance
        Vat::create(21);
        Vat::create(0.21);
        $vat = Vat::create(1.21);
        Assert::same(1.21, $vat->getUpDecimal());
        Assert::same(0.21, $vat->getDownDecimal());
        Assert::same(21, $vat->getPercent());

        // second instance
        $vat = Vat::create(14);
        Assert::equal(1.14, $vat->getUpDecimal());
        Assert::same(0.14, $vat->getDownDecimal());
        Assert::same(14, $vat->getPercent());

        // created instances
        Assert::same(2, count(Vat::getInstances()));
    }

}

$test = new VatTest();
$test->run();
