<?php
  function logger($qualcosa) {
    $output = $qualcosa;
    if(is_array($output)) {
      $output = implode(',', $output);
    }
    echo "<script>console.log('Logging: ".$output."' );</script>";
  }

  $totaleDeceduti = array();
  $totaleTamponi = array();

  $handler = 0;

  for($i = 2; $i >0; $i--){
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

    $totaleDeceduti[$handler] = $datiLetti[1][10];
    $totaleTamponi[$handler] = $datiLetti[1][14];

    $handler++;

    logger("Data: ".$datiLetti[1][0]);
 }

 $differenzaDeceduti = $totaleDeceduti[1] - $totaleDeceduti[0];
 $differenzaTamponi = $totaleTamponi[1] - $totaleTamponi[0];
 $percentualeTamponi = round($datiLetti[1][14]/$datiLetti[1][13], 2, PHP_ROUND_HALF_UP);

?>

<!DOCTYPE HTML>
<html>
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
</head>
<body>
  <div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
    <header class="mdl-layout__header">
      <div class="mdl-layout__header-row">
        <span class="mdl-layout-title">covidDataTracker</span>
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
          <div class="mdl-cell mdl-cell--4-col">
            <div class="card-square mdl-card--border mdl-shadow--4dp">
              <div class="mdl-card__title mdl-card--expand">
                <h2 class="mdl-card__title-text">Totale positivi</h2>
              </div>
              <div class="mdl-card__supporting-text">
                Totale positivi: <?php echo $datiLetti[1][6]?>
              </div>
              <div class="mdl-card__actions mdl-card--border">
                <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" href="totalePositivi.php">
                  Resoconto ultimi 10 giorni
                </a>
              </div>
            </div>
          </div>
          <div class="mdl-cell mdl-cell--4-col">
            <div class="card-square mdl-card--border mdl-shadow--4dp">
              <div class="mdl-card__title mdl-card--expand">
                <h2 class="mdl-card__title-text">Totale deceduti</h2>
              </div>
              <div class="mdl-card__supporting-text">
                Totale deceduti: <?php echo $datiLetti[1][10]?>
              </div>
              <div class="mdl-card__actions mdl-card--border">
                <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" href="totaleDeceduti.php">
                  Resoconto ultimi 10 giorni
                </a>
              </div>
            </div>
          </div>
          <div class="mdl-cell mdl-cell--4-col">
            <div class="card-square mdl-card--border mdl-shadow--4dp">
              <div class="mdl-card__title mdl-card--expand">
                <h2 class="mdl-card__title-text">Totale tamponi</h2>
              </div>
              <div class="mdl-card__supporting-text">
                Totale tamponi: <?php echo $datiLetti[1][14]?>
              </div>
              <div class="mdl-card__actions mdl-card--border">
                <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" href="totaleTamponi.php">
                  Resoconto ultimi 10 giorni
                </a>
              </div>
            </div>
          </div>
        </div>
        <div class="mdl-grid">
          <div class="mdl-cell mdl-cell--4-col">
            <div class="card-square mdl-card--border mdl-shadow--4dp">
              <div class="mdl-card__title mdl-card--expand">
                <h2 class="mdl-card__title-text">Nuovi positivi</h2>
              </div>
              <div class="mdl-card__supporting-text">
                Nuovi positivi: <?php echo $datiLetti[1][8]?>
              </div>
              <div class="mdl-card__actions mdl-card--border">
                <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" href="nuoviPositivi.php">
                  Resoconto ultimi 10 giorni
                </a>
              </div>
            </div>
          </div>
          <div class="mdl-cell mdl-cell--4-col">
            <div class="card-square mdl-card--border mdl-shadow--4dp">
              <div class="mdl-card__title mdl-card--expand">
                <h2 class="mdl-card__title-text">Deceduti oggi</h2>
              </div>
              <div class="mdl-card__supporting-text">
                Deceduti oggi: <?php echo $differenzaDeceduti?>
              </div>
              <div class="mdl-card__actions mdl-card--border">
                <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" href="nuoviDeceduti.php">
                  Resoconto ultimi 10 giorni
                </a>
              </div>
            </div>
          </div>
          <div class="mdl-cell mdl-cell--4-col">
            <div class="card-square mdl-card--border mdl-shadow--4dp">
              <div class="mdl-card__title mdl-card--expand">
                <h2 class="mdl-card__title-text">Tamponi oggi</h2>
              </div>
              <div class="mdl-card__supporting-text">
                Tamponi oggi: <?php echo $differenzaTamponi?>
              </div>
              <div class="mdl-card__actions mdl-card--border">
                <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" href="nuoviTamponi.php">
                  Resoconto ultimi 10 giorni
                </a>
              </div>
            </div>
          </div>
        </div>
        <div class="mdl-grid">
          <div class="mdl-cell mdl-cell--6-col">
            <div class="card-square mdl-card--border mdl-shadow--4dp">
              <div class="mdl-card__title mdl-card--expand">
                <h2 class="mdl-card__title-text">Percentuale tamponi su positvi</h2>
              </div>
              <div class="mdl-card__supporting-text">
                Percentuale: <?php echo $percentualeTamponi."%"?>
              </div>
              <div class="mdl-card__actions mdl-card--border">
                <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" href="percentualeTamponi.php">
                  Resoconto ultimi 10 giorni
                </a>
              </div>
            </div>
          </div>
          <div class="mdl-cell mdl-cell--6-col">
            <div class="card-square mdl-card--border mdl-shadow--4dp">
              <div class="mdl-card__title mdl-card--expand">
                <h2 class="mdl-card__title-text">Terapia intensiva</h2>
              </div>
              <div class="mdl-card__supporting-text">
                Terapia intensiva: <?php echo $datiLetti[1][3]?>
              </div>
              <div class="mdl-card__actions mdl-card--border">
                <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" href="terapiaIntensiva.php">
                  Resoconto ultimi 10 giorni
                </a>
              </div>
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
