<?php
$nameOffer = "";
$step = isset($_GET["page"]) ? $_GET["page"] : 1 ;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Change le titre par le nom de l'offre -->
  <title><?php echo isset($nameOffer)? $nameOffer : "Gestion de l'offre" ?></title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <?php require_once "components/header.php" ?>
  <aside>
    <!-- Création ou modification -->
    <h2>de votre offre</h2>
    <ul>
      <!-- Redirige vers une page qui va sauvegarder les données puis redirige à la bonne page -->
      <li><a href="enregOffer.php?page=1">Sélection de l’offre</a></li>
      <li><a href="enregOffer.php?page=2">Détails de l’offre</a></li>
      <li><a href="enregOffer.php?page=3">Localisation</a></li>
      <li><a href="enregOffer.php?page=4">Contact</a></li>
      <li><a href="enregOffer.php?page=5">Horaires</a></li>
      <li><a href="enregOffer.php?page=6">Prévisualiser l’offre</a></li>
      <li><a href="enregOffer.php?page=7">Paiement</a></li>
    </ul>
    <ul>
      <!-- Si 0 on enregistre et retourne au menu du professionnel -->
      <li><a href="enregOffer.php?page=0">Quitter</a></li>
      <!-- Si -1 on retourne au menu du professionnel sans enregistrer -->
      <li><a href="enregOffer.php?page=-1">Annuler</a></li>
    </ul>
  </aside>
  <?php
    // Affichage du formulaire suivant l'étape indiquer par un chiffre dans la barre de recherche avec un require
    switch ($step) {
      case 1:
        require_once "components/offer/selectOffer.php";
        break;
      case 2:
        require_once "components/offer/detailsOffer.php";
        break;
      case 3:
        require_once "components/offer/localisationOffer.php";
        break;
      case 4:
        require_once "components/offer/contactOffer.php";
        break;
      case 5:
        require_once "components/offer/hourlyOffer.php";
        break;
      case 6:
        require_once "components/offer/previewOffer.php";
        break;
      case 7:
        require_once "components/offer/paymentOffer.php";
        break;
      
      default:
        echo "Page inconnue";
        break;
    }
  ?>
  <div>
    <!-- Bouton précédent et suivant -->
     <?php
     // Précédent
     if ($step > 1) {
      ?>
      <a href="enregOffer.php?page=<?php echo $step-1?>">Précédent</a>
      <?php
     }
     ?>
     <!-- Suivant -->
    <a href="enregOffer.php?page=<?php echo $step+1?>">Suivant</a>
  </div>
  <?php require_once "components/footer.php"; ?>
</body>
<script src="js/script.js"></script>
</html>