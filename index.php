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
    // Hämta JSON från webbtjänsten
    $json = file_get_contents("https://raw.githubusercontent.com/openfootball/football.json/master/2024-25/en.1.json");
    $data = json_decode($json, true);

    // Samla unika lag från matcherna
    $lag = [];
    foreach ($data["matches"] as $m) {
      $lag[] = $m["team1"];
    }
    $lag = array_unique($lag);
    sort($lag);
  ?>

  <!-- FORMULÄR 1: Dropdown -->
  <form method="POST" action="response.php">
    <label>Välj lag:</label>
    <select name="lag">
      <?php
        // Loopa lagen och skapa <option>
        foreach ($lag as $l) {
          echo "<option value='" . htmlspecialchars($l) . "'>" . htmlspecialchars($l) . "</option>";
        }
      ?>
    </select>
    <button type="submit">Visa matcher</button>
  </form>

  <!-- FORMULÄR 2: Sök -->
  <form method="POST" action="search.php">
    <label>Sök lag:</label>
    <input type="text" name="lag" placeholder="t.ex. Liverpool" required>
    <button type="submit">Sök</button>
  </form>

</div>
</body>
</html>