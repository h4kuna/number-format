<?php

namespace h4kuna\Number;

/**
 *
 * @author Milan Matějček
 */
interface INumberFormat {

    const FLAG_NBSP = 1;
    const ZERO_CLEAR = 2;
    const IS_HTML = 4;
    const RENDER_SYMBOL = 8;
    const ZERO_IS_EMPTY = 16;

    /** @var string utf-8 &nbsp; */
    const NBSP = "\xc2\xa0";

    /**
     * How render number.
     *
     * @param $num
     * @return string
     */
    public function render($num);

    /**
     * Not formated number.
     *
     * @return float|int|string
     */
    public function getNumber();

    /**
     * Get symbol.
     *
     * @return string
     */
    public function getSymbol();

    /**
     * Count of decimal number.
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
     * Set number.
     *
     * @param string
     * @return INumberFormat
     */
    public function setNumber($number);

    /**
     * Integers delimiter.
     *
     * @param string
     * @return INumberFormat
     */
    public function setPoint($str);

    /**
     * Set symbol - unit, currency.
     *
     * @param string
     * @return INumberFormat
     */
    public function setSymbol($str);

    /**
     * Thousand separator.
     *
     * @param string
     * @return INumberFormat
     */
    public function setThousand($str);

    /**
     * Setter of property.
     *
     * @param int $int
     * @return INumberFormat
     */
    public function setFlag($int);

    /** @return int */
    public function getFlag();
}
