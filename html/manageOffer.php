<?php
  require_once 'config.php'; 

  if(!isset($_SESSION['idUser'])){
      header("Location: index.php");
      exit();
  }

  if (!($typeUser == "pro_public" || $typeUser == "pro_prive")) {
    header("Location: index.php");
    exit();
  }

  $nameOffer = "";
  $step =  isset($_POST["page"]) ? $_POST["page"] : 1;
  $idOffre = isset($_POST["idOffre"])?$_POST["idOffre"]:"";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Change le titre par le nom de l'offre -->
  <title><?php echo isset($nameOffer)? $nameOffer : "Gestion de l'offre" ?></title>
  <link rel="icon" href="img/logo.png" type="image/x-icon">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <link rel="stylesheet" href="style.css">
</head>
<body id="manageOffer">
<script src="js/setColor.js"></script>
  <?php require_once "components/header.php" ?>
  <main>
    <aside>
      <!-- Création ou modification -->
      <h3>Gestion de votre offre</h3>
      <ul>
        <!-- Redirige vers une page qui va sauvegarder les données puis redirige à la bonne page -->
        <li><button <?php echo empty($idOffre)? "" : "disabled"?> id="<?php echo empty($idOffre)? "" : "disabledSelect"?>" type="submit" onclick="submitForm(event,1)" class="<?php echo $step == 1 ? "guideSelect" : "" ?>">Sélection de l’abonnement</button></li>
        <li><button type="submit" onclick="submitForm(event,2)" class="<?php echo $step == 2 ? "guideSelect" : "" ?>">Détails de l’offre</button></li>
        <li><button type="submit" onclick="submitForm(event,3)" class="<?php echo $step == 3 ? "guideSelect" : "" ?>">Localisation</button></li>
        <li><button type="submit" onclick="submitForm(event,4)" class="<?php echo $step == 4 ? "guideSelect" : "" ?>">Contact</button></li>
        <li><button type="submit" onclick="submitForm(event,5)" class="<?php echo $step == 5 ? "guideSelect" : "" ?>">Horaires</button></li>
        <li><button type="submit" onclick="submitForm(event,6)" class="<?php echo $step == 6 ? "guideSelect" : "" ?>">Prévisualiser l’offre</button></li>
        <li><button type="submit" onclick="submitForm(event,7)" class="<?php echo $step == 7 ? "guideSelect" : "" ?>">Paiement</button></li>
      </ul>
      <ul>
        <!-- Si 0 on enregistre et retourne au menu du professionnel -->
        <li><button type="submit" onclick="submitForm(event,0)" class="guideComplete">Sauvegarder & Quitter</button></li>
        <!-- Si -1 on retourne au menu du professionnel sans enregistrer -->
        <li><button type="submit" onclick="submitForm(event,-1)" class="guideStartComplete">Quitter</button></li>
      </ul>
    </aside>
    <section>
      <?php
        // Affichage du formulaire suivant l'étape indiquer par un chiffre dans la barre de recherche avec un require
        $partOffer;
        switch ($step) {
          case 1:
            $partOffer = "selectOffer.php";
            break;
          case 2:
            $partOffer = "detailsOffer.php";
            break;
          case 3:
            $partOffer = "localisationOffer.php";
            break;
          case 4:
            $partOffer = "contactOffer.php";
            break;
          case 5:
            $partOffer = "hourlyOffer.php";
            break;
          case 6:
            $partOffer = "previewOffer.php";
            break;
          case 7:
            $partOffer = "paymentOffer.php";
            break;
          default:
            $partOffer = "errorOffer.php";
            break;
        }
        require_once "components/offer/".$partOffer;
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
      if ($step > 2) {
        ?>
        <button type="submit" onclick="submitForm(event,<?php echo $step-1?>)" class="blueBtnOffer">Précédent</button>
        <?php
      }
      ?>
      <!-- Suivant -->
      <?php
      if ($step < 7) {
        ?>
        <button type="submit" onclick="submitForm(event,<?php echo $step+1?>)" class="guideSelect">Suivant</button>
        <?php
      }
      ?>
    </div>
    <?php if (isset($_POST["save"])) { ?>
      <div id="save-offer">
        <div id="loading-logo"></div>
        <figure id="valid-logo">
          <div>&#10003;</div>
          <figcaption>Sauvegardé</figcaption>
        </figure>
        <img src="img/icone/croix.png" alt="ferme feedback save" onclick="closeSave()">
      </div>
    <?php } ?>
  </main>
  <?php require_once "components/footer.php"; ?>
</body>
<script src="js/script.js"></script>
<script>
  // FeedBack visuel sauvegarde

  const saveAside = document.getElementById("save-offer");
  window.onload = () => {
    if (saveAside) {
      setTimeout(function() {
        document.querySelector("#save-offer img").style.display = 'block';
        document.getElementById('loading-logo').style.display = 'none';
        document.getElementById('valid-logo').style.display = 'flex';
        saveAside.style.borderColor = "#1ca4ed";
        saveAside.style.backgroundColor = "white";
        saveAside.style.boxShadow = '#034d7c 4px 4px 4px';
      }, 1000); // 1s
      
      setTimeout(() => closeSave(), 3000); // 3s 
    }
  }

  function closeSave() {
    saveAside.style.display = "none";
  }
  
  /* envoie un formulaire à la page enregistrement (enregOffer.php) */
  function submitForm(event, page) {
      document.getElementById('pageCurrent').value = page;
      let form = document.querySelector('section form:not(#paypal)');
      let confirm_quit = (page == 0)? confirm("Les données seront enregistrées.\n Vous pourrez reprendre vos modifications plus tard.\n Il faut compléter toute les données obligatoires pour quitter") : true;
      let confirm_annule = (page == -1)? confirm("Les données ne seront pas enregistrées.\n Toute modification apportée aux données ne sera pas prise en compte.") : false;

      // Si le professionnel annule, on le redirige vers la page d'acceuil
      if (confirm_annule) {
          window.location.href = "index.php"; 
      }

      // Sinon on vérifie si le formulaire est correcte
      let verifStep = true;
      if (typeof checkOfferValidity === 'function') {
        verifStep = checkOfferValidity(event);
      }
      
      if (verifStep) {
        if (form.checkValidity() && confirm_quit) {
          form.submit();
        } else {
          form.reportValidity();
        }
      } else {
        form.reportValidity();
      }
  }
</script>
</html>