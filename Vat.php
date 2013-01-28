<?php

namespace h4kuna;

use Nette\Object;

/**
 * Tax minimu is 2%, maximum 99.99%, if you are out of range, this class is work bad
 */
class Vat extends Object {

    /** @var float */
    private $upDecimal;

    /** @var float */
    private $downDecimal;

    /** @var float */
    private $percent;

    /** @var array */
    private static $instance = array();

    /**
     * @param float $number
     */
    private function __construct($percent, $downDecimal, $upDecimal) {
        $this->downDecimal = $downDecimal;
        $this->upDecimal = $upDecimal;
        $this->percent = $percent;
    }

    public function getPercent() {
        return $this->percent;
    }

    public function getDownDecimal() {
        return $this->downDecimal;
    }

    public function getUpDecimal() {
        return $this->upDecimal;
    }

    static private function prepareKey($percent) {
        return (string) round($percent, 2);
    }

    /**
     * @param type $number
     * @return Vat
     * @throws \InvalidArgumentException
     */
    static function create($number) {
        $key = self::prepareKey($number);
        if (!is_numeric($number) || $number < 0) {
            throw new \InvalidArgumentException($number . ' $number must be a number and greater or equal then 0.');
        } elseif (isset(self::$instance[$key])) {
            return self::$instance[$key];
        }

        if (!$number) {
            $downDecimal = $upDecimal = 1.0;
            $percent = 0;
        } elseif ($number < 1) {
            $downDecimal = $number;
            $upDecimal = $number + 1;
            $percent = $number * 100;
        } elseif ($number < 2) {
            $downDecimal = $number - 1;
            $upDecimal = $number;
            $percent = $downDecimal * 100;
        } else {
            $downDecimal = $number * 0.01;
            $upDecimal = $downDecimal + 1;
            $percent = $number;
        }

        $key = self::prepareKey($percent);

        if (!isset(self::$instance[$key])) {
            if ($percent != 0 && ($percent < 2 || $percent >= 100)) {
                throw new \OutOfRangeException($percent . '% must be (2;100> || 0');
            }
            self::$instance[$key] = new static($percent, $downDecimal, $upDecimal);
        }

        return self::$instance[$key];
    }

}