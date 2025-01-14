<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Condition d'utilisation générale</title>
  <link rel="icon" href="img/logo.png" type="image/x-icon">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <script src="js/setColor.js"></script>
  <?php require_once "components/header.php" ?>
  <?php 
    $page = $_GET["page"] ?? 1;
    $nbPage = 4;
  ?>
  <main id="pageCgu">
    <?php
      switch ($page) {
        case 1: ?>
          <article>
            
            <h1>Conditions Générales d'Utilisation</h1>
            <section>
              <h2>
                1. Introduction
              </h2>
              <p>
                Bienvenue sur notre site web. En accédant et en utilisant ce site, vous acceptez les présentes Conditions
                Générales d'Utilisation.
              </p>
            </section>

            <section>
              <h2>2. Utilisation du site</h2>
              <p>
                Vous vous engagez à utiliser ce site conformément aux lois en vigueur et à respecter les règles suivantes :
              </p>
              <ul>
                <li>Ne pas publier de contenu illicite ou offensant.</li>
                <li>Respecter la vie privée des autres utilisateurs.</li>
                <li>Ne pas tenter de compromettre la sécurité du site.</li>
                <li>Soyez respecteux lors de la rédaction de vos avis</li>
              </ul>
            </section>

            <section>
              <h2>
                3. Propriété Intellectuelle
              </h2>
              <p>
                Tous les contenus présents sur ce site (textes, images, logos) sont protégés par le droit d'auteur.
              </p>
            </section>

            <section>
              <h2>
                4. Responsabilité
              </h2>
              <p>
                Nous déclinons toute responsabilité sur les dommages résultant de l'utilisation du site.
              </p>
            </section>

            <section>
              <h2>
                5. Modification des CGU
              </h2>
              <p>
                Nous nous réservons le droit de modifier ces CGU à tout moment.
              </p>
            </section>

            <section>
              <h2>
                6. Contact
              </h2>
              <p>
                Pour toute question concernant ces CGU, vous pouvez nous contacter à l'adresse suivante : ewen@jain-etudiants.univ-rennes1.com
              </p>
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

        case 3: ?>

          <article>
            <h2>
              Page 3
            </h2>
          </article>

        <?php break;
        case 4: ?>

          <article>
            <h2>
              Page 4
            </h2>
          </article>

        <?php break;
        
        default:?>
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
        <a href="cgu.php?page=<?= $page-1 ?>">Précédent</a>
      <?php } ?>

      <?php if ($page < $nbPage) { ?>
        <a href="cgu.php?page=<?= $page+1 ?>">Suivant</a>
      <?php } ?>

      <?php if ($page != 1 || empty($page)) { ?>
        <a href="cgu.php?page=1">Retour page 1</a>
      <?php } ?>
    </div>
  </main>
  <?php require_once "components/footer.php"; ?>
</body>

</html>