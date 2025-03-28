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





Mentions Légales 

Dans le cadre de l'utilisation de notre site internet PACT, nous attachons une grande importance à la protection de vos données à caractère personnel. Cette section vous informe des pratiques relatives à la collecte, l'utilisation, la conservation et la protection de vos données personnelles. 

1. Responsable du traitement 

Tottereau Benoît, Président association TripEnArvor 

Adresse : Rue Edouard Branly 22300 Lannion 

Email : benoit.tottereau@univ-rennes.fr 

2. Finalités du traitement des données 

Les données à caractère personnel que nous collectons sont utilisées pour les finalités suivantes : 

Fournir les services proposés sur le site (ex : création de compte, gestion des offres, des avis). 

Améliorer l’expérience utilisateur et assurer la sécurité du site. 

 

3. Catégories de données collectées 

Nous pouvons être amenés à collecter les données suivantes : 

Données d'identification : nom, prénom, pseudonyme, adresse e-mail, adresse postale, numéro de téléphone. 

Pour une entreprise sa dénomination et son numéro de SIREN 

 

4. Partage des données personnelles 

Nous nous engageons à ne pas vendre ou céder vos données à des tiers sans votre consentement explicite, à l'exception des services nécessaire pour la gestion du site. 

5. Durée de conservation des données 

Nous conservons vos données personnelles pendant la durée nécessaire à l’accomplissement des services de PACT pour lesquelles elles ont été collectées dans le respect des obligations légales. 

6. Vos droits   

Conformément au RGPD, vous disposez des droits suivants concernant vos données personnelles : 

Droit d'accès : vous pouvez obtenir une copie des données personnelles que nous détenons à votre sujet. 

Droit de rectification : vous pouvez demander que vos données soient corrigées si elles sont inexactes. 

Droit à l’effacement : vous pouvez demander la suppression de vos données personnelles, sous certaines conditions. 

Droit d’opposition : vous pouvez vous opposer à l'utilisation de vos données. 

Pour exercer ces droits, vous pouvez nous contacter à l’adresse suivante : benoit.tottereau@univ-rennes.fr 

7. Cookies 

Nous utilisons des cookies pour améliorer l’expérience de navigation et analyser l’utilisation de notre site. Vous pouvez gérer vos préférences de cookies via les paramètres de votre navigateur. 

8. Modifications des mentions légales 

Nous nous réservons le droit de modifier ces mentions légales à tout moment. Nous vous invitons donc à les consulter régulièrement. 

 

Conditions Générales d’Utilisation 

 

CONDITIONS GÉNÉRALES D'UTILISATION (CGU) – THE VOID 

Préambule 

Article 1 - Définition 

Article 2 - Objet 

Article 3 – Acceptation des CGU 

Article 4 - Accès au Site et Disponibilité 

Article 5 - Création de compte 

Article 6 - Propriété intellectuelle 

Article 7 - Responsabilités 

Article 8 - Données Personnelles 

Article 9 - Engagements de l’Utilisateur 

Article 10 - Liens Hypertextes 

Article 11 - Modification des CGU 

Article 12 - Droit Applicable et Juridiction Compétente 

Article 13 - Contact 

Préambule  

Les présentes Conditions Générales d'Utilisation ont pour objet de définir les modalités d'accès et d'utilisation du site PACT, édité par l’équipe A21 The-Void. 

 

PACT est une plateforme dédiée à la publication et consultation d’offre touristique dans le département des Côtes-d’Armor. Elle favorise également une communauté dynamique où les utilisateurs peuvent échanger de leurs expériences. 

 

En accédant au site, l'utilisateur accepte pleinement et sans réserve les présentes CGU. Ces conditions s’appliquent à tous les utilisateurs, qu’ils soient membres enregistrés ou simples visiteurs. 

Article 1 – Définition  

Dans les présentes CGU, les termes suivants ont la signification suivante : 

Site : désigne le site internet The Void accessible à l'adresse https://the-void.ventsdouest.dev/ . 

Compte : désigne une entité unique créée par un utilisateur sur le site, lui permettant d’accéder aux services et fonctionnalités proposés 

Utilisateur : désigne toute personne physique ou morale accédant au Site, qu'elle soit inscrite ou non. 

Contenu : désigne l'ensemble des éléments présents sur le Site, notamment les textes, images, vidéos, graphismes, logos, bases de données, etc. 

Article 2 – Objet  

Les CGU ont pour objet de définir clairement les conditions d'utilisation du Site par les Utilisateurs. Elles encadrent l'accès aux services, les responsabilités respectives et les règles de conduite à respecter par les Utilisateurs.  

 

La plateforme s’engage à  

Garantir la sécurité des utilisateurs afin de protéger leurs informations.  

Favoriser le respect et la bienveillance entre utilisateurs. 

 

Bien que la plateforme vise à offrir un espace sûr et accueillant, chaque utilisateur est responsable de ses interactions et du contenu qu’il publie. 

Article 3 – Acceptation des CGU  

L'utilisation du Site est conditionnée à l'acceptation expresse des présentes CGU. En naviguant sur le Site, l'Utilisateur reconnaît avoir lu, compris et accepté pleinement les présentes conditions. 

 

Si l’utilisateur n’accepte pas les CGU, il est invité à ne pas utiliser le site. Dans ce cas, l’utilisateur doit se désinscrire immédiatement et cesser toute utilisation des services proposés. 

Article 4 – Accès au Site et Disponibilité  

Le Site est accessible gratuitement à tout Utilisateur disposant d'un accès à Internet. L'accès au Site peut être suspendu temporairement ou définitivement, sans préavis, pour des raisons de maintenance ou en cas de force majeure. 

 

The Void se réserve le droit de modifier, suspendre ou interrompre, temporairement ou définitivement, tout ou partie du site, sans préavis. 

Article 5 – Création de Compte  

Pour accéder à certaines fonctionnalités du Site, l'Utilisateur peut être amené à créer un compte en fournissant des informations exactes et à jour. Il est responsable de la confidentialité de ses identifiants et de toute activité réalisée depuis son compte. 

 

The Void se réserve le droit de refuser la création d’un compte ou de supprimer un compte existant à sa seule discrétion, en cas de violation des CGU ou pour toute autre raison jugée appropriée. 

Article 6 – Propriété Intellectuelle  

Le Contenu du Site, y compris la structure, le design, les textes, images et bases de données, est protégé par les lois relatives à la propriété intellectuelle. Toute reproduction ou représentation non autorisée est strictement interdite et constitue une contrefaçon. 

En cas de constatation d’une violation des droits de propriété intellectuelle, l’utilisateur est invité à contacter l’éditeur pour signaler ces abus. 

Article 7 – Responsabilités  

Responsabilité de l’Utilisateur L’Utilisateur utilise le site sous sa responsabilité exclusive. Cela inclut toutes les actions réalisées sur la plateforme et les conséquences qui peuvent en découler. 

 

Limitation de la responsabilité de l’éditeur L’éditeur décline toute responsabilité pour les dommages directs et indirects résultant de l’utilisation du site ou de l’impossibilité d’y accéder. Cela peut inclure des pertes financières, des pertes de données, ou toute autre forme de préjudice. 

Article 8 – Données Personnelles  

L'éditeur s'engage à respecter la réglementation en vigueur sur la protection des données personnelles (RGPD). Les données collectées sont utilisées uniquement dans le cadre des services proposés par le Site et peuvent être supprimées sur demande. 

 

Si vous estimez que vos droits ne sont pas respectés, vous avez le droit de déposer une plainte auprès de la CNIL, l’Autorité française de protection des données www.cnil.fr . 

Article 9 – Engagements de l'Utilisateur 

Dans le cadre de l’utilisation de notre service, l’utilisateur s’engage à respecter les conditions suivantes : 

Obligation de L’Utilisateur L’ Utilisateur s’engage à adopter un comportement respectueux envers les autres utilisateurs. Cela inclut l’interdiction de tenir des propos offensants, discriminatoires ou diffamatoires. 

 

L’utilisateur s’engage à ne pas utiliser le service à des fins malveillantes, notamment pour diffuser des virus, des logiciels malveillants ou pour tenter d’accéder à des systèmes ou données non autorisées. 

 

Signalement de Comportements Inappropriés L’Utilisateur est encouragé à signaler tout comportement inapproprié qu’il observe sur la plateforme. 

Les comportements inappropriés incluent, mais ne se limitent pas aux harcèlement, discours de haine, diffamation, contenue sexuellement explicite et spam. 

L’identité des utilisateurs qui signalent des comportements inappropriés sera protégée, sauf si la loi exige de divulguer cette information. 

Sécurité des comptes Chaque utilisateur est responsable de la sécurité de son compte, y compris le choix d’un mot de passe fort et la protection de ses identifiants de connexion. 

 

The Void décline toute responsabilité en cas de perte ou de dommages résultant d’une négligence de la part de l’utilisateur dans la protection de son compte. 

Article 10 – Liens Hypertextes  

Le Site peut contenir des liens vers des sites tiers. L'éditeur ne saurait être tenu responsable du contenu ou de la disponibilité de ces sites. 

 

L’utilisateur est encouragé à prendre le temps de lire attentivement les CGU et les politiques de confidentialité des sites vers lesquels il se rend. 

Article 11 – Modification des CGU  

L'éditeur se réserve le droit de modifier les présentes CGU à tout moment. Les modifications entrent en vigueur dès leur publication sur le Site. 

 

Il est de la responsabilité de l’utilisateur de consulter régulièrement les CGU pour être informé des éventuelles modifications. L’utilisation continue du site après la publication des modifications constitue une acceptation des nouvelles conditions. 

Article 12 – Droit Applicable et Juridiction Compétente  

Les présentes CGU sont régies par le droit français. Tout litige relatif à leur interprétation et exécution relève de la compétence exclusive des tribunaux de Rennes. 

Article 13 – Contact  

Pour toute question relative aux présentes CGU, l'Utilisateur peut contacter l'éditeur à l'adresse suivante : ewen@jain-etudiants.univ-rennes1.com . Nous nous engageons à répondre à toutes les demandes dans un délai de 5 à 7 jours ouvrables. 

 

Conditions Générales de Ventes 

1. Préambule 
Les présentes Conditions Générales de Vente (CGV) régissent les relations contractuelles entre le site PACT et les professionnels (ci-après "les Clients") souhaitant publier des offres via des abonnements et options spécifiques. 
En souscrivant un abonnement ou des options, le Client accepte sans réserve les présentes CGV. 
2. Objet 
Le Site propose aux professionnels : 

Des abonnements pour publier leurs offres, facturés en fonction du nombre de jour de mise en ligne. 

Des options pour mettre en avant leurs offres sur la plateforme (ci-après "Options de mise en avant"). 

3. Abonnements 

3.1. Types d’abonnements 

Professionnels publics : Les abonnements sont gratuits pour la publication d’offres. Cependant, les options de mise en avant sont payantes (voir article 4). 

Professionnels privés : 

Abonnement Basique : 1,67 € HT par jour de mise en ligne d’une offre. 

Abonnement Premium : 3,34 € HT par jour de mise en ligne d’une offre. Cet abonnement inclut : 

Les mêmes avantages que l’abonnement Basique. 

La possibilité de blacklister jusqu’à 3 avis pour l’offre premium concernée, avec récupération de 1 droit de blacklistage par an. 

3.2. Facturation 

La facturation des abonnements est calculée en fonction du nombre de jours pendant lesquels l’offre est en ligne. Les jours où l’offre est hors ligne ne sont pas facturés. 

Les prix sont indiqués hors taxes (HT). 

3.3. Suspension et résiliation 

Le Client peut suspendre ou retirer une offre à tout moment via son espace professionnel. Aucune facturation n’est appliquée pour les périodes où l’offre est hors ligne. 

En cas de manquement grave du Client aux présentes CGV, le Site se réserve le droit de suspendre ou résilier l’abonnement sans préavis. 
 

4. Options de mise en avant 

4.1. Description des options 

Les Clients peuvent choisir des options supplémentaires pour augmenter la visibilité de leurs offres : 

 

Option "À la Une" : 

L’offre apparaît dans une section dédiée sur la page d’accueil. 

Coût : 16,66 € HT par semaine. 

Durée : de 1 à 4 semaines (au choix du Client). 

Option "En Relief" : 

L’offre est mise en exergue dans les listes de résultats avec une couronne distinctive. 

Coût : 8,33 € HT par semaine. 

Durée : de 1 à 4 semaines (au choix du Client). 

4.2. Souscription et activation 

Les options doivent être sélectionnées et payées à la fin du mois de lancement de celle-ci. 

Une fois activée, l’option ne peut être modifiée, elle peut être annulée, mais elle sera facturée en intégralité pour la période choisie. 

5. Paiement 

Modalités de paiement : Les paiements sont effectués via les moyens proposés sur le Site (carte bancaire, virement et PayPal). 

Facturation mensuelle : Les abonnements et options sont facturés tous les 1ers du mois pour les services rendus au cours du mois précédent. 

6. Annulation et remboursement 

Les abonnements et options sont non remboursables après activation, sauf en cas d’erreur imputable au Site. 

Les offres suspendues ou retirées par le Client n’entraînent pas de remboursement, mais arrêtent la facturation à partir du jour de la suspension. 

7. Responsabilités du Client 

Exactitude des informations : Le Client garantit que les informations publiées sont véridiques et conformes aux lois en vigueur. 

Respect des règles éditoriales : Les offres et options doivent respecter les politiques du Site. 

Utilisation des fonctionnalités Premium : Le droit de blacklistage des avis est limité à 3 avis par offre premium, avec une récupération annuelle de 1 droit supplémentaire. 

8. Responsabilités du Site 

Le Site agit comme une plateforme intermédiaire et ne garantit pas le succès des offres ou leur visibilité en dehors des options activées par le Client. 

En cas de suspension ou suppression d’une offre pour non-respect des règles, aucune compensation ou remboursement ne sera accordé. 

 

 

 

9. Litiges et droit applicable 

Les présentes CGV sont régies par le droit français. 

En cas de litige, une solution amiable sera privilégiée. À défaut, le litige sera soumis aux tribunaux compétents. 

10. Acceptation des CGV 

En souscrivant un abonnement ou une option, le Client reconnaît avoir pris connaissance des présentes CGV et les accepte sans réserve. 

 