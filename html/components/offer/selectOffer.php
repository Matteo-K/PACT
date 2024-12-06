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
$options = [
  "enRelief" => false,
  "rlfActif" => false,
  "rlfNbWeek" => 1,
  "rlfFinOpt" => "",
  "ALaUne" => false,
  "aluActif" => false,
  "aluNbWeek" => 1,
  "aluFinOpt" => "",
];

if (!empty($idOffre)) {
  $stmt = $conn->prepare("SELECT * FROM pact._option_offre NATURAL JOIN pact._dateoption WHERE idoffre = ?");
  $stmt->execute([$idOffre]);
  // si les options éxistent, on les ajoutent dans la base de donnée
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    switch ($row["nomoption"]) {
      case "EnRelief":
        $options["enRelief"] = true;
        $options["rlfActif"] = $row["datelancement"] != "" ? true : false;
        $options["rlfNbWeek"] = $row["duree"];
        $options["rlfFinOpt"] = $row["datefin"] != "" ? $row["datefin"] : "";
        break;

      case "ALaUne":
        $options["ALaUne"] = true;
        $options["aluActif"] = $row["datelancement"] != null;
        $options["aluNbWeek"] = $row["duree"];
        $options["aluFinOpt"] = $row["datefin"] != null ? $row["datefin"] : "";
        break;
    }
  }
}

// Sélection des tarifs et abonnements
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
  <!-- Abonnement -->
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
      <span>(prix hors-taxe)</span>
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
            <span>(prix hors-taxe)</span>
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
    <!-- Option -->
    <div>
      <div id="blcEnRelief" class="blcOption">
        <label for="enRelief">
          <?php
          foreach ($prixOption as $opt) {
            if ($opt['nomoption'] === "EnRelief") {
                ?>
                <span prix="<?php echo htmlspecialchars($opt['tarif']) ?>">
                  (&nbsp;+<?php echo htmlspecialchars($opt['tarif']) ?> &euro;/&nbsp;semaines)
                </span>
                <?php
                break;
            }
          }
          ?>
          <input type="checkbox" name="enRelief" id="enRelief" <?php echo $options["ALaUne"] ?"checked":"" ?>>
          <span class="checkmark"></span>
          <span>En relief</span> : met votre offre en exergue lors de son affichage dans la liste d’offres
        </label>
        <div>
          <label for="nbWeekEnRelief">Nombre de semaine&nbsp;:&nbsp;</label>
          <input type="number" name="nbWeekEnRelief" id="nbWeekEnRelief" min="1" max="4" value="<?php echo $options["rlfNbWeek"]?>" <?php echo $options["rlfActif"] ? "disabled" : ""; ?>>
        </div>
        <input type="hidden" name="actifEnRelief" value="<?php echo $options["rlfActif"] ?>">
      </div>
      <?php
      if ($options["rlfActif"]) {
        ?>
        <span class="msgError">Option en relief en cours, modifiable à partir de <?php echo $options['rlfFinOpt'] ?></span>
      <?php
      }
      ?>
      <div id="blcALaUne" class="blcOption">
        <label for="aLaUne">
          <?php
          foreach ($prixOption as $opt) {
            if ($opt['nomoption'] === "ALaUne") {
                ?>
                <span prix="<?php echo htmlspecialchars($opt['tarif']) ?>">
                  (&nbsp;+<?php echo htmlspecialchars($opt['tarif']) ?> &euro;/&nbsp;semaines)
                </span>
                <?php
                break;
            }
          }
          ?>
          <input type="checkbox" name="aLaUne" id="aLaUne" <?php echo $options["ALaUne"]?"checked":"" ?>>
          <span class="checkmark"></span>
          <span>À la une</span> : met votre offre sur la page d’accueil du site
        </label>
        <div>
          <label for="nbWeekALaUne">Nombre de semaine&nbsp;:&nbsp;</label>
          <input type="number" name="nbWeekALaUne" id="nbWeekALaUne" min="1" max="4" value="<?php echo $options["aluNbWeek"]?>" <?php echo $options["aluActif"] ? "disabled" : ""; ?>>
        </div>
        <input type="hidden" name="actifALaUne" value="<?php echo $options["aluActif"] ?>">
      </div>
      <?php
      if ($options["aluActif"]) {
        ?>
        <span class="msgError">Option à la Une en cours, modifiable à partir de <?php echo $options['aluFinOpt'] ?></span>
        <?php
      }
      ?>
    </div>
    <p>Attention ! Vous ne pouvez pas changer d’offre une fois séléctionée.</p>
    <div>Montant actuel (abonnement sur 1 mois): <span id="prixPrevisionel"></span>&euro;</div>
  </div>

  <script>
    const prixPrevisionnel = document.querySelector("#prixPrevisionel");
    const radio = document.querySelectorAll("[type='radio']");
    const option = document.querySelectorAll('[type="checkbox"]');
    const nbSemaines = document.querySelectorAll('[type="number"]');

    function updatePrix() {
      let prix = 0;

      // Abonnement
      radio.forEach(element => {
        if (element.checked) {
          const tarifOffre = element.parentElement.parentElement.querySelector('h3').getAttribute('prix');
          prix += parseFloat(tarifOffre)*30;
        }
      });

      // Option
      for (let index = 0; index < option.length; index++) {
        if (option[index].checked) {
          const tarifOption = option[index].parentElement.querySelector('span').getAttribute('prix');        
          if (tarifOption && nbSemaines[index].value) {
            const tarifValue = parseFloat(tarifOption);
            const nbSemainesValue = parseInt(nbSemaines[index].value, 10);

            if (!isNaN(tarifValue) && !isNaN(nbSemainesValue)) {
              prix += tarifValue * nbSemainesValue;
            }
          }
        }
      }

      prixPrevisionnel.innerText = prix.toFixed(2);
    }

    radio.forEach(element => {
      element.addEventListener("click", updatePrix);
    });

    option.forEach(element => {
      element.addEventListener("click", updatePrix);
    });

    nbSemaines.forEach(element => {
      element.addEventListener("change", () => {
        if (element.value < 1) {
          element.value = 1;
        } else if (element.value > 4) {
          element.value = 4;
        }
      });
      element.addEventListener("change", updatePrix);
    });

    updatePrix();

  </script>
  