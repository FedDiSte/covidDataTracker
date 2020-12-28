<?php
  function logger($qualcosa) {
    $output = $qualcosa;
    if(is_array($output)) {
      $output = implode(',', $output);
    }
    echo "<script>console.log('Logging: ".$output."' );</script>";
  }

  for($i = 0; $i < 10; $i++) {
    $curl = curl_init();
    $file = fopen('creato.csv', 'w');
    if(date("H:i") > strtotime("17:00")) {
      $today = "https://raw.githubusercontent.com/pcm-dpc/COVID-19/master/dati-andamento-nazionale/dpc-covid19-ita-andamento-nazionale-".date("Ymd", strtotime("-".$i." days")).".csv";
    } else {
      $incremento = $i + 1;
      $today = "https://raw.githubusercontent.com/pcm-dpc/COVID-19/master/dati-andamento-nazionale/dpc-covid19-ita-andamento-nazionale-".date("Ymd", strtotime("-".$incremento." days")).".csv";
    }
    curl_setopt($curl, CURLOPT_URL, $today);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_FILE, $file);
    curl_exec($curl);
    fclose($file);

    $datiLetti = array();
    if (($handle = fopen("creato.csv", "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $datiLetti[] = $data;
        }
        fclose($handle);
    }

    $totalePositivi = array();
    $nuoviPositivi = array();
    $rapportoPositiviSuTamponi = array();

    logger("Data: ".$datiLetti[1][0]);
    logger("Totale positivi: ".$datiLetti[1][6]);
    $totalePositivi[$i] = $datiLetti[1][6];
    logger("Nuovi positivi: ".$datiLetti[1][7]);
    $nuoviPositivi[$i] = $datiLetti[1][7];
    logger("Rapporto positivi su tampomi: ".$datiLetti[1][14]/$datiLetti[1][13]);
    $rapportoPositiviSuTamponi[$i] = $datiLetti[1][14]/$datiLetti[1][13];
  }

?>
