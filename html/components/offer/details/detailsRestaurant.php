
<section id="restaurant">
  <h4>Gamme de prix : </h4>
  <input type="radio" name="gamme_prix" id="€" value="€" <?php echo $gamme["€"] ? "checked" : "" ?>>
  <label for="€">&euro; (menu à moins de 25€)</label>
  <input type="radio" name="gamme_prix" id="€€" value="€€" <?php echo $gamme["€€"] ? "checked" : "" ?>>
  <label for="€€">&euro;&euro; (menu de 25€ à 40€)</label>
  <input type="radio" name="gamme_prix" id="€€€" value="€€€" <?php echo $gamme["€€€"] ? "checked" : "" ?>>
  <label for="€€€">&euro;&euro;&euro; (menu à plus de 40€)</label>
</section>

<section id="restaurant">
  <h4>Gamme de prix :</h4>
  <label for="€">
    <input type="radio" name="gamme_prix" id="€" value="€" <?php echo $gamme["€"] ? "checked" : "" ?>>
    &euro; (menu à moins de 25€)
  </label>
  <label for="€€">
    <input type="radio" name="gamme_prix" id="€€" value="€€" <?php echo $gamme["€€"] ? "checked" : "" ?>>
    &euro;&euro; (menu de 25€ à 40€)
  </label>
  <label for="€€€">
    <input type="radio" name="gamme_prix" id="€€€" value="€€€" <?php echo $gamme["€€€"] ? "checked" : "" ?>>
    &euro;&euro;&euro; (menu à plus de 40€)
  </label>
</section>
``
