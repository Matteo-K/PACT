<?php
$is_prive = $_SESSION["typeUser"] == "pro_prive";

// Définit le type d'abonnement lors de la modification
// Si c'est lors de la modification, alors on ne peut pas la changé.
if ($is_prive) {
  if (!empty($idOffre)) {
    $stmt = $conn->prepare("SELECT nomabonnement FROM pact._abonner WHERE idoffre = ? ");
    $stmt->execute([$idOffre]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result["nomabonnement"] == "Premium") {
      $premium = "checked";
      $standard = "disabled";
    } else {
      // abonnement standard
      $premium = "disabled";
      $standard = "checked";
    }
  } else {
    $premium = "checked";
    $standard = "";
  }
}
  
// insert les options de l'offre dans un tableau
$options = [];
if (!empty($idOffre)) {
  $stmt = $conn->prepare("SELECT nomoption FROM pact._option_offre WHERE idoffre=?");
  $stmt->execute([$idOffre]);
  // si les options éxistent, on les ajoutent dans la base de donnée
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $options[] = $row["nomoption"];
  }
}

$abonnement = [];
$stmt = $conn->prepare("SELECT nomabonnement, tarif FROM pact._abonnement");
$stmt->execute();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $abonnement[] = ["nomabonnement" => $row["nomabonnement"], "tarif" => $row["tarif"]];
}

$prixOption = [];
$stmt = $conn->prepare("SELECT nomoption, prixoffre FROM pact._option");
$stmt->execute();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $prixOption[] = ["nomoption" => $row["nomoption"], "tarif" => $row["prixoffre"]];
}

?>
<form id="selectOffer" action="enregOffer.php" method="post">
  <div>
    <?php
    if (!$is_prive) {
    ?>
    <div>
      <h2>Offre Gratuit</h2>
      <?php
      foreach ($abonnement as $ab) {
        if ($ab['nomabonnement'] === "Gratuit") {
            ?>
            <h3 prix="<?php echo htmlspecialchars($ab['tarif']) ?>"> <?php echo htmlspecialchars($ab['tarif']) ?> &euro;&nbsp;/&nbsp;jour</h3>
            <?php
            break;
        }
      }
      ?>
      <ul>
        <li>Réservée au public</li>
      </ul>
      <div>
        <input type="radio" name="typeOffre" id="gratuit" value="gratuit" checked>
        <label for="gratuit">Sélectionner</label>
      </div>
    </div>
    <?php
    } else {
    ?>
    <div>
      <h2>Offre Premium</h2>
      <?php
      foreach ($abonnement as $ab) {
        if ($ab['nomabonnement'] === "Premium") {
            ?>
            <h3 prix="<?php echo htmlspecialchars($ab['tarif']) ?>"> <?php echo htmlspecialchars($ab['tarif']) ?> &euro;&nbsp;/&nbsp;jour</h3>
            <?php
            break;
        }
      }
      ?>
      <ul>
        <li>Réservée au privé</li>
        <li>Saisie d’une grille tarifaire</li>
        <li>Blackliste sur 3 avis</li>
      </ul>
      <div>
        <input type="radio" name="typeOffre" id="premium" value="premium" <?php echo $premium ?>>
        <label for="premium">Sélectionner</label>
      </div>
    </div>
    <div>
      <h2>Offre Standard</h2>
      <?php
      foreach ($abonnement as $ab) {
        if ($ab['nomabonnement'] === "Basique") {
            ?>
            <h3 prix="<?php echo htmlspecialchars($ab['tarif']) ?>"> <?php echo htmlspecialchars($ab['tarif']) ?> &euro;&nbsp;/&nbsp;jour</h3>
            <?php
            break;
        }
      }
      ?>
      <ul>
        <li>Réservée au privé</li>
        <li>Saisie d’une grille tarifaire</li>
      </ul>
      <div>
        <input type="radio" name="typeOffre" id="standard" value="standard" <?php echo $standard ?>>
        <label for="standard">Sélectionner</label>
      </div>
    </div>
    <?php
    }
    ?>
  </div>
  <div>
    <div>
      <?php
      foreach ($prixOption as $opt) {
        if ($opt['nomoption'] === "EnRelief") {
            ?>
            <span prix="<?php echo htmlspecialchars($opt['tarif']) ?>">
              (&nbsp;+<?php echo htmlspecialchars($opt['tarif']) ?> &euro;/&nbsp;)
            </span>
            <?php
            break;
        }
      }
      ?>
      <input type="checkbox" name="enRelief" id="enRelief" <?php echo in_array('ALaUne',$options)?"checked":"" ?>>
      <label for="enRelief"><span>En relief</span> : met votre offre en exergue lors de son affichage dans la liste d’offres</label></div>
    <div>
      <?php
      foreach ($prixOption as $opt) {
        if ($opt['nomoption'] === "ALaUne") {
            ?>
            <span prix="<?php echo htmlspecialchars($opt['tarif']) ?>">
              (&nbsp;+<?php echo htmlspecialchars($opt['tarif']) ?> &euro;&nbsp;)
            </span>
            <?php
            break;
        }
      }
      ?>
      <input type="checkbox" name="aLaUne" id="aLaUne" <?php echo in_array('EnRelief',$options)?"checked":"" ?>>
      <label for="aLaUne"><span>À la une</span> : met votre offre sur la page d’accueil du site</label>
    </div>
    <p>Attention ! Vous ne pouvez pas changer d’offre une fois séléctionée.</p>
    <div>Montant actuelle : <span id="prixPrevisionel"></span>&euro;</div>
  </div>

  <script>
    const prixPrevisionnel = document.querySelector("#prixPrevisionel");
    const radio = document.querySelectorAll("[type='radio']");
    const option = document.querySelectorAll('[type="checkbox"]');

    document.getElementById('prixMin').addEventListener('change', () => {
      let prix = 0;

      radio.forEach(element => {
        if (element.checked) {
          const tarifOffre = element.parentElement.parentElement.querySelector('h3').getAttribute('prix');
          prix += parseFloat(tarifOffre);
        }
      });

      option.forEach(element => {
        if (element.checked) {
          const tarifOption = element.parentElement.querySelector('span').getAttribute('prix');
          prix += parseFloat(tarifOption);
        }
      });

      prixPrevisionnel.innerText = prix.toFixed(2);
    });

    radio.forEach(element => {
      element.addEventListener("click", updatePrix);
    });

    option.forEach(element => {
      element.addEventListener("click", updatePrix);
    });

    updatePrix();

  </script>
  