<?php
  function logger($qualcosa) {
    $output = $qualcosa;
    if(is_array($output)) {
      $output = implode(',', $output);
    }
    echo "<script>console.log('Logging: ".$output."' );</script>";
  }


  $curl = curl_init();
  $file = fopen('creato.csv', 'w');
  if(date("H:i") > strtotime("17:00")) {
    $today = "https://raw.githubusercontent.com/pcm-dpc/COVID-19/master/dati-andamento-nazionale/dpc-covid19-ita-andamento-nazionale-".date("Ymd").".csv";
  } else {
    $today = "https://raw.githubusercontent.com/pcm-dpc/COVID-19/master/dati-andamento-nazionale/dpc-covid19-ita-andamento-nazionale-".date("Ymd", strtotime("-1 days")).".csv";
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

?>

<!DOCTYPE HTML>
<html>
<head>
  <title>covidDataTracker</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="mdl/material.min.css">
  <script src="mdl/material.min.js"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <style>
    .card-square > .mdl-card__title {
      color: #fff;
      background: #46B6AC;
    }
  </style>
</head>
<body>
  <div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer
              mdl-layout--fixed-header">
    <header class="mdl-layout__header">
      <div class="mdl-layout__header-row">
        <span class="mdl-layout-title">covidDataTracker</span>
      </div>
    </header>
    <div class="mdl-layout__drawer">
      <span class="mdl-layout-title">covidDataTracker</span>
      <nav class="mdl-navigation">
        <a class="mdl-navigation__link" href="">Homepage</a>
      </nav>
    </div>
    <main class="mdl-layout__content">
      <div class="page-content">
        <div class="mdl-grid">
          <div class="mdl-cell mdl-cell--4-col">
            <div class="card-square mdl-card--border mdl-shadow--4dp">
              <div class="mdl-card__title mdl-card--expand">
                <h2 class="mdl-card__title-text">Totale positivi</h2>
              </div>
              <div class="mdl-card__supporting-text">
                Totale positivi: <?php echo $datiLetti[1][6]?>
              </div>
              <div class="mdl-card__actions mdl-card--border">
                <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
                  Resoconto ultimi 10 giorni
                </a>
              </div>
            </div>
          </div>
          <div class="mdl-cell mdl-cell--4-col">
            <div class="card-square mdl-card--border mdl-shadow--4dp">
              <div class="mdl-card__title mdl-card--expand">
                <h2 class="mdl-card__title-text">Nuovi positivi</h2>
              </div>
              <div class="mdl-card__supporting-text">
                Nuovi positivi: <?php echo $datiLetti[1][8]?>
              </div>
              <div class="mdl-card__actions mdl-card--border">
                <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
                  Resoconto ultimi 10 giorni
                </a>
              </div>
            </div>
          </div>
          <div class="mdl-cell mdl-cell--4-col">
            <div class="card-square mdl-card--border mdl-shadow--4dp">
              <div class="mdl-card__title mdl-card--expand">
                <h2 class="mdl-card__title-text">Tamponi</h2>
              </div>
              <div class="mdl-card__supporting-text">
                Totale tamponi: <?php echo $datiLetti[1][14]?>
              </div>
              <div class="mdl-card__actions mdl-card--border">
                <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
                  Resoconto ultimi 10 giorni
                </a>
              </div>
            </div>
          </div>
        </div>
        <div class="mdl-grid">

        </div>
    </main>
  </div>
</body>
</html>
