<?php
require_once "config.php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>PACT</title>
</head>
<body id="index">
  <script src="js/setColor.js"></script>
  <?php require_once "components/header.php"; ?>
  <main>
    <div id="proposeRecherche">
      <h2>Commencer par une recherche</h2>
    </div>
    <div id="ALaUne">
      <?php 
        $elementStart = 0;
        $nbElement = 20;
        $offres = new ArrayOffer();
        $offres->displayCardALaUne($offres->filtre($idUser, $typeUser), $typeUser, $elementStart, $nbElement);
      ?>
    </div>
    <div id="voirPlus">
      <a href="search.php">Voir plus</a>
    </div>
  </main>
  <?php require_once "components/footer.php"; ?>
</body>
</html>