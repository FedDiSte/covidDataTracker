<?php

  $curl = curl_init();
  $file = fopen('creato.csv', 'w');
  curl_setopt($curl, CURLOPT_URL, 'https://raw.githubusercontent.com/pcm-dpc/COVID-19/master/dati-andamento-nazionale/dpc-covid19-ita-andamento-nazionale-20201225.csv');
  curl_setopt($curl, CURLOPT_HEADER, 0);
  curl_setopt($curl, CURLOPT_FILE, $file);
  curl_exec($curl);

  $row = 1;
  if(($handle = fopen('creato.csv', 'r')) !== FALSE) {
    while(($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
        echo "<p> $num fields in line $row: <br /></p>\n";
        $row++;
        for ($c=0; $c < $num; $c++) {
            echo $data[$c] . "<br />\n";
        }
        fclose($handle);
      }
  }

?>
