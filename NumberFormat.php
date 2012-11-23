<?php

namespace h4kuna;

use Nette\Object;

/**
 * @property-write $number
 * @property-write $thousand
 * @property-write $decimal
 * @property-write $point
 * @property-write $nbsp
 * @property-write $zeroClear
 * @property-write $mask
 * @property-write $symbol
 */
class NumberFormat extends Object {
    /** @var string utf-8 &nbsp; */
    const NBSP = "\xc2\xa0";

    /** @var string */
    private $thousand = ' ';

    /** @var int */
    private $decimal = 2;

    /** @var string */
    private $point = ',';

    /** @var bool */
    private $nbsp = TRUE;

    /** @var bool */
    private $zeroClear = FALSE;

    /** @var number */
    private $number;

    /** @var string */
    private $mask = '1 S';

    /**
     * internal helper
     * @var array
     */
    private $workMask = array('', '');

    /** @var string */
    private $symbol;

    /**
     * @param string $symbol
     */
    public function __construct($symbol = NULL) {
        $this->setSymbol($symbol);
    }

    public function getSymbol() {
        return $this->symbol;
    }

    /**
     * round, can be negative
     * @param int $val
     * @return \h4kuna\NumberFormat
     */
    public function setDecimal($val) {
        $this->decimal = $val;
        return $this;
    }

    /**
     * @example '1 S', 'S 1'
     * S = symbol, 1 = number
     * @param string $mask
     * @return \h4kuna\NumberFormat
     */
    public function setMask($mask) {
        if (strpos($mask, '1') === FALSE || strpos($mask, 'S') === FALSE) {
            throw new \RuntimeException('The mask consists of 1 and S.');
        }

        $this->mask = $mask;
        $this->workMask = explode('1', str_replace('S', $this->symbol, $mask));
        return $this;
    }

    /**
     * @param int|float|string $number
     * @return \h4kuna\NumberFormat
     */
    public function setNumber($number) {
        $this->number = $number;
        return $this;
    }

    /**
     * @param bool $val
     * @return \h4kuna\NumberFormat
     */
    public function setNbsp($val) {
        $this->nbsp = (bool) $val;
        return $this;
    }

    /**
     * decimal point
     * @param string $val
     * @return \h4kuna\NumberFormat
     */
    public function setPoint($val) {
        $this->point = $val;
        return $this;
    }

    /**
     * @param string $symbol
     * @return \h4kuna\NumberFormat
     */
    public function setSymbol($symbol) {
        if ($symbol == $this->symbol) {
            return $this;
        }

        $this->symbol = $symbol;

        if ($symbol !== NULL) {
            $this->setMask($this->mask);
        }
        return $this;
    }

    /**
     * thousand separator
     * @param string $val
     * @return \h4kuna\NumberFormat
     */
    public function setThousand($val) {
        $this->thousand = $val;
        return $this;
    }

    /**
     * remove zero of right
     * @param bool $val
     * @return \h4kuna\NumberFormat
     */
    public function setZeroClear($val) {
        $this->zeroClear = (bool) $val;
        return $this;
    }

    public function render($number = FALSE, $decimal = NULL) {
        if ($number === FALSE) {
            $number = $this->number;
        }

        if (!is_numeric($number)) {
            return NULL;
        }

        if ($decimal === NULL) {
            $decimal = $this->decimal;
        }

        if ($decimal < 0) {
            $number = count($number, $decimal);
            $decimal = 0;
        }

        $num = number_format($number, $decimal, $this->point, $this->thousand);

        if ($decimal > 0 && $this->zeroClear) {
            $num = rtrim(rtrim($num, '0'), $this->point);
        }

        if ($this->symbol) {
            $num = implode($num, $this->workMask);
        }

        if ($this->nbsp) {
            $num = str_replace(' ', self::NBSP, $num);
        }

        return $num;
    }

    public function __toString() {
        return $this->render();
    }

}
