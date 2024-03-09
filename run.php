<?php


use checkout_configs\DatesGenerator;

require_once 'checkout-configs/DatesGenerator.php';


$datesGenerator = new DatesGenerator();

$generatedDates = $datesGenerator->handle(30);

foreach($generatedDates as $dg){
  echo $dg . PHP_EOL;
}


