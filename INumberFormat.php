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
     * Get symbol
     *
     * @return string
     */
    public function getSymbol();

    /**
     * Set symbol - unit, currency
     *
     * @param string
     * @return INumberFormat
     */
    public function setSymbol($str);

    /**
     * Count of decimal number
     *
     * @param int $int
     * @return INumberFormat
     */
    public function setDecimal($int);

    /**
     * Integers delimiter
     *
     * @param string
     * @return INumberFormat
     */
    public function setPoint($str);

    /**
     * Thousand separator
     *
     * @param string
     */
    public function setThousand($str);

    /**
     * Set number
     *
     * @param string
     * @return INumberFormat
     */
    public function setNumber($number);

    /**
     * Not formated number
     *
     * @return float|int|string
     */
    public function getNumber();
}
