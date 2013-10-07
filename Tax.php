<?php

namespace h4kuna;

use Nette\Object;

/**
 * Work class with price and vat
 *
 * @author Milan Matějček
 */
class Tax extends Object {

    const VAT_IN = 1;
    const VAT_OUT = 2;

    /** @var Vat */
    private $vat;

    /** @var int */
    private $vatIO;

    /** @var int */
    private $vatTemp;

    /**
     * @param int|float|string $vat
     */
    public function __construct($vat = 21) {
        $this->setVat($vat);
        $this->setVatIO(self::VAT_IN & self::VAT_OUT, self::VAT_IN & self::VAT_OUT);
    }

    /** @return Vat */
    public function getVat() {
        return $this->vat;
    }

    /**
     * Setup VAT
     *
     * @param int|float|string $v
     * @return Tax
     */
    public function setVat($v) {
        $this->vat = Vat::create($v);
        return $this;
    }

    /**
     * In = input number is texed
     *
     * @param bool $in
     * @param bool $out
     * @return Tax
     */
    public function setVatIO($in, $out) {
        $in = $in ? self::VAT_IN : 0;
        $out = $out ? self::VAT_OUT : 0;
        $this->vatTemp = $this->vatIO = $in | $out;
        return $this;
    }

    /**
     * @return Tax
     */
    public function vatOn() {
        $this->vatTemp = $this->vatIO | self::VAT_OUT;
        return $this;
    }

    /**
     * @return Tax
     */
    public function vatOff() {
        $this->vatTemp = $this->vatIO & ~self::VAT_OUT;
        return $this;
    }

    /**
     * Will number taxed?
     *
     * @return bool
     */
    public function isVatOn() {
        return $this->vatTemp > self::VAT_IN;
    }

    /**
     *
     * @return Tax
     */
    public function reset() {
        $this->vatTemp = $this->vatIO;
        return $this;
    }

    /**
     * This ignore settings IO
     *
     * @param int|float|string $number
     * @param int|float|string|Vat $vat
     * @return float
     */
    public function withVat($number, $vat = NULL) {
        $res = $this->vatOn()->taxation($number, $vat);
        $this->reset();
        return $res;
    }

    /**
     * This ignore settings IO
     *
     * @param int|float|string $number
     * @param int|float|string|Vat $vat
     * @return numeric
     */
    public function withoutVat($number, $vat = NULL) {
        $res = $this->vatOff()->taxation($number, $vat);
        $this->reset();
        return $res;
    }

    /**
     * Tax number how setup this class
     *
     * @param int|float|string $number
     * @param int|float|string|Vat $vat
     * @return int|float
     */
    public function taxation($number, $vat = NULL) {
        $both = self::VAT_IN | self::VAT_OUT;
        if (!$this->vatTemp || $this->vatTemp == $both) {
            return $number;
        }

        if ($vat === NULL) {
            $vat = $this->vat;
        } else {
            $vat = Vat::create($vat);
        }

        if (self::VAT_IN & $this->vatTemp) {
            return $number /= $vat->getUpDecimal();
        }

        return $number *= $vat->getUpDecimal();
    }

}
