<?php

namespace h4kuna;

/**
 *
 * @author Milan Matějček
 */
interface INumberFormat {

    /** @var string utf-8 &nbsp; */
    const NBSP = "\xc2\xa0";

    /**
     * How render number
     *
     * @param $num
     * @return string
     */
    public function render($num);

    /**
     * Not formated number
     *
     * @return float|int|string
     */
    public function getNumber();

    /**
     * Get symbol
     *
     * @return string
     */
    public function getSymbol();

    /**
     * Count of decimal number
     *
     * @param int $int
     * @return INumberFormat
     */
    public function setDecimal($int);

    /**
     * S = symbol, 1 = number
     *
     * @example '1 S', 'S 1'
     * @param string $mask
     * @return NumberFormat
     */
    public function setMask($mask);

    /**
     * Set number
     *
     * @param string
     * @return INumberFormat
     */
    public function setNumber($number);

    /**
     * Replace non-break space
     *
     * @param bool $bool
     * @return NumberFormat
     */
    public function setNbsp($bool);

    /**
     * Integers delimiter
     *
     * @param string
     * @return INumberFormat
     */
    public function setPoint($str);

    /**
     * Set symbol - unit, currency
     *
     * @param string
     * @return INumberFormat
     */
    public function setSymbol($str);

    /**
     * Thousand separator
     *
     * @param string
     */
    public function setThousand($str);

    /**
     * Remove zero of right
     *
     * @param bool $val
     * @return NumberFormat
     */
    public function setZeroClear($bool);
}
