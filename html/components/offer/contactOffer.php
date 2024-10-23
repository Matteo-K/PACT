<?php 
  // Requête de récupération des données avec l'id de l'offre
  $stmt = $conn->prepare("SELECT mail, telephone, affiche, urlsite FROM pact._offre WHERE idoffre= ?");
  $stmt->execute([$idOffre]);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($result === false) {
    $mail = "";
    $phone = "";
    $displayNumber = true;
    $linkWeb = "https://";
  } else {
    $mail = $result["mail"];
    $phone = $result["telephone"] == null ? "" : $result["telephone"];
    $displayNumber = $result["affiche"] == null ? true : $result["affiche"];
    $linkWeb = $result["urlsite"] == null ? "https://" : $result["urlsite"];
  }
?>
<form id="contactOffer" action="enregOffer.php" method="post">
  <label for="mail">Adresse mail de contact pour votre offre*&nbsp;:&nbsp;</label>
  <input type="email" name="mail" id="mail" required="required" placeholder="Adresse mail - (exemple@mail.com)" value="<?php echo $mail; ?>">
  <label for="phone">Numéro de fixe&nbsp;:&nbsp;</label>
  <div>
    <input type="tel" name="phone" id="phone" placeholder="Numéro fixe" value="<?php echo $phone; ?>"> <label></label>
  </div>
  <div>
    <h4>Consentez vous à afficher votre numéro de portable sur l’offre &nbsp;?&nbsp;</h4>
    <div>
      <input type="radio" name="DisplayNumber" id="Oui" value="Oui" <?php echo $displayNumber?"checked":""?>>
      <label for="Oui">Oui</label>
    </div>
    <div>
      <input type="radio" name="DisplayNumber" id="Non" value="Non" <?php echo !$displayNumber?"checked":""?>>
      <label for="Non">Non</label>
    </div>
  </div>
  <label for="webSide">Si vous avez un site web pour votre offre, vous pouvez insérer son lien ici pour qu’il apparaîsse sur l’offre&nbsp;:&nbsp;</label>
  <input type="text" name="webSide" id="webSide" placeholder="Lien vers votre site web" value="<?php echo $linkWeb; ?>">