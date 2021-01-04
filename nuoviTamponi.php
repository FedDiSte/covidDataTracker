<?php
  function logger($qualcosa) {
    $output = $qualcosa;
    if(is_array($output)) {
      $output = implode(',', $output);
    }
    echo "<script>console.log('Logging: ".$output."' );</script>";
  }

  $dataDati = array();
  $handler = 0;

  for($i = 11; $i > 0; $i--) {
    $curl = curl_init();
    $file = fopen('creato.csv', 'w');
    if(strtotime(date("H:i")) > strtotime("17:00")) {
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

    $dataDati[$handler] = date("d/m/Y" ,strtotime($datiLetti[1][0]));
    $tamponi[$handler] = $datiLetti[1][14];
    $handler++;
  }

  for($i = 0; $i < 10; $i++) {
    $calcolato = $tamponi[$i + 1] - $tamponi[$i];
    $nuoviTamponi[$i] = array("y" => $calcolato, "label" => $dataDati[$i + 1]);
  }

?>

<!DOCTYPE HTML>
<html lang="it">
<head>
  <title>covidDataTracker</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.blue_grey-pink.min.css"/> 
  <script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <style>
    .card-square > .mdl-card__title {
      color: #000;
      background: #8eacbb;
    }
  </style>
  <script src = "https://canvasjs.com/assets/script/canvasjs.min.js">
  </script>
  <script>
    window.onload = function() {
      var chart = new CanvasJS.Chart("chartContainer", {
        animationEnabled: true,
        theme: "light2",
        axisY: {
          title: "Nuovi tamponi",
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
          type: "column",
          name: "Tamponi",
          legendText: "tamponi effettuati oggi",
          showInLegend: true,
          dataPoints:<?php echo json_encode($nuoviTamponi, JSON_NUMERIC_CHECK); ?>
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
  <div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer
              mdl-layout--fixed-header">
    <header class="mdl-layout__header">
      <div class="mdl-layout__header-row">
        <span class="mdl-layout-title">Nuovi tamponi</span>
      </div>
    </header>
    <div class="mdl-layout__drawer">
      <span class="mdl-layout-title">covidDataTracker</span>
      <nav class="mdl-navigation">
        <a class="mdl-navigation__link" href="index.php">Homepage</a>
        <a class="mdl-navigation__link" href="totalePositvi.php">Resoconto totale positivi</a>
        <a class="mdl-navigation__link" href="totaleDeceduti.php">Resoconto totale deceduti</a>
        <a class="mdl-navigation__link" href="totaleTamponi.php">Resoconto totale tamponi</a>
        <a class="mdl-navigation__link" href="nuoviPositivi.php">Resoconto nuovi positivi</a>
        <a class="mdl-navigation__link" href="nuoviDeceduti.php">Resoconto deceduti giotnalieri</a>
        <a class="mdl-navigation__link" href="nuoviTamponi.php">Resoconto tamponi giornalieri</a>
        <a class="mdl-navigation__link" href="percentualeTamponi.php">Resoconto percentuale positività</a>
        <a class="mdl-navigation__link" href="terapiaIntensiva.php">Resoconto terapia intensiva</a>
      </nav>
    </div>
    <main class="mdl-layout__content">
      <div class="page-content">
        <div class="mdl-grid">
          <div class="mdl-cell mdl-cell--12-col">
            <div class="mdl-card--border mdl-shadow--4dp">
              <div id="chartContainer" style="height: 400px; width: 100%;"></div>
            </div>
          </div>
      </div>
    </main>
    <footer class="mdl-mini-footer">
        <div class="mdl-mini-footer__right-section">
          <div class="mdl-logo">covidDataTracker</div>
            <ul class="mdl-mini-footer__link-list">
              <li><a href="https://github.com/FedDiSte">GitHub</a></li>
              <li><a href="mailto:fededark0220@gmail.com">Email</a></li>
            </ul>
          </div>
      </footer>
  </div>
</body>
</html>
