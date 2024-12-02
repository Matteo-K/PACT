<?php
require_once "config.php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <link rel="icon" href="img/logo.png" type="image/x-icon">
  <title>PACT</title>
</head>
<body>
  <script src="js/setColor.js"></script>
  <?php require_once "components/header.php"; ?>
  <main id="index">
    <div id="ALaUne">
      <?php if ($typeUser != "pro_public" && $typeUser != "pro_prive") { ?>
        <h2>À la une</h2>
      <?php } ?>
      <div>
        <?php 
          $elementStart = 0;
          $nbElement = 20;
          $offres = new ArrayOffer();
          $offres->displayCardALaUne($offres->filtre($idUser, $typeUser), $typeUser, $elementStart, $nbElement);
        ?>
      </div>
    </div>
    <div id="voirPlus">
      <?php if ($typeUser == "pro_public" || $typeUser == "pro_prive") { ?>
        <a href="manageOffer.php" class="modifierBut">Créer une offre</a>  
      <?php } ?>
        <a href="search.php" class="modifierBut">Voir plus</a>
    </div>
  </main>
  <?php require_once "components/footer.php"; ?>
  <script>
    const forms = document.querySelectorAll("#index form");
    forms.forEach(form => {
      form.addEventListener("click", (event) => {
        if (event.target.tagName.toLowerCase() === "a") {
          return;
        }
        event.preventDefault();
        form.submit();
      });
    });
  </script>
</body>
</html>