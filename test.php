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


try {
  $stmt = $db->prepare("SELECT generate FROM days_generated LIMIT 1;");

  $stmt->execute();

  $res = $stmt->fetchAll();


  if(count($res) <= 0){
    die("Nenhum dado encontrado");
  }

  $pass = 0;
  $error = 0;

  foreach ($res as $key => $value) {
    $day = new DateTime($res['generate']);

    if($datesGenerator->verify($day)){
      $pass++;
    }else{
      $error++;
    }
    echo "progress... {$key}\r";
  }

  echo PHP_EOL . "corretos: {$pass}" . PHP_EOL . "erros: {$error}" . PHP_EOL;


} catch (\PDOException $th) {
  writeFile('error.txt', $th->getMessage());
  die($th);
}





function writeFile($dir, $text) {
	date_default_timezone_set('America/Sao_Paulo');
	if (file_exists(__DIR__ . "/{$dir}")) {
		$file = file_get_contents(__DIR__ . "/{$dir}");

		file_put_contents(__DIR__ . "/{$dir}", $file . PHP_EOL . date('d/m/Y H:i:s', time()) . ' --- ' . $text . PHP_EOL);
	} else {
		file_put_contents(__DIR__ . "/{$dir}", date('d/m/Y H:i:s', time()) . ' --- ' . $text . PHP_EOL);
	}
}


