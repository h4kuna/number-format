<?php
include __DIR__ . "/vendor/autoload.php";
// 2# Create Nette Configurator
$configurator = new Nette\Config\Configurator;
$tmp = __DIR__ . '/tmp';
$configurator->enableDebugger($tmp);
$configurator->setTempDirectory($tmp);
$container = $configurator->createContainer();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
        <title>NumberFormat example</title>
    </head>
    <body>

        <h3>Number Format</h3>
        <?php
        $number = new h4kuna\NumberFormat('EUR');
        echo $number->render(); // NULL
        echo $number; // ''
        ?><br><?php
        echo $number->setNumber(1234.4560); // 1&nbsp;234,46&nbsp;EUR
        ?><br><?php
        echo $number->setNbsp(FALSE); // 1 234,46 EUR
        ?><br><?php
        echo $number->setDecimal(4); // 1 234,4560 EUR
        ?><br><?php
        echo $number->setMask('S 1'); // EUR 1 234,4560
        ?><br><?php
        echo $number->setPoint('.'); // EUR 1 234.4560
        ?><br><?php
        echo $number->setSymbol('€'); // € 1 234.4560
        ?><br><?php
        echo $number->setThousand(','); // € 1,234.4560
        ?><br><?php
        echo $number->setZeroClear(TRUE); // € 1,234.456
        ?><br><?php
        echo $number->setNumber(0);
        ?><br><?php
        echo $number->setNumber(5);
        ?><br><?php
        echo $number->render(NULL);
        ?><br><?php
        echo $number->setDecimal(-2)->render(1235.45); // € 1,200
        ?><br><?php
        try {
            $number->setNumber('1,5'); // throw exception
        } catch (h4kuna\NumberException $e) {

        }
        echo $number->render('1,5'); // NULL
        ?>

    </body>
</html>



