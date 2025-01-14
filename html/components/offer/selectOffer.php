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

// Sélection des tarifs et abonnements
$abonnement = [];
$stmt = $conn->prepare("SELECT nomabonnement, tarif FROM pact._abonnement");
$stmt->execute();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $abonnement[] = ["nomabonnement" => $row["nomabonnement"], "tarif" => $row["tarif"]];
}

?>
<form id="selectOffer" action="enregOffer.php" method="post">
  <!-- Abonnement -->
  <div>
    <?php
    if (!$is_prive) {
    ?>
    <div class="offre-container">
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
    <div class="offre-container">
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
    <div class="offre-container">
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
  <div>Montant actuel (abonnement sur 1 mois): <span id="prixPrevisionel"></span>&euro;</div>
  </div>

  <script>
    const prixPrevisionnel = document.querySelector("#prixPrevisionel");
    const radio = document.querySelectorAll("[type='radio']");

    // Style
    document.addEventListener('DOMContentLoaded', function() {
      const radioButtons = document.querySelectorAll('input[name="typeOffre"]');
      
      function applyStyle() {
        document.querySelectorAll('.offre-container').forEach(carte => {
          carte.style.transform = '';
          carte.style.boxShadow = '';
        });

        // Appliquer les styles en fonction de la carte sélectionnée
        radioButtons.forEach((radio, index) => {
          const carte = radio.closest('.offre-container');

          if (radio.checked) {
            carte.style.transform = 'skewX(-1deg)';
            if (index%2 === 1) {
              carte.style.boxShadow = 'inset 4px 4px 4px rgba(255, 255, 255, 0.5), inset -4px -4px 4px var(--secondary60), 4px 4px 12px var(--text-dark30)';
            } else {
              carte.style.boxShadow = 'inset 4px 4px 4px rgba(255, 255, 255, 0.5), inset -4px -4px 4px var(--primary60), 4px 4px 12px var(--bloc)';
            }
          }
        });
      }

      // Attache l'événement 'change' pour appliquer les styles à chaque fois qu'un radio est sélectionné
      radioButtons.forEach(radio => {
        radio.addEventListener('change', applyStyle);
      });

      // Appliquer les styles au chargement si déjà sélectionné
      applyStyle();
    });


    function updatePrix() {
      let prix = 0;

      // Abonnement
      radio.forEach(element => {
        if (element.checked) {
          const tarifOffre = element.parentElement.parentElement.querySelector('h3').getAttribute('prix');
          prix += parseFloat(tarifOffre)*30;
        }
      });

      prixPrevisionnel.innerText = prix.toFixed(2);
    }

    radio.forEach(element => {
      element.addEventListener("click", updatePrix);
    });

    updatePrix();

  </script>
  