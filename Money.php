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
     *
     * @param type $number
     * @param type $vat
     */
    public function render($number = FALSE, $vat = NULL) {
        if ($vat === NULL) {
            $vat = $this->vat;
        } else {
            $vat = Vat::create($vat);
        }

        if ($number === FALSE) {
            $number = $this->getNumber();
        }

        switch ($this->vatIO) {
            case self::VAT_IN:
                $number /= $vat->getUpDecimal();
                break;
            case self::VAT_OUT:
                $number *= $vat->getUpDecimal();
                break;
        }

        return parent::render($number);
    }

}
