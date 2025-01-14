<!-- Restaurant -->
<?php
// Initialisation des données à vide
$gamme = [
  "€" => true,
  "€€" => false,
  "€€€" => false
];
$limitImgMenu = 5;

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
  <div>
    <h4 class="labelTitre">Gamme de prix</h4>
    
    <!-- Gamme de prix -->
    <ul>
      <li>
        <label class="labelSousTitre" for="€">
          <input type="radio" name="rest_gamme_prix" id="€" value="€" <?php echo $gamme["€"] ? "checked" : "" ?>>
          <span class="checkmark"></span>
          &euro; (menu à moins de 25€)
        </label>
      </li>
      <li>
        <label class="labelSousTitre" for="€€">
          <input type="radio" name="rest_gamme_prix" id="€€" value="€€" <?php echo $gamme["€€"] ? "checked" : "" ?>>
          <span class="checkmark"></span>
          &euro;&euro; (menu de 25€ à 40€)
        </label>
      </li>
      <li>
        <label class="labelSousTitre" for="€€€">
          <input type="radio" name="rest_gamme_prix" id="€€€" value="€€€" <?php echo $gamme["€€€"] ? "checked" : "" ?>>
          <span class="checkmark"></span>
          &euro;&euro;&euro; (menu à plus de 40€)
        </label>
      </li>
    </ul>
  </div>
    
  <!-- Partie pour l'ajout des photos du menu du restaurant-->
  <div class="photosR">
      <div id="insereImg">
          <label class="labelTitre">Photos du menu*</label>
          <label for="rest_ajoutPhotoMenu" class="modifierBut">Ajouter</label>
      </div>
      <div id="rest_zoneImg"></div> <!-- Zone pour afficher les images -->
      <label class="labelSousTitre">Vous pouvez insérer <?= $limitImgMenu ?> photos de votre menu</label> <!-- Indication pour l'utilisateur -->
      <label class="labelSousTitre"> Cliquez sur une image pour la supprimer</label>
      <input type="file" id="rest_ajoutPhotoMenu" name="rest_ajoutPhotoMenu[]"
      accept="image/PNG, image/JPG, image/JPEG, image/WEBP, image/GIF" method="post" multiple> <!-- Les différents type d'images acceptée -->
  </div>

</section>
<script>
  // Chargement pour les images de les menus
  loadEventLoadImg(
    document.getElementById('rest_ajoutPhotoMenu'),
    'img/imageMenu/',
    document.getElementById('rest_zoneImg'),
    <?= $limitImgMenu ?>,
    <?= $idOffre ?>
  );
</script>