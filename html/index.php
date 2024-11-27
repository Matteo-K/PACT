<?php
require_once "config.php";
header("Location: search.php");
exit();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PACT</title>
</head>
<body id="index">
  <script src="js/setColor.js"></script>
  <?php require_once "components/header.php"; ?>
  <main>
    <div id="proposeRecherche">
      <h2>Commencé par une recherche</h2>
    </div>
    <div id="ALaUne">

    </div>
    <div id="voirPlus">
      <a href="search.php">Voir plus</a>
    </div>
  </main>
  <?php require_once "components/footer.php"; ?>
</body>
</html>