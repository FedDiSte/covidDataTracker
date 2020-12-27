<?php
  $curl = curl_init();
  if(file_exists('creato.csv')) {
    file_delete('creato.csv');
  }
  $file = fopen('creato.csv', 'w');
  curl_setopt($curl, CURLOPT_URL, 'https://raw.githubusercontent.com/pcm-dpc/COVID-19/master/dati-andamento-nazionale/dpc-covid19-ita-andamento-nazionale-20201226.csv');
  curl_setopt($curl, CURLOPT_HEADER, 0);
  curl_setopt($curl, CURLOPT_FILE, $file);
  curl_exec($curl);
  fclose($file);

  $row = 1;
  echo "<br>pre if";
  if(($handle = fopen('creato.csv', 'r')) !== FALSE) {
    echo "in if";
    while(($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        echo "in while";
        $num = count($data);
        echo "<p> $num fields in line $row: <br /></p>\n";
        //$row++;
        for ($c=0; $c < $num; $c++) {
            echo "in for";
            echo $data[$c] . "<br />\n";
        }
        fclose($handle);
      }
  }
?>
