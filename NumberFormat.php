<?php

namespace h4kuna;

use Nette\Object;
use Nette\Utils\Html;

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

    const FLAG_NBSP = 1;
    const ZERO_CLEAR = 2;
    const IS_HTML = 4;
    const RENDER_SYNBOL = 8;
    const ZERO_IS_EMPTY = 16;

    /** @var string */
    private $thousand = ' ';

    /** @var int */
    private $decimal = 2;

    /** @var string */
    private $point = ',';

    /** @var number */
    private $number;

    /** @var string */
    private $mask = '1 S';

    /**
     * Internal helper
     *
     * @var array
     */
    private $workMask = array('', '');

    /** @var string */
    private $symbol;

    /** @var string */
    private $emptyValue = NULL;

    /** @var int */
    private $flag = self::FLAG_NBSP;

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
     * If input value is not number
     *
     * @param string $str
     * @return NumberFormat
     */
    public function setEmptyValue($str) {
        $this->emptyValue = $str;
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
        if (strip_tags($workMask) !== $workMask) {
            $this->flag |= self::IS_HTML;
        } else {
            $this->flag &= ~self::IS_HTML;
        }
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
        if ($bool) {
            return $this->onNbsp();
        }
        return $this->offNbsp();
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
        $this->onSymbol();
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
     * 
     * @param int $flag
     * @return NumberFormat
     * @throws NumberException
     */
    public function setFlag($flag) {
        $this->flag = $this->checkInt($flag);
        return $this;
    }

    /**
     * 
     * @param int $flag
     * @return NumberFormat
     */
    public function addFlag($flag) {
        $this->flag |= $this->checkInt($flag);
        return $this;
    }

    /**
     * 
     * @param int $flag
     * @return NumberFormat
     */
    public function removeFlag($flag) {
        $this->flag &= ~$this->checkInt($flag);
        return $this;
    }

    /**
     * 
     * @param int $flag
     * @return int
     * @throws NumberException
     */
    private function checkInt($flag) {
        if (!is_int($flag)) {
            throw new NumberException('Flag must be int.');
        }
        return $flag;
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
            return $this->emptyValue;
        }

        if ($decimal === NULL) {
            $decimal = $this->decimal;
        }

        if ($decimal < 0) {
            $number = round($number, $decimal);
            $decimal = 0;
        }

        if (!$number && $this->flag & self::ZERO_IS_EMPTY) {
            return $this->emptyValue;
        }

        $number = number_format($number, $decimal, $this->point, $this->thousand);

        if ($decimal > 0 && $this->flag & self::ZERO_CLEAR) {
            $number = rtrim(rtrim($number, '0'), $this->point);
        }

        if ($this->symbol && $this->flag & self::RENDER_SYNBOL) {
            $number = implode($number, $this->workMask);
        }

        if ($this->flag & self::IS_HTML) {
            return Html::el()->setHtml($this->replaceNbsp($number));
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
        if ($this->flag & self::FLAG_NBSP) {
            $val = str_replace(' ', self::NBSP, $val);
        }
        return $val;
    }

}

