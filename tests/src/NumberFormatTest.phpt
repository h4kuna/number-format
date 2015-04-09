<?php

namespace h4kuna\Number;

use Nette\Utils\Html,
    Tester\TestCase,
    Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

/**
 * @author Milan Matějček
 */
class NumberFormatTest extends TestCase
{

    /** @var NumberFormat */
    private $number;

    /**
     *
     * @return NumberFormat
     */
    protected function setUp()
    {
        parent::setUp();
        $this->number = $this->createNumberFormat();
    }

    private function createNumberFormat()
    {
        $number = new NumberFormat('CZK');
        $number->setNumber(1234.567899);
        $number->off(INumberFormat::FLAG_NBSP);
        return $number;
    }

    public function testDecimal()
    {
        $this->number->setDecimal(1);
        Assert::equal('1 234,6 CZK', (string) $this->number);

        $this->number->setDecimal(-2);
        Assert::equal('1 200 CZK', (string) $this->number);
    }

    public function testThousand()
    {
        $this->number->setThousand('-');
        Assert::equal('1-234,57 CZK', (string) $this->number);
    }

    public function testMask()
    {

        $this->number->setMask('S 1');
        Assert::equal('CZK 1 234,57', (string) $this->number);

        $this->number->setMask('<span>S</span> 1');
        Assert::equal('<span>CZK</span> 1 234,57', (string) $this->number);

        Assert::same(TRUE, $this->number->render($this->number->getNumber()) instanceof Html);
    }

    public function testPoint()
    {

        $this->number->setPoint(';');
        Assert::equal('1 234;57 CZK', (string) $this->number);
    }

    public function testSymbol()
    {

        $this->number->setSymbol('Kč');
        Assert::equal('1 234,57 Kč', (string) $this->number);
    }

    public function testRender()
    {

        Assert::equal(NULL, $this->number->render());

        Assert::equal('1 234,12 CZK', $this->number->render(1234.123, 2));
        Assert::equal('1 230 CZK', $this->number->render(1234.123, -1));
    }

    public function testNbsp()
    {

        $this->number->on(INumberFormat::FLAG_NBSP);
        Assert::equal('1' . INumberFormat::NBSP . '234,57' . INumberFormat::NBSP . 'CZK', (string) $this->number);
    }

    public function testZeroClear()
    {

        $this->number->on(NumberFormat::ZERO_CLEAR);
        $this->number->setDecimal(5);
        Assert::equal('1 234,5679 CZK', (string) $this->number);
    }

    public function testEmptyValue()
    {

        $this->number->setEmptyValue('-');
        Assert::equal('-', (string) $this->number->render(NULL));
        Assert::equal('-', (string) $this->number->render(''));
    }

    public function testZeroIsEmpty()
    {

        $this->number->setEmptyValue('-');
        $this->number->on(INumberFormat::ZERO_IS_EMPTY);
        Assert::equal('-', (string) $this->number->render(0.0));
        Assert::equal('-', (string) $this->number->render('0'));
        $this->number->off(NumberFormat::ZERO_IS_EMPTY);
        Assert::equal('0,00 CZK', (string) $this->number->render('0'));
    }

    public function testRenderSymbol()
    {

        Assert::equal('50,00 CZK', (string) $this->number->render(50));
        $this->number->off(INumberFormat::RENDER_SYMBOL);
        $this->number->on(INumberFormat::ZERO_CLEAR);
        Assert::equal('50', (string) $this->number->render(50));
        $this->number->on(INumberFormat::RENDER_SYMBOL);
        Assert::equal('50 CZK', (string) $this->number->render(50));
    }

}

$test = new NumberFormatTest();
$test->run();
