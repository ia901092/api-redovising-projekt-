<!DOCTYPE html>
<html lang="sv">
<head>
  <meta charset="UTF-8">
  <title>Sök</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="box">

  <a href="index.php" class="tillbaka">← Tillbaka</a>
  <h1>🔍 Sökresultat</h1>

  <?php
    if (isset($_POST['lag']) && $_POST['lag'] != "") {  // Formulär/svarssida: POST från search-form
      $sok = $_POST['lag'];
    } else if (isset($_GET['lag']) && $_GET['lag'] != "") {  // Länkning: GET från response.php
      $sok = $_GET['lag'];
    } else {
      echo "<p>Inget sökord. <a href='index.php'>Tillbaka</a></p>";
      exit;
    }

    echo "<p>Söker efter: <b>" . htmlspecialchars($sok) . "</b></p>";

    $json = file_get_contents("https://raw.githubusercontent.com/openfootball/football.json/master/2024-25/en.1.json");
    $data = json_decode($json, true);

    $antal = 0;

    echo "<table>";
    echo "<tr><th>📅 Datum</th><th>🏠 Hemma</th><th>✈️ Borta</th><th>⚽ Resultat</th></tr>";

    foreach ($data["matches"] as $m) {  // Itererin genom alla matches
      $text = strtolower($m["team1"] . " " . $m["team2"]);
      if (strpos($text, strtolower($sok)) === false) continue;  // Sökning: hittar substring (skiftlägesobleroende)

      $resultat = isset($m["score"]) ? $m["score"]["ft"][0] . " - " . $m["score"]["ft"][1] : "ej spelad";

      echo "<tr>";
      echo "<td>" . htmlspecialchars($m["date"]) . "</td>";
      echo "<td>" . htmlspecialchars($m["team1"]) . "</td>";
      echo "<td>" . htmlspecialchars($m["team2"]) . "</td>";
      echo "<td class='resultat'>$resultat</td>";
      echo "</tr>";

      $antal++;
    }

    echo "</table>";

    if ($antal == 0) {
      echo "<p>Inga träffar för \"" . htmlspecialchars($sok) . "\".</p>";
    } else {
      echo "<p><b>$antal matcher hittades.</b></p>";
    }
  ?>

</div>
</body>
</html>