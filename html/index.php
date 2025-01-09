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
  <main>
    <div id="index">
      <div id="ALaUne">
      <?php if ($typeUser != "pro_public" && $typeUser != "pro_prive") { ?>
        <div>
          <?php 
            $elementStart = 0;
            $nbElement = 20;
            $offres = new ArrayOffer();
            $offres->displayCardALaUne($offres->filtre($idUser, $typeUser), $typeUser, $elementStart, $nbElement);
          ?>
        </div>
      </div>
      <?php if ($typeUser == "membre") {
        $nbElement = 10;
        $stmt = $conn->prepare("SELECT * FROM pact._consulter WHERE idu = ? ORDER BY dateconsultation LIMIT ?");
        $stmt->execute([$_SESSION['idUser'], $nbElement]);
        $idOffres = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $idOffres[] = $row['idoffre'];
          }
        } elseif ($typeUser == "visiteur") {
          $idOffres = $_SESSION["recent"] ?? [];
        }
      ?>
      <div id="consultationRecente">
        <h2>Consulté récemment</h2>
        <div>
          <?php if (count($idOffres) > 0) {
            $consultRecent = new ArrayOffer($idOffres);
            $consultRecent->displayConsulteRecemment($nbElement);
            ?>
          <?php } else { ?>
            <p>Aucune offre consultée récemment</p>
            <?php } ?>
          </div>
        </div>
        <?php } ?>
      <div id="voirPlus">
        <?php if ($typeUser == "pro_public" || $typeUser == "pro_prive") { ?>
          <a href="manageOffer.php" class="modifierBut">Créer une offre</a>  
        <?php } ?>
      </div>
    </div>
      <!-- Partie de recherche -->
    <div id="searchIndex" class="search">
      <div id="blcSearch">
        <?php 
        require_once "components/asideTriFiltre.php";

        $offres = new ArrayOffer();
        ?>
        <section class="searchoffre">
        </section>
      </div>
      <section id="pagination">
          <ul id="pagination-liste">
          </ul>
      </section>
    </div>
    <?php require_once "components/footer.php"; ?>
    <!-- Data -->
    <div id="offers-data" data-offers='<?php echo htmlspecialchars(json_encode($offres->getArray($offres->filtre($idUser, $typeUser)))); ?>'></div>
    <div id="user-data" data-user='<?php echo $typeUser ?>'></div>
    <script src="js/sortAndFilter.js"></script>
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