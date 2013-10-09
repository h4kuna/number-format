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
class NumberFormat extends Object implements INumberFormat {

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

    /** @return string */
    public function getSymbol() {
        return $this->symbol;
    }

    /** @return int|float */
    public function getNumber() {
        return $this->number;
    }

    /**
     * Round, can be negative
     *
     * @param int $val
     * @return NumberFormat
     */
    public function setDecimal($val) {
        $this->decimal = $val;
        return $this;
    }

    /**
     * S = symbol, 1 = number
     *
     * @example '1 S', 'S 1'
     * @param string $mask
     * @return NumberFormat
     */
    public function setMask($mask) {
        if (strpos($mask, '1') === FALSE || strpos($mask, 'S') === FALSE) {
            throw new NumberException('The mask consists of 1 and S.');
        }

        $this->mask = $mask;
        $workMask = str_replace('S', $this->symbol, $mask);
        $this->workMask = explode('1', $this->replaceNbsp($workMask));
        return $this;
    }

    /**
     * @param int|float|string $number
     * @return NumberFormat
     */
    public function setNumber($number) {
        if (!is_numeric($number)) {
            $this->number = NULL;
            throw new NumberException('This is not number: ' . $number);
        }
        $this->number = $number;
        return $this;
    }

    /**
     * Replace non-break space
     *
     * @param bool $bool
     * @return NumberFormat
     */
    public function setNbsp($bool) {
        $this->nbsp = (bool) $bool;
        $this->setMask($this->mask);
        return $this;
    }

    /**
     * Decimal point
     *
     * @param string $val
     * @return NumberFormat
     */
    public function setPoint($val) {
        $this->point = $val;
        return $this;
    }

    /**
     * Set unit symbol currency, weight, length...
     *
     * @param string $symbol
     * @return NumberFormat
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
     * Thousand separator
     *
     * @param string $val
     * @return NumberFormat
     */
    public function setThousand($val) {
        $this->thousand = $val;
        return $this;
    }

    /**
     * Remove zero of right
     *
     * @param bool $val
     * @return NumberFormat
     */
    public function setZeroClear($bool) {
        $this->zeroClear = (bool) $bool;
        return $this;
    }

    /**
     * Render number
     *
     * @param int|float|string $number
     * @param int $decimal
     * @return NULL|string
     */
    public function render($number = NULL, $decimal = NULL) {
        try {
            $number = $this->setNumber($number)->number;
        } catch (NumberException $e) {
            return NULL;
        }

        if ($decimal === NULL) {
            $decimal = $this->decimal;
        }

        if ($decimal < 0) {
            $number = round($number, $decimal);
            $decimal = 0;
        }

        $number = number_format($number, $decimal, $this->point, $this->thousand);

        if ($decimal > 0 && $this->zeroClear) {
            $number = rtrim(rtrim($number, '0'), $this->point);
        }

        if ($this->symbol) {
            $number = implode($number, $this->workMask);
        }

        return $this->replaceNbsp($number);
    }

    /**
     * Render number
     *
     * @return string
     */
    public function __toString() {
        return (string) $this->render($this->number);
    }

    /**
     * Replace space to &nbsp; in utf-8
     *
     * @param string $val
     * @return string
     */
    private function replaceNbsp($val) {
        if ($this->nbsp) {
            $val = str_replace(' ', self::NBSP, $val);
        }
        return $val;
    }

}
