<?php
  $fileDownload = file_get_contents('https://raw.githubusercontent.com/pcm-dpc/COVID-19/master/dati-andamento-nazionale/dpc-covid19-ita-andamento-nazionale-20201226.csv');
  if(file_exists("creato.csv")){
    file_put_contents("creato.csv", $fileDownload);
  } else {
    file_create("creato.csv");
    file_put_contents("creato.csv", $fileDownload);
  }
?>
