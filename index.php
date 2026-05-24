<!DOCTYPE html>
<html lang="sv">
<head>
  <meta charset="UTF-8">
  <title>Premier League</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="box">

  <h1>⚽ Premier League</h1>
  <p>Välj ett lag för att se deras matcher, eller sök efter ett lag.</p>

  <?php
    $json = file_get_contents("https://raw.githubusercontent.com/openfootball/football.json/master/2024-25/en.1.json");
    $data = json_decode($json, true);

    $lag = [];
    foreach ($data["matches"] as $m) {
      $lag[] = $m["team1"];  // Iterering genom matches-arrayen
    }
    $lag = array_unique($lag);  // Tar bort dubbletter
    sort($lag);  // Sorterar alphabetiskt för dropdown
  ?>

    <form method="POST" action="response.php">
      <label>Välj lag:</label>
      <select name="lag">
        <?php
          foreach ($lag as $l) {  // Generering av dropdown-options
            echo "<option value='" . htmlspecialchars($l) . "'>" . htmlspecialchars($l) . "</option>";
          }
        ?>
      </select>
      <button type="submit">Visa matcher</button>
    </form>
  <form method="POST" action="search.php">
    <label>Sök lag:</label>
    <input type="text" name="lag" placeholder="t.ex. Liverpool" required>
    <button type="submit">Sök</button>
  </form>

</div>
</body>
</html>