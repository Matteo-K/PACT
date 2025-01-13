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
                Données à cractère personnel
              </h2>
            </section>
            <section>
              <h2>
                Droit d'auteur
              </h2>
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
        
        default:
          
          break;
      }
    ?>
    <div class="pagination">
      <?php if ($page > 1) { ?>
        <a href="mentionsLegales.php?page=<?= $page-1 ?>">Précédent</a>
      <?php } ?>

      <?php if ($page < $nbPage) { ?>
        <a href="mentionsLegales.php?page=<?= $page+1 ?>">Suivant</a>
      <?php } ?>
    </div>
  </main>
  <?php require_once "components/footer.php"; ?>
</body>

</html>