<!-- Restaurant -->
<?php
// Initialisation des données à vide
$gamme = [
  "€" => true,
  "€€" => false,
  "€€€" => false
];

// Si le restaurant était déjà existante, on récupère les données
if ($categorie["_restauration"]) {
  $stmt = $conn->prepare("SELECT * from pact._restauration where idoffre=?");
  $stmt->execute([$idOffre]);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($result) {
    $gamme["€"] = false;
    $gamme[$result["gammedeprix"]] = true;
  }
}

?>
<section id="restaurant"> <!-- Section pour le CSS -->
  <h4>Gamme de prix : </h4>

  <!-- Boutons radio pour la sélection de la gamme de prix , seul 1 des 3 peut être coché à la fois car il n'y a que une seule gamme de prix par restaurant-->
  <div>
    <input type="radio" name="rest_gamme_prix" id="€" value="€" <?php echo $gamme["€"] ? "checked" : "" ?>> <!-- Bouton radio gamme de prix €
    Est groupe avec les 2 autres pour que un seul puisse être selectionner a la fois-->
    <label for="€">&euro; (menu à moins de 25€)</label>
  </div>
  <div>
    <input type="radio" name="rest_gamme_prix" id="€€" value="€€" <?php echo $gamme["€€"] ? "checked" : "" ?>><!-- Bouton radio gamme de prix €€
    Est groupe avec les 2 autres pour que un seul puisse être selectionner a la fois-->
    <label for="€€">&euro;&euro; (menu de 25€ à 40€)</label>
  </div>
  <div>
    <input type="radio" name="rest_gamme_prix" id="€€€" value="€€€" <?php echo $gamme["€€€"] ? "checked" : "" ?>> <!-- Bouton radio gamme de prix €€€
    Est groupe avec les 2 autres pour que un seul puisse être selectionner a la fois-->
    <label for="€€€">&euro;&euro;&euro; (menu à plus de 40€)</label>
  </div>

  <!-- Partie pour l'ajout des photos du menu du restaurant-->
  <div class="photosR">
    <label class="labelPhotos">Photos du menu* </label>
    <label class="labNbPhotos">Vous pouvez insérer 5 photos de votre menu</label> <!-- Indication pour l'utilisateur -->
    <label class="labelSuppPhotos"> Cliquez sur une image pour la supprimer</label>
  </div>

  <label for="rest_ajoutPhotoMenu" class="buttonDetailOffer blueBtnOffer">Ajouter</label>
  <input type="file" id="rest_ajoutPhotoMenu" name="rest_ajoutPhotoMenu[]"
    accept="image/PNG, image/JPG, image/JPEG, image/WEBP, image/GIF" method="post" multiple> <!-- Les différents type d'images acceptée -->
    
  <div id="afficheImages" class="imgRestaurant"></div> <!-- Zone pour afficher les images -->
</section>