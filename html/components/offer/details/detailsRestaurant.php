<!-- Restaurant -->
<?php
$stmt = $conn->prepare("SELECT * from pact._restauration where idoffre=?");
$stmt->execute([$idOffre]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
if ($result !== false) {
  $gamme = $result["gammedeprix"];
} else {
  $gamme = "";
}
?>
<section id="restaurant">
  <h4>Gamme de prix : </h4>
  <input type="radio" name="gamme_prix" id="€" <?php echo ($gamme == "€" || empty($gamme))? "checked" : "" ?>>
  <label for="€">&euro;   (menu à moins de 25€)</label>
  <input type="radio" name="gamme_prix" id="€€" <?php echo $gamme == "€€"? "checked" : "" ?>>
  <label for="€€">&euro;&euro;  (menu de 25€ à 40€)</label>
  <input type="radio" name="gamme_prix" id="€€€" <?php echo $gamme == "€€€"? "checked" : "" ?>>
  <label for="€€€">&euro;&euro;&euro; (menu à plus de 40€)</label>
</section>  