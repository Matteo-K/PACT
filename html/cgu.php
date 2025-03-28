<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Conditions Générales d'Utilisation</title>
  <link rel="icon" href="img/logo.png" type="image/x-icon">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <script src="js/setColor.js"></script>
  <?php require_once "components/header.php" ?>
  <?php 
    $page = $_GET["page"] ?? 1;
    $nbPage = 2; // Limiter à deux pages
  ?>
  <main id="pageCgu">
    <?php
      switch ($page) {
        case 1: ?>
          <article>
            <h1>Conditions Générales d'Utilisation (CGU)</h1>
            
            <section>
              <h2>Préambule</h2>
              <p>Les présentes Conditions Générales d'Utilisation ont pour objet de définir les modalités d'accès et d'utilisation du site PACT, édité par l’équipe A21 The-Void.</p>
              <p>Pact est une plateforme dédiée à la publication et consultation d’offre touristique dans le département des Côtes-d’Armor, tout en favorisant une communauté dynamique où les utilisateurs peuvent échanger leurs expériences.</p>
              <p>En accédant au site, l'utilisateur accepte pleinement et sans réserve les présentes CGU. Ces conditions s’appliquent à tous les utilisateurs, qu’ils soient membres enregistrés ou simples visiteurs.</p>
            </section>

            <section>
              <h2>Article 1 - Définition</h2>
              <p>Dans les présentes CGU, les termes suivants ont la signification suivante :</p>
              <ul>
                <li><strong>Site</strong>: désigne le site internet The Void accessible à l'adresse https://the-void.ventsdouest.dev/.</li>
                <li><strong>Compte</strong>: désigne une entité unique créée par un utilisateur sur le site, lui permettant d’accéder aux services et fonctionnalités proposés.</li>
                <li><strong>Utilisateur</strong>: désigne toute personne physique ou morale accédant au Site, qu'elle soit inscrite ou non.</li>
                <li><strong>Contenu</strong>: désigne l'ensemble des éléments présents sur le Site, notamment les textes, images, vidéos, graphismes, logos, bases de données, etc.</li>
              </ul>
            </section>

            <section>
              <h2>Article 2 - Objet</h2>
              <p>Les CGU ont pour objet de définir clairement les conditions d'utilisation du Site par les Utilisateurs. Elles encadrent l'accès aux services, les responsabilités respectives et les règles de conduite à respecter par les Utilisateurs.</p>
            </section>

            <section>
              <h2>Article 3 – Acceptation des CGU</h2>
              <p>L'utilisation du Site est conditionnée à l'acceptation expresse des présentes CGU. En naviguant sur le Site, l'Utilisateur reconnaît avoir lu, compris et accepté pleinement les présentes conditions.</p>
              <p>Si l’utilisateur n’accepte pas les CGU, il est invité à ne pas utiliser le site. Dans ce cas, l’utilisateur doit se désinscrire immédiatement et cesser toute utilisation des services proposés.</p>
            </section>

            <section>
              <h2>Article 4 – Accès au Site et Disponibilité</h2>
              <p>Le Site est accessible gratuitement à tout Utilisateur disposant d'un accès à Internet. L'accès au Site peut être suspendu temporairement ou définitivement, sans préavis, pour des raisons de maintenance ou en cas de force majeure.</p>
              <p>The Void se réserve le droit de modifier, suspendre ou interrompre, temporairement ou définitivement, tout ou partie du site, sans préavis.</p>
            </section>

            <section>
              <h2>Article 5 – Création de Compte</h2>
              <p>Pour accéder à certaines fonctionnalités du Site, l'Utilisateur peut être amené à créer un compte en fournissant des informations exactes et à jour. Il est responsable de la confidentialité de ses identifiants et de toute activité réalisée depuis son compte.</p>
              <p>The Void se réserve le droit de refuser la création d’un compte ou de supprimer un compte existant à sa seule discrétion, en cas de violation des CGU ou pour toute autre raison jugée appropriée.</p>
            </section>

            <section>
              <h2>Article 6 – Propriété Intellectuelle</h2>
              <p>Le Contenu du Site, y compris la structure, le design, les textes, images et bases de données, est protégé par les lois relatives à la propriété intellectuelle. Toute reproduction ou représentation non autorisée est strictement interdite et constitue une contrefaçon.</p>
            </section>

            <section>
              <h2>Article 7 – Responsabilités</h2>
              <p>L’Utilisateur utilise le site sous sa responsabilité exclusive. Cela inclut toutes les actions réalisées sur la plateforme et les conséquences qui peuvent en découler.</p>
              <p>L’éditeur décline toute responsabilité pour les dommages directs et indirects résultant de l’utilisation du site ou de l’impossibilité d’y accéder.</p>
            </section>

            <section>
              <h2>Article 8 – Données Personnelles</h2>
              <p>L'éditeur s'engage à respecter la réglementation en vigueur sur la protection des données personnelles (RGPD). Les données collectées sont utilisées uniquement dans le cadre des services proposés par le Site et peuvent être supprimées sur demande.</p>
            </section>

            <section>
              <h2>Article 9 – Engagements de l'Utilisateur</h2>
              <p>Dans le cadre de l’utilisation de notre service, l’utilisateur s’engage à respecter les conditions suivantes : un comportement respectueux envers les autres utilisateurs, la non-diffusion de contenu malveillant, et la protection de la sécurité de son compte.</p>
            </section>

            <section>
              <h2>Article 10 – Liens Hypertextes</h2>
              <p>Le Site peut contenir des liens vers des sites tiers. L'éditeur ne saurait être tenu responsable du contenu ou de la disponibilité de ces sites.</p>
            </section>

            <section>
              <h2>Article 11 – Modification des CGU</h2>
              <p>L'éditeur se réserve le droit de modifier les présentes CGU à tout moment. Les modifications entrent en vigueur dès leur publication sur le Site. Il est de la responsabilité de l’utilisateur de consulter régulièrement les CGU pour être informé des éventuelles modifications.</p>
            </section>

            <section>
              <h2>Article 12 – Droit Applicable et Juridiction Compétente</h2>
              <p>Les présentes CGU sont régies par le droit français. Tout litige relatif à leur interprétation et exécution relève de la compétence exclusive des tribunaux de Rennes.</p>
            </section>

            <section>
              <h2>Article 13 – Contact</h2>
              <p>Pour toute question relative aux présentes CGU, l'Utilisateur peut contacter l'éditeur à l'adresse suivante : ewen@jain-etudiants.univ-rennes1.com .</p>
            </section>
          </article>
        <?php break;

        case 2: ?>
          <article>
            <h1>Conditions Générales de Vente</h1>
            <p><strong>1. Préambule</strong><br>Les présentes Conditions Générales de Vente (CGV) régissent les relations contractuelles entre le site PACT et les professionnels souhaitant publier des offres...</p>

            <p><strong>2. Objet</strong><br>Le Site propose des abonnements pour publier des offres, avec différentes options de mise en avant.</p>

            <p><strong>3. Abonnements</strong><br>Des abonnements gratuits ou payants sont proposés aux professionnels...</p>
          </article>
        <?php break;
        
      }
    ?>
    <div class="pagination">
      <?php if ($page == 1) { ?>
        <a href="cgu.php?page=2">Suivant</a>
      <?php } ?>

      <?php if ($page == 2) { ?>
        <a href="cgu.php?page=1">Retour à la page 1</a>
      <?php } ?>
    </div>
  </main>
  <?php require_once "components/footer.php"; ?>
</body>

</html>