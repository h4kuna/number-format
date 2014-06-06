<?php

namespace Tests;

use h4kuna\INumberFormat;
use h4kuna\NumberFormat;
use Nette\Utils\Html;
use PHPUnit_Framework_TestCase;

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * @author Milan Matějček
 */
class NumberFormatTest extends PHPUnit_Framework_TestCase {

    /**
     *
     * @return NumberFormat
     */
    private function createNumberFormat() {
        $number = new NumberFormat('CZK');
        $number->setNumber(1234.567899);
        $number->offNbsp();
        return $number;
    }

    public function testDecimal() {
        $number = $this->createNumberFormat();
        $number->setDecimal(1);
        $this->assertEquals('1 234,6 CZK', (string) $number);

        $number->setDecimal(-2);
        $this->assertEquals('1 200 CZK', (string) $number);
    }

    public function testThousand() {
        $number = $this->createNumberFormat();
        $number->setThousand('-');
        $this->assertEquals('1-234,57 CZK', (string) $number);
    }

    public function testMask() {
        $number = $this->createNumberFormat();
        $number->setMask('S 1');
        $this->assertEquals('CZK 1 234,57', (string) $number);

        $number->setMask('<span>S</span> 1');
        $this->assertEquals('<span>CZK</span> 1 234,57', (string) $number);

        $this->assertSame(TRUE, $number->render($number->getNumber()) instanceof Html);
    }

    public function testPoint() {
        $number = $this->createNumberFormat();
        $number->setPoint(';');
        $this->assertEquals('1 234;57 CZK', (string) $number);
    }

    public function testSymbol() {
        $number = $this->createNumberFormat();
        $number->setSymbol('Kč');
        $this->assertEquals('1 234,57 Kč', (string) $number);
    }

    public function testRender() {
        $number = $this->createNumberFormat();
        $this->assertEquals(NULL, $number->render());

        $this->assertEquals('1 234,12 CZK', $number->render(1234.123, 2));
        $this->assertEquals('1 230 CZK', $number->render(1234.123, -1));
    }

    public function testNbsp() {
        $number = $this->createNumberFormat();
        $number->onNbsp();
        $this->assertEquals('1' . INumberFormat::NBSP . '234,57' . INumberFormat::NBSP . 'CZK', (string) $number);
    }

    public function testZeroClear() {
        $number = $this->createNumberFormat();
        $number->addFlag(NumberFormat::ZERO_CLEAR);
        $number->setDecimal(5);
        $this->assertEquals('1 234,5679 CZK', (string) $number);
    }

    public function testEmptyValue() {
        $number = $this->createNumberFormat();
        $number->setEmptyValue('-');
        $this->assertEquals('-', (string) $number->render(NULL));
        $this->assertEquals('-', (string) $number->render(''));
    }

    public function testZeroIsEmpty() {
        $number = $this->createNumberFormat();
        $number->setEmptyValue('-');
        $number->onZeroIsEmpty();
        $this->assertEquals('-', (string) $number->render(0.0));
        $this->assertEquals('-', (string) $number->render('0'));
        $number->removeFlag(NumberFormat::ZERO_IS_EMPTY);
        $this->assertEquals('0,00 CZK', (string) $number->render('0'));
    }

    public function testRenderSymbol() {
        $number = $this->createNumberFormat();
        $this->assertEquals('50,00 CZK', (string) $number->render(50));
        $number->offSymbol();
        $number->onZeroClear();
        $this->assertEquals('50', (string) $number->render(50));
        $number->onSymbol();
        $this->assertEquals('50 CZK', (string) $number->render(50));
    }

}
