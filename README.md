Number Format
=============

[![Build Status](https://travis-ci.org/h4kuna/number-format.png)](https://travis-ci.org/h4kuna/number-format)

Wrapper above number_format, api is very easy.

If you don't use Nette framework you can delete [extends Object](http://doc.nette.org/cs/php-language-enhancements).

NumberFormat
-------
```php
$number = new NumberFormat('EUR');
echo $number->render(); // NULL

$number->setNumber(1234.4560);
echo $number->render(); // 1&nbsp;234,46&nbsp;EUR

$number->off(INumberFormat::NBSP_FLAG);
echo $number->render(); // 1 234,46 EUR

$number->setDecimal(4);
echo $number->render(); // 1 234,4560 EUR

$number->setMask('S 1');
echo $number->render(); // EUR 1 234,4560

$number->setPoint('.');
echo $number->render(); // EUR 1 234.4560

$number->setSymbol('€');
echo $number->render(); // € 1 234.4560

$number->setThousand(',');
echo $number->render(); // € 1,234.4560

$number->on(INumberFormat::ZERO_CLEAR);
echo $number->render(); // € 1,234.456

$number->setDecimal(-2);
echo $number->render(); // € 1,200
echo $number; // € 1,200

$number->setEmptyValue('-');
echo $number->render(''); // -
echo $number->render(0); // € 0

$number->on(INumberFormat::ZERO_IS_EMPTY);
echo $number->render(0); // -

$number->setNumber('1,5'); // throw exception
$number->render('1,5'); // NULL
```

Tax
-------
```php
$tax = new Tax;
$tax->setVatIO(FALSE, TRUE);
echo $tax->taxation(100); // 121,00 Kč

$tax->setVatIO(TRUE, FALSE);
$tax->number = 121;
echo $tax; // 100,00 Kč
```

Vat
-------
```php
// first instance
$vat = Vat::create(20);
$vat = Vat::create(1.2);
$vat = Vat::create(0.2);
$vat = Vat::create($vat);

// second instance
$vat = Vat::create('21');
```
In memory exists only two instance of Vat with 20% and 21%.
