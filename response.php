<!DOCTYPE html>
<html lang="sv">
<head>
  <meta charset="UTF-8">
  <title>Matcher</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="box">
 
  <a href="index.php" class="tillbaka">← Tillbaka</a>
 
  <?php
    if (!isset($_POST['lag']) || $_POST['lag'] == "") {  // Formulär/svarssida: tar emot POST-data
      echo "<p>Inget lag valt. <a href='index.php'>Tillbaka</a></p>";
      exit;
    }
 
    $valtLag = $_POST['lag'];
 
    // Hämta JSON
    $json = file_get_contents("https://raw.githubusercontent.com/openfootball/football.json/master/2024-25/en.1.json");
    $data = json_decode($json, true);
 
    $vinst = 0; $oavgjort = 0; $forlust = 0;
    foreach ($data["matches"] as $m) {  // Iterering genom matches
      if ($m["team1"] != $valtLag && $m["team2"] != $valtLag) continue;
      if (!isset($m["score"])) continue;
      $h = $m["score"]["ft"][0];  // Navigering för attribut: nested array
      $b = $m["score"]["ft"][1];
      if ($m["team1"] == $valtLag) {
        if ($h > $b) $vinst++;
        else if ($h == $b) $oavgjort++;
        else $forlust++;
      } else {
        if ($b > $h) $vinst++;
        else if ($h == $b) $oavgjort++;
        else $forlust++;
      }
    }
  ?>
 
  <!-- Dokumentorienterad layout: DIV-baserad struktur -->
  <div class="header">
    <h1>⚽ <?php echo htmlspecialchars($valtLag); ?></h1>
    <div class="info">  <!-- Statistik-kort istället för tabell -->
      <div class="kort"><div class="kort-label">Vinster</div><div class="kort-tal vinst"><?php echo $vinst; ?></div></div>
      <div class="kort"><div class="kort-label">Oavgjorda</div><div class="kort-tal oavg"><?php echo $oavgjort; ?></div></div>
      <div class="kort"><div class="kort-label">Förluster</div><div class="kort-tal forlust"><?php echo $forlust; ?></div></div>
    </div>
  </div>
 
  <!-- Tabell-layout: rad (tr) och kolumner (td) -->
  <table>
    <tr>
      <th>📅 Datum</th>
      <th>🏠 Hemma</th>
      <th>✈️ Borta</th>
      <th>⚽ Resultat</th>
      <th>🔍 Mer</th>
    </tr>
    <?php
      foreach ($data["matches"] as $m) {  // Iterering för varje match
        if ($m["team1"] != $valtLag && $m["team2"] != $valtLag) continue;
 
        if (isset($m["score"])) {
          $resultat = $m["score"]["ft"][0] . " - " . $m["score"]["ft"][1];
        } else {
          $resultat = "ej spelad";
        }
 
        $motstandare = ($m["team1"] == $valtLag) ? $m["team2"] : $m["team1"];
 
        echo "<tr>";  // Rad för denna match
        echo "<td>" . htmlspecialchars($m["date"]) . "</td>";  // Kolumn 1: Datum
        echo "<td>" . htmlspecialchars($m["team1"]) . "</td>";  // Kolumn 2: Hemmalag
        echo "<td>" . htmlspecialchars($m["team2"]) . "</td>";  // Kolumn 3: Bortalag
        echo "<td class='resultat'>$resultat</td>";  // Kolumn 4: Resultat
        echo "<td><a href='search.php?lag=" . urlencode($motstandare) . "' class='lank'>" . htmlspecialchars($motstandare) . " →</a></td>";  // Länkning med GET
        echo "</tr>";
      }
    ?>
      }
    ?>
  </table>
 
</div>
</body>
</html>
 