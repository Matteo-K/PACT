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
    array_push($res, $row["nomoption"]);
  }
  print_r($res);
}

?>
<form id="selectOffer" action="enregOffer.php" method="post">
  <div>
    <?php
    if (!$is_prive) {
    ?>
    <div>
      <h2>Offre Gratuit</h2>
      <h3>0&euro;&nbsp;/&nbsp;mois</h3>
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
      $stmt = $conn->prepare("SELECT o.idoffre FROM pact._offre o ORDER BY idoffre DESC LIMIT 1");
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
    ?>
    <div>
      <h2>Offre Premium</h2>
      <h3>119,99&euro;&nbsp;/&nbsp;mois</h3>
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
      <h3>79,99&euro;&nbsp;/&nbsp;mois</h3>
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
      <span>(&nbsp;+14.99&euro;&nbsp;)</span>
      <input type="checkbox" name="enRelief" id="enRelief" <?php echo in_array('ALaUne',$options)?"checked":"" ?>>
      <label for="enRelief"><span>En relief</span> : met votre offre en exergue lors de son affichage dans la liste d’offres</label></div>
    <div>
      <span>(&nbsp;+19.99&euro;&nbsp;)</span>
      <input type="checkbox" name="aLaUne" id="aLaUne" <?php echo in_array('EnRelief',$options)?"checked":"" ?>>
      <label for="aLaUne"><span>À la une</span> : met votre offre sur la page d’accueil du site</label>
    </div>
    <p>Attention ! Vous ne pouvez pas changer d’offre une fois séléctionée.</p>
  </div>