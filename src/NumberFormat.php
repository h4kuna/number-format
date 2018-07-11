<?php

namespace h4kuna\Number;

use Nette\SmartObject,
    Nette\Utils\Html;

/**
 * @property float|int $number
 * @property $int $flag
 * @property string $symbol
 * @property-write string $thousand
 * @property-write string $decimal
 * @property-write string $point
 * @property-write string $mask
 * @property-write string $emptyValue
 */
class NumberFormat implements INumberFormat
{
	use SmartObject;

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
    public function __construct($symbol = NULL)
    {
        $this->setSymbol($symbol);
    }

    /**
     *
     * @param int $flag
     * @return NumberFormat
     */
    public function on($flag)
    {
        $this->flag |= $this->checkInt($flag);
        return $this->onChangeFlag($flag);
    }

    /**
     *
     * @param int $flag
     * @return NumberFormat
     */
    public function off($flag)
    {
        $this->flag &= ~$this->checkInt($flag);
        return $this->onChangeFlag($flag);
    }

    public function getFlag()
    {
        return $this->flag;
    }

    /** @return int|float */
    public function getNumber()
    {
        return $this->number;
    }

    /** @return string */
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * Round, can be negative
     *
     * @param int $val
     * @return NumberFormat
     */
    public function setDecimal($val)
    {
        $this->decimal = $val;
        return $this;
    }

    /**
     * If input value is not number
     *
     * @param string $str
     * @return NumberFormat
     */
    public function setEmptyValue($str)
    {
        $this->emptyValue = $str;
        return $this;
    }

    /**
     *
     * @param int $flag
     * @return NumberFormat
     * @throws NumberException
     */
    public function setFlag($flag)
    {
        $this->flag = $this->checkInt($flag);
        return $this->onChangeFlag($flag);
    }

    /**
     * S = symbol, 1 = number
     *
     * @example '1 S', 'S 1'
     * @param string $mask
     * @return NumberFormat
     */
    public function setMask($mask)
    {
        if (strpos($mask, '1') === FALSE || strpos($mask, 'S') === FALSE) {
            throw new NumberException('The mask consists of 1 and S.');
        }

        $this->mask = $mask;
        $this->setWorkingMask($mask);
        return $this;
    }

    /**
     * @param int|float|string $number
     * @return NumberFormat
     */
    public function setNumber($number)
    {
        if (!is_numeric($number)) {
            $this->number = NULL;
            throw new NumberException('This is not number: ' . $number);
        }
        $this->number = $number;
        return $this;
    }

    /**
     * Decimal point
     *
     * @param string $val
     * @return NumberFormat
     */
    public function setPoint($val)
    {
        $this->point = $val;
        return $this;
    }

    /**
     * Set unit symbol currency, weight, length...
     *
     * @param string $symbol
     * @return NumberFormat
     */
    public function setSymbol($symbol)
    {
        if ($symbol == $this->symbol) {
            return $this;
        }

        $this->symbol = $symbol;

        if ($symbol !== NULL) {
            $this->on(self::RENDER_SYMBOL);
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
    public function setThousand($val)
    {
        $this->thousand = $val;
        return $this;
    }

    /**
     *
     * @param int $flag
     * @return int
     * @throws NumberException
     */
    private function checkInt($flag)
    {
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
    public function render($number = NULL, $decimal = NULL)
    {
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

        if ($this->symbol && $this->flag & self::RENDER_SYMBOL) {
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
    public function __toString()
    {
        return (string) $this->render($this->number);
    }

    /**
     * Replace space to &nbsp; in utf-8
     *
     * @param string $val
     * @return string
     */
    private function replaceNbsp($val)
    {
        if ($this->flag & self::FLAG_NBSP) {
            return str_replace(' ', self::NBSP, $val);
        }
        return $val;
    }

    /** @param string $mask */
    private function setWorkingMask($mask)
    {
        $workMask = str_replace('S', $this->symbol, $mask);
        if (strip_tags($workMask) !== $workMask) {
            $this->on(self::IS_HTML);
        } else {
            $this->off(self::IS_HTML);
        }
        $this->workMask = explode('1', $this->replaceNbsp($workMask));
    }

    /**
     *
     * @param int $flag
     * @return NumberFormat
     */
    private function onChangeFlag($flag)
    {
        if ($flag & self::FLAG_NBSP) {
            $this->setWorkingMask($this->mask);
        }
        return $this;
    }

}
