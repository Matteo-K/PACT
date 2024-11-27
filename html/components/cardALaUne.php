<form action="detailsOffer.php" method="post">
  <input type="hidden" name="" value="">
  <figure>
  <?php $alt = isset($urlImg['url']) && $urlImg ? "photo_principal_de_l'offre" : "Pas_de_photo_attribué_à_l'offre";?>
    <img src="<?php echo $urlImg; ?>" alt=<?php echo $alt; ?>>
    <figcaption>
      <h3><?php echo $nomOffre ?></h3>
    </figcaption>
  </figure>
</form>