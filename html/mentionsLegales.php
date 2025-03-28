<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mentions Légales</title>
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
            <h2>Mentions Légales</h2>
            <p>
              Dans le cadre de l'utilisation de notre site internet PACT, nous attachons une grande importance à la protection de vos données à caractère personnel. Cette section vous informe des pratiques relatives à la collecte, l'utilisation, la conservation et la protection de vos données personnelles.
            </p>
          </section>
          <section>
            <h2>1. Responsable du traitement</h2>
            <p>
              Tottereau Benoît, Président association TripEnArvor
              <br> Adresse : Rue Edouard Branly 22300 Lannion
              <br> Email : benoit.tottereau@univ-rennes.fr
            </p>
          </section>
          <section>
            <h2>2. Finalités du traitement des données</h2>
            <p>Les données à caractère personnel que nous collectons sont utilisées pour les finalités suivantes :</p>
            <ul>
              <li>Fournir les services proposés sur le site (ex : création de compte, gestion des offres, des avis).</li>
              <li>Améliorer l’expérience utilisateur et assurer la sécurité du site.</li>
            </ul>
          </section>
          <section>
            <h2>3. Catégories de données collectées</h2>
            <p>Nous pouvons être amenés à collecter les données suivantes :</p>
            <ul>
              <li>Données d'identification : nom, prénom, pseudonyme, adresse e-mail, adresse postale, numéro de téléphone.</li>
              <li>Pour une entreprise, sa dénomination et son numéro de SIREN.</li>
            </ul>
          </section>
          <section>
            <h2>4. Partage des données personnelles</h2>
            <p>Nous nous engageons à ne pas vendre ou céder vos données à des tiers sans votre consentement explicite, à l'exception des services nécessaires pour la gestion du site.</p>
          </section>
          <section>
            <h2>5. Durée de conservation des données</h2>
            <p>Nous conservons vos données personnelles pendant la durée nécessaire à l’accomplissement des services de PACT pour lesquelles elles ont été collectées dans le respect des obligations légales.</p>
          </section>
          <section>
            <h2>6. Vos droits</h2>
            <p>Conformément au RGPD, vous disposez des droits suivants concernant vos données personnelles :</p>
            <ul>
              <li>Droit d'accès : vous pouvez obtenir une copie des données personnelles que nous détenons à votre sujet.</li>
              <li>Droit de rectification : vous pouvez demander que vos données soient corrigées si elles sont inexactes.</li>
              <li>Droit à l’effacement : vous pouvez demander la suppression de vos données personnelles, sous certaines conditions.</li>
              <li>Droit d’opposition : vous pouvez vous opposer à l'utilisation de vos données.</li>
            </ul>
            <p>Pour exercer ces droits, vous pouvez nous contacter à l’adresse suivante : benoit.tottereau@univ-rennes.fr</p>
          </section>
          <section>
            <h2>7. Cookies</h2>
            <p>Nous utilisons des cookies pour améliorer l’expérience de navigation et analyser l’utilisation de notre site. Vous pouvez gérer vos préférences de cookies via les paramètres de votre navigateur.</p>
          </section>
          <section>
            <h2>8. Modifications des mentions légales</h2>
            <p>Nous nous réservons le droit de modifier ces mentions légales à tout moment. Nous vous invitons donc à les consulter régulièrement.</p>
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
        <a href="mentionsLegales.php?page=1">Retour page 1</a>
      <?php } ?>
    </div>
  </main>
  <?php require_once "components/footer.php"; ?>
</body>

</html>
