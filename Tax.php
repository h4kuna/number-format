<?php

namespace h4kuna;

use Nette\Object;

/**
 * Description of NumberVat
 *
 * @author h4kuna
 */
class Tax extends Object {

    const VAT_IN = 1;
    const VAT_OUT = 2;

    /** @var Vat */
    private $vat;
    private $vatIO;
    private $vatTemp;

    public function __construct($vat = 21) {
        $this->setVat($vat);
        $this->setVatIO(self::VAT_IN & self::VAT_OUT, self::VAT_IN & self::VAT_OUT);
    }

    /** @return Vat */
    public function getVat() {
        return $this->vat;
    }

    /**
     * @param float|int $v
     * @return \h4kuna\Money
     */
    public function setVat($v) {
        $this->vat = Vat::create($v);
        return $this;
    }

    /**
     * in = input number is texed
     * @param bool $in
     * @param bool $out
     * @return \h4kuna\Money
     */
    public function setVatIO($in, $out) {
        $in = $in ? self::VAT_IN : 0;
        $out = $out ? self::VAT_OUT : 0;
        $this->vatTemp = $this->vatIO = $in | $out;
        return $this;
    }

    /**
     * @return \h4kuna\Money
     */
    public function vatOn() {
        $this->vatTemp = $this->vatIO | self::VAT_OUT;
        return $this;
    }

    /**
     * @return \h4kuna\Money
     */
    public function vatOff() {
        $this->vatTemp = $this->vatIO & ~self::VAT_OUT;
        return $this;
    }

    public function isVatOn() {
        return $this->vatTemp > self::VAT_IN;
    }

    public function reset() {
        $this->vatTemp = $this->vatIO;
        return $this;
    }

    /**
     * this ignore settings IO
     * @param float|int $number
     * @param float|int|Vat $vat
     * @return float|int
     */
    public function withVat($number, $vat = NULL) {
        $res = $this->vatOn()->taxation($number, $vat);
        $this->reset();
        return $res;
    }

    /**
     * this ignore settings IO
     * @param float|int $number
     * @param float|int|Vat $vat
     * @return float|int
     */
    public function withoutVat($number, $vat = NULL) {
        $res = $this->vatOff()->taxation($number, $vat);
        $this->reset();
        return $res;
    }

    /**
     *
     * @param float|int $number
     * @param float|int|Vat $vat
     * @param int $vatSetUp
     * @return float|int
     */
    public function taxation($number, $vat = NULL) {
        $both = self::VAT_IN | self::VAT_OUT;
        if (!$this->vatTemp || $this->vatTemp == $both) {
            return $number;
        }

        if ($vat === NULL) {
            $vat = $this->vat;
        } elseif (!($vat instanceof Vat)) {
            $vat = Vat::create($vat);
        }

        if (self::VAT_IN & $this->vatTemp) {
            return $number /= $vat->getUpDecimal();
        }

        return $number *= $vat->getUpDecimal();
    }

}
