<?php
  $ch = curl_init('https://raw.githubusercontent.com/pcm-dpc/COVID-19/master/dati-json/dpc-covid19-ita-andamento-nazionale.json');
  $jsonfile = curl_exec($ch);
  $decoded = json_decode($jsonfile);
  print($decoded);
?>
