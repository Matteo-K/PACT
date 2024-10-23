<?php
  // Démarrer la session
  session_start();

  // fichier de connexion à la BDD
  require_once 'db.php'; 

  if(!isset($_SESSION['idUser'])){
      header("Location: index.php");
      exit();
  }


  if (!($_SESSION["typeUser"] == "pro_public" || $_SESSION["typeUser"] == "pro_prive")) {
    header("Location: index.php");
    exit();
} 


  $nameOffer = "";
  $step = isset($_POST["page"]) ? $_POST["page"] : 1;
  $idOffre = isset($_POST["idOffre"])?$_POST["idOffre"]:"";
  require_once "components/offer/checkOffer.php";
  $step = 2;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Change le titre par le nom de l'offre -->
  <title><?php echo isset($nameOffer)? $nameOffer : "Gestion de l'offre" ?></title>
  <link rel="icon" href="img/logo.png" type="image/x-icon">
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
        <li><a onclick="submitForm(1)" class="<?php echo $step == 1 ? "guideSelect" : checkSelectOffer($idOffre) ?>">Sélection de l’offre</a></li>
        <li><a onclick="submitForm(2)" class="<?php echo $step == 2 ? "guideSelect" : checkDetailsOffer($idOffre) ?>">Détails de l’offre</a></li>
        <li><a onclick="submitForm(3)" class="<?php echo $step == 3 ? "guideSelect" : checkLocalisationOffer($idOffre) ?>">Localisation</a></li>
        <li><a onclick="submitForm(4)" class="<?php echo $step == 4 ? "guideSelect" : checkContactOffer($idOffre) ?>">Contact</a></li>
        <li><a onclick="submitForm(5)" class="<?php echo $step == 5 ? "guideSelect" : checkHourlyOffer($idOffre) ?>">Horaires</a></li>
        <li><a onclick="submitForm(6)" class="<?php echo $step == 6 ? "guideSelect" : checkPreviewOffer($idOffre) ?>">Prévisualiser l’offre</a></li>
        <li><a onclick="submitForm(7)" class="<?php echo $step == 7 ? "guideSelect" : checkPayementOffer($idOffre) ?>">Paiement</a></li>
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
          <input type="hidden" name="pageCurrent" id="pageCurrent" value="">
          <input type="hidden" name="pageBefore" id="pageBefore" value="<?php echo $step ?>">
          <input type="hidden" name="idOffre" id="idOffre" value="<?php echo $idOffre ?>">
          <input type="hidden" name="idUser" id="idUser" value="<?php echo $_SESSION['idUser']; ?>">
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
      document.getElementById('pageCurrent').value = page;
      let form = document.querySelector('section form:not(#paypal)');
      let confirm_page = (page == -1)? confirm("Les données ne seront pas enregistrées.\n Toute modification apportée aux données ne sera pas prise en compte.") : true;
      if (form.checkValidity() && confirm_page) {
        form.submit();
      } else {
        form.reportValidity();
      }
  }
</script>
</html>