<form action="detailsOffer.php" method="post">
  <input type="hidden" name="" value="">
  <figure>
  <?php $alt = isset($urlImg['url']) && $urlImg ? "photo_principal_de_l'offre" : "Pas_de_photo_attribué_à_l'offre";?>
    <img src="<?php echo $urlImg; ?>" alt=<?php echo $alt; ?>>
    <figcaption>
      <h3><?php echo $nomOffre ?></h3>
      <div>
        <div id="detailsCardOffer">
          <p><?php echo $resume ?></p>
        </div>
        <div id="localisationCard">
          <div id="tagsCard">
            <?php foreach ($tags as $key => $tag) { ?>
              <span class="tagIndex"><?php echo $tag ?></span>
            <?php } ?>
          </div>
        </div>
      </div>
    </figcaption>
  </figure>
</form>