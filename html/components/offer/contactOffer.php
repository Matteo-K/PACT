<?php 
  $mail = "";
  $phone = "";
  $DisplayNumber = true;
  $linkWeb = "https://";
?>
<form id="contactOffer" action="enregOffer.php" method="post">
  <label for="mail">Adresse mail de contact pour votre offre*&nbsp;:&nbsp;</label>
  <input type="email" name="mail" id="mail" required="required" placeholder="Adresse mail - (exemple@mail.com)">
  <label for="phone">Numéro de fixe&nbsp;:&nbsp;</label>
  <input type="tel" name="phone" id="phone" placeholder="Numéro fixe">
  <div>
    <h4>Consentez vous à afficher votre numéro de portable sur l’offre &nbsp;?&nbsp;</h4>
    <div>
      <input type="radio" name="DisplayNumber" id="Oui" checked>
      <label for="Oui">Oui</label>
    </div>
    <div>
      <input type="radio" name="DisplayNumber" id="Non">
      <label for="Non">Non</label>
    </div>
  </div>
  <label for="webSide">Si vous avez un site web pour votre offre, vous pouvez insérer son lien ici pour qu’il apparaîsse sur l’offre&nbsp;:&nbsp;</label>
  <input type="text" name="webSide" id="webSide" placeholder="Lien vers votre site web" value="https://">