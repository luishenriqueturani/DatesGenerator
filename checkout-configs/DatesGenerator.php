<?php

namespace checkout_configs;

use DateTime;

class DatesGenerator {

  
  /**
   * Method handle
   *
   * @param Integer $quantDays [explicite description]
   *
   * @return string[]
   */
  public function handle($quantDays){
    $now = new DateTime();

    $return = [];

    for($i = 0; $i < $quantDays; $i++){

      if(! $this->verify($now) ){
        $i--;
        $now->modify("+1 day");
        continue;
      }
      array_push($return, $now->format("d/m/Y") );
      //echo $now->format('l') . PHP_EOL;
      $now->modify("+1 day");
    }

    return $return;
  }
  
  /**
   * Method verify
   *
   * @param DateTime $date [explicite description]
   *
   * @return boolean
   */
  private function verify(DateTime $date){
    $year = $date->format('Y');

    $rules = $this->rules();

    foreach($rules['daysOfWeek'] as $day){

      if($day == $date->format('l')) return false;
    }

    //echo $date->format('l') . PHP_EOL;

    foreach($rules['holidays'] as $day){
      $d = new DateTime("{$year}-{$day['month']}-{$day['day']}");

      //echo $d->format('Y-m-d') . PHP_EOL;

      if($d->format('Y-m-d') == $date->format('Y-m-d')){
        return false;
      }
    }

    return true;

  }
  
  /**
   * Method rules
   *
   * @return array
   */
  private function rules(){

    $easterDate = new DateTime( date( "Y-m-d", easter_date()) );

    $easterDay = $easterDate->format('d');
    $easterMonth = $easterDate->format('m');

    $carnivalDate = new DateTime( date( "Y-m-d", strtotime( $easterDate->format("Y-m-d") . " -47 days") ));
    $carnivalDate2 = new DateTime( date( "Y-m-d", strtotime( $easterDate->format("Y-m-d") . " -48 days") ));

    $easterDate->modify("-2 days");

    return [
      "daysOfWeek" => [
        "Sunday"
      ],
      "holidays" => [
        /* Feriados nacionais */
        [ //ano novo
          "day" => "01",
          "month" => "01",
        ],
        [ //carnaval segunda
          "day" => $carnivalDate2->format('d'),
          "month" => $carnivalDate2->format('m'),
        ],
        [ //carnaval terça
          "day" => $carnivalDate->format('d'),
          "month" => $carnivalDate->format('m'),
        ],
        [ //sexta-feira santa
          "day" => $easterDate->format('d'),
          "month" => $easterDate->format('m'),
        ],
        [ //páscoa
          "day" => $easterDay,
          "month" => $easterMonth,
        ],
        [ // tiradentes
          "day" => "21",
          "month" => "04",
        ],
        [ // dia do trabalho
          "day" => "01",
          "month" => "05",
        ],
        [ // independência do Brasil
          "day" => "07",
          "month" => "09",
        ],
        [ // Nossa Senhora Aparecida
          "day" => "12",
          "month" => "10",
        ],
        [ // finados
          "day" => "02",
          "month" => "11",
        ],
        [ // Proclamação da República
          "day" => "15",
          "month" => "11",
        ],
        [ // Natal
          "day" => "25",
          "month" => "12",
        ],
        /* Feriados de POA */
        [ // Data Magna do Rio Grande do Sul
          "day" => "20",
          "month" => "09",
        ],
        [ // Data Magna do Rio Grande do Sul
          "day" => "02",
          "month" => "02",
        ],
        [ // Data Magna do Rio Grande do Sul
          "day" => "07",
          "month" => "04",
        ],
        [ // Data Magna do Rio Grande do Sul
          "day" => "08",
          "month" => "06",
        ],
        [ // Data Magna do Rio Grande do Sul
          "day" => "02",
          "month" => "11",
        ],
      ],
      "skipDays" => []
    ];
  }


}


