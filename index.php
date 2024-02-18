<?php

use checkout_configs\DatesGenerator;

require_once 'checkout-configs/DatesGenerator.php';

const DB_USER = "neo";
const DB_PASS = "sdfv546v454@#";
const DB_NAME = "dates_generator";
const DB_HOST = "localhost";//192.168.1.34

try {
  $db = new PDO("mysql:host=".DB_HOST.":3306;DB_NAME=".DB_NAME, DB_USER, DB_PASS);

  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $db->exec("USE ".DB_NAME);

} catch (\PDOException $ex) {
  writeFile('error.txt', $ex->getMessage());
  die($ex);
}



$datesGenerator = new DatesGenerator();

$generatedDates = $datesGenerator->handle(300);

$db->prepare("INSERT into day_generation values ()")->execute();

$id = $db->lastInsertId();

foreach($generatedDates as $dg){
  $stmt = $db->prepare("INSERT INTO days_generated (day_generation_id, generate) values( ? , ? )");
  $stmt->bindParam(1, $id);
  $stmt->bindParam(2, $dg);

  $stmt->execute();
}

echo "Encerrado" . PHP_EOL;



function writeFile($dir, $text) {
	date_default_timezone_set('America/Sao_Paulo');
	if (file_exists(__DIR__ . "/{$dir}")) {
		$file = file_get_contents(__DIR__ . "/{$dir}");

		file_put_contents(__DIR__ . "/{$dir}", $file . PHP_EOL . date('d/m/Y H:i:s', time()) . ' --- ' . $text . PHP_EOL);
	} else {
		file_put_contents(__DIR__ . "/{$dir}", date('d/m/Y H:i:s', time()) . ' --- ' . $text . PHP_EOL);
	}
}
