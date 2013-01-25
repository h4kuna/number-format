<?php

namespace h4kuna;

/**
 * Description of NumberVat
 *
 * @author h4kuna
 */
class Money extends NumberFormat {

    const VAT_IN = 1;
    const VAT_OUT = 2;

    /** @var Vat */
    private $vat;
    private $vatIO = self::VAT_OUT;
    private $vatTemp = self::VAT_OUT;

    public function __construct($symbol = 'Kč', $vat = 21) {
        parent::__construct($symbol);
        $this->setVat($vat);
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

    /**
     * this ignore settings IO
     * @param float|int|NumberFormat $number
     * @param float|int|Vat $vat
     * @return float|int|NumberFormat
     */
    public function withVat($number = FALSE, $vat = NULL) {
        return $this->taxation($number, $vat, $this->vatTemp | self::VAT_OUT);
    }

    /**
     * this ignore settings IO
     * @param float|int|NumberFormat $number
     * @param float|int|Vat $vat
     * @return float|int|NumberFormat
     */
    public function withoutVat($number = FALSE, $vat = NULL) {
        return $this->taxation($number, $vat, $this->vatTemp & ~self::VAT_OUT);
    }

    /**
     * @param float|int|NumberFormat $number
     * @param float|int|Vat $vat
     * @return string|NumberFormat
     */
    public function render($number = FALSE, $vat = NULL) {
        if ($number instanceof NumberFormat) {
            return $this->taxation($number, $vat);
        }
        return parent::render($this->taxation($number, $vat));
    }

    /**
     *
     * @param float|int|NumberFormat $number
     * @param float|int|Vat $vat
     * @param int $vatSetUp
     * @return float|int|NumberFormat
     */
    private function taxation($number, $vat = NULL, $vatSetUp = NULL) {
        if ($vat === NULL) {
            $vat = $this->vat;
        } elseif (!($vat instanceof Vat)) {
            $vat = Vat::create($vat);
        }
        $numberFormat = NULL;
        if ($number === FALSE) {
            $number = $this->getNumber();
        } elseif ($number instanceof NumberFormat) {
            $numberFormat = $number;
            $number = $number->getNumber();
        }


        if ($vatSetUp === NULL) {
            $vatSetUp = $this->vatTemp;
        }

        switch ($vatSetUp) {
            case self::VAT_IN:
                $number /= $vat->getUpDecimal();
                break;
            case self::VAT_OUT:
                $number *= $vat->getUpDecimal();
                break;
        }
        if ($numberFormat) {
            return $numberFormat->setNumber($number);
        }
        return $number;
    }

}
