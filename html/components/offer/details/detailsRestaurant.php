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
<section id="restaurant">
  <h4>Gamme de prix : </h4>
  <div>
  <input type="radio" name="gamme_prix" id="€" value="€" <?php echo $gamme["€"] ? "checked" : "" ?>>
  <label for="€">&euro; (menu à moins de 25€)</label>
  </div>
  <div>
  <input type="radio" name="gamme_prix" id="€€" value="€€" <?php echo $gamme["€€"] ? "checked" : "" ?>>
  <label for="€€">&euro;&euro; (menu de 25€ à 40€)</label>
  </div>
  <div>
  <input type="radio" name="gamme_prix" id="€€€" value="€€€" <?php echo $gamme["€€€"] ? "checked" : "" ?>>
  <label for="€€€">&euro;&euro;&euro; (menu à plus de 40€)</label>
  </div>
</section>