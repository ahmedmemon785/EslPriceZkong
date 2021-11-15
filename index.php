<?php
require __DIR__ . '/vendor/autoload.php';
try {

$esl= new Esl('Daniyal888','Daniyal888','1632468509994','1558577702698');

    $eslItem= new EslItem('A1A1');
    $eslItem->setTitle('UpdatedTitleByEsl1');
    $eslItem->setSellPrice(100*100);
    $eslItem->setDiscountedPrice(90*100);
    $eslItem->setStock(100);

$esl->updateSku($eslItem);
}catch (Exception $exception){
    die($exception->getMessage().$exception->getTraceAsString());
}

echo 'Welcome'
?>