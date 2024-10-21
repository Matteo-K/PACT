<?php
$nameOffer = "";
$step = isset($_GET["page"]) ? $_GET["page"] : 1;
require_once "components/offer/checkOffer.php"
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
<body id="manageOffer">
<script src="js/setColor.js"></script>
  <?php require_once "components/header.php" ?>
  <main>
    <aside>
      <!-- Création ou modification -->
      <h3>Création de votre offre</h3>
      <ul>
        <!-- Redirige vers une page qui va sauvegarder les données puis redirige à la bonne page -->
        <li><a onclick="submitForm(1)" class="<?php echo $step == 1 ? "guideSelect" : checkSelectOffer() ?>">Sélection de l’offre</a></li>
        <li><a onclick="submitForm(2)" class="<?php echo $step == 2 ? "guideSelect" : checkDetailsOffer() ?>">Détails de l’offre</a></li>
        <li><a onclick="submitForm(3)" class="<?php echo $step == 3 ? "guideSelect" : checkLocalisationOffer() ?>">Localisation</a></li>
        <li><a onclick="submitForm(4)" class="<?php echo $step == 4 ? "guideSelect" : checkContactOffer() ?>">Contact</a></li>
        <li><a onclick="submitForm(5)" class="<?php echo $step == 5 ? "guideSelect" : checkHourlyOffer() ?>">Horaires</a></li>
        <li><a onclick="submitForm(6)" class="<?php echo $step == 6 ? "guideSelect" : checkPreviewOffer() ?>">Prévisualiser l’offre</a></li>
        <li><a onclick="submitForm(7)" class="<?php echo $step == 7 ? "guideSelect" : checkPayementOffer() ?>">Paiement</a></li>
      </ul>
      <ul>
        <!-- Si 0 on enregistre et retourne au menu du professionnel -->
        <li><a onclick="submitForm(0)" class="guideStartComplete">Quitter</a></li>
        <!-- Si -1 on retourne au menu du professionnel sans enregistrer -->
        <li><a onclick="submitForm(-1)" class="guideStartComplete">Annuler</a></li>
      </ul>
    </aside>
    <section>
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
          require_once "components/offer/errorOffer.php";
            break;
        }
      ?>
          <input type="hidden" name="page" id="currentPage" value="<?php echo $step ?>">
          <input type="hidden" name="idOffre" id="idOffre" value="<?php echo isset($_GET["idOffre"])?$_GET["idOffre"]:"" ?>">
      </form>
    </section>
    <div>
      <!-- Bouton précédent et suivant -->
      <?php
      // Précédent
      if ($step > 1) {
        ?>
        <a onclick="submitForm(<?php echo $step-1?>)" class="blueBtnOffer">Précédent</a>
        <?php
      }
      ?>
      <!-- Suivant -->
      <a onclick="submitForm(<?php echo $step+1?>)" class="guideSelect">Suivant</a>
    </div>
  </main>
  <?php require_once "components/footer.php"; ?>
</body>
<script src="js/script.js"></script>
<script>
  /* envoie un formulaire à la page enregistrement (enregOffer.php) */
  function submitForm(page) {
      document.getElementById('currentPage').value = page;
      let form = document.querySelector('section form');
      console.log(page);
      if (page < 1 || form.checkValidity()) {
        form.submit();
      } else {
        form.reportValidity();
      }
  }
</script>
</html>