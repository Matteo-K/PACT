<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mentions Legales</title>
  <link rel="icon" href="img/logo.png" type="image/x-icon">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <script src="js/setColor.js"></script>
  <?php require_once "components/header.php" ?>
  <?php
  $page = $_GET["page"] ?? 1;
  $nbPage = 2;
  ?>
  <main id="mentionsLegales">
    <?php
    switch ($page) {
      case 1: ?>
        <article>
          <section>
            <h2>
              1. Responsable du traitement
            </h2>
            Equipe A21 The Void
            <br>
            Adresse : Rue Edouard Branly 22300 Lannion
            <br>
            Email : ewen@jain-etudiants.univ-rennes1.com
            <br>
          </section>
          <section>
            <h2>
            2. Finalités du traitement des données 
            </h2>
            Les données à caractère personnel que nous collectons sont utilisées pour les finalités suivantes : 
            
          </section>
          <section>
            <h2>
              Sécurité informatique
            </h2>
          </section>
        </article>
        <?php break;

      case 2: ?>

        <article>
          <h2>
            Page 2
          </h2>
        </article>

        <?php break;

      default: ?>
        <article>
          <h2>
            Page inconnue
          </h2>
        </article>
        <?php break;
    }
    ?>
    <div class="pagination">
      <?php if ($page > 1) { ?>
        <a href="mentionsLegales.php?page=<?= $page - 1 ?>">Précédent</a>
      <?php } ?>

      <?php if ($page < $nbPage) { ?>
        <a href="mentionsLegales.php?page=<?= $page + 1 ?>">Suivant</a>
      <?php } ?>

      <?php if ($page != 1 || empty($page)) { ?>
        <a href="cgu.php?page=1">Retour page 1</a>
      <?php } ?>
    </div>
  </main>
  <?php require_once "components/footer.php"; ?>
</body>

</html>