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
     *
     * @param bool $in
     * @param bool $out
     * @return \h4kuna\Money
     */
    public function setVatIO($in, $out) {
        $in = $in ? self::VAT_IN : 0;
        $out = $out ? self::VAT_OUT : 0;
        $this->vatIO = $in | $out;
        return $this;
    }

    /**
     * @param \h4kuna\NumberFormat $number
     * @return \h4kuna\NumberFormat
     */
    public function vat(NumberFormat $number, $vat = NULL) {
        return $number->setNumber($this->taxation($number->getNumber(), $vat));
    }

    /**
     *
     * @param type $number
     * @param type $vat
     */
    public function render($number = FALSE, $vat = NULL) {
        if ($number === FALSE) {
            $number = $this->getNumber();
        } elseif ($number instanceof NumberFormat) {
            return $this->vat($number, $vat);
        }

        return parent::render($this->taxation($number, $vat));
    }

    private function taxation($number, $vat) {
        if ($vat === NULL) {
            $vat = $this->vat;
        } else {
            $vat = Vat::create($vat);
        }

        switch ($this->vatIO) {
            case self::VAT_IN:
                return $number /= $vat->getUpDecimal();
            case self::VAT_OUT:
                return $number *= $vat->getUpDecimal();
        }

        return $number;
    }

}
