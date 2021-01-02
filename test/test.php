<?php
  function logger($qualcosa) {
    $output = $qualcosa;
    if(is_array($output)) {
      $output = implode(',', $output);
    }
    echo "<script>console.log('Logging: ".$output."' );</script>";
  }

  $totalePositivi = array();
  $nuoviPositivi = array();
  $rapportoPositiviSuTamponi = array();

  $handler = 0;

  for($i = 200; $i > 0; $i--) {
    $curl = curl_init();
    $file = fopen('creato.csv', 'w');
    if(date("H:i") > strtotime("17:00")) {
      $incremento = $i - 1;
      $today = "https://raw.githubusercontent.com/pcm-dpc/COVID-19/master/dati-andamento-nazionale/dpc-covid19-ita-andamento-nazionale-".date("Ymd", strtotime("-".$incremento." days")).".csv";
    } else {
      $today = "https://raw.githubusercontent.com/pcm-dpc/COVID-19/master/dati-andamento-nazionale/dpc-covid19-ita-andamento-nazionale-".date("Ymd", strtotime("-".$i." days")).".csv";
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

    logger("Data: ".$datiLetti[1][0]);
    $dataDati = $datiLetti[1][0];
    $totalePositivi[$handler] = array("y" => $datiLetti[1][6], "label" => $dataDati);
    $nuoviPositivi[$handler] = array("y" => $datiLetti[1][7], "label" => $dataDati);
    $rapportoPositiviSuTamponi[$handler] = array("y" => $datiLetti[1][14]/$datiLetti[1][13], "label" => $dataDati);
    $handler++;
  }

?>

<!DOCTYPE HTML>
<html>
<head>
  <script src = "https://canvasjs.com/assets/script/canvasjs.min.js">
  </script>
  <script>
    window.onload = function() {
      var chart = new CanvasJS.Chart("chartContainer", {
        animationEnabled: true,
        theme: "light2",
        title: {
          text: "Totale positivi negli ultimi 10 giorni"
        },
        axisY: {
          title: "Positivi",
          titleFontColor: "#4F81BC",
          lineColor: "#CF1606",
          labelFontColor:"#4F81BC",
          tickColor: "#CF1606"
        },
        toolTip: {
          shared: true
        },
        legend: {
          cursor: "pointer",
          itemClick: toggleDataSeries
        },
        data: [{
          type: "line",
          name: "Positvi",
          legendText: "Positivi",
          showInLegend: true,
          dataPoints:<?php echo json_encode($totalePositivi, JSON_NUMERIC_CHECK); ?>
        }]
      });
      chart.render();

      function toggleDataSeries(e) {
        if(typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
          e.dataSeries.visible = false;
        } else {
          e.dataSeries.visible = true;
        }
        chart.render();
      }
    }
  </script>
</head>

<body>
  <div id="chartContainer" style="height: 300px; width: 100%;"></div>
</body>
</html>
