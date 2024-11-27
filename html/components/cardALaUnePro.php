<form action="detailsOffer.php" method="post">
  <input type="hidden" name="" value="">
  <figure>
  <?php $alt = isset($urlImg['url']) && $urlImg ? "photo_principal_de_l'offre" : "Pas_de_photo_attribué_à_l'offre";?>
    <img src="<?php echo $urlImg; ?>" alt=<?php echo $alt; ?>>
    <figcaption>
      <h3><?php echo $nomOffre ?></h3>
      <div>
        <div id="detailsCardOffer">
          <div id="noteALaUne">
            <span id="blcStarALaUne">
              <?php  
              $etoilesPleines = floor($noteAvg); // Nombre entier d'étoiles pleines
              $reste = $noteAvg - $etoilesPleines;
              // Étoiles pleines
              for ($i = 1; $i <= $etoilesPleines; $i++) {
                echo '<div class="star pleine"></div>';
              }
              // Étoile partielle
              if ($reste > 0) {
                $pourcentageRempli = $reste * 100; // Pourcentage rempli
                echo '<div class="star partielle" style="--pourcentage: ' . $pourcentageRempli . '%;"></div>';
              }
              // Étoiles vides
              for ($i = $etoilesPleines + ($reste > 0 ? 1 : 0); $i < 5; $i++) {
                echo '<div class="star vide"></div>';
              }
              ?>
            </span>
            <span><?php echo $noteAvg ?>/5</span>
            <span>(<?php echo $nbNote ?> avis)</span>
            <?php if ($categorie == "Restaurant") { ?>
              <span> ⋅ <?php echo $gammeDePrix ?></span>
            <?php } ?>
          </div>
          <p><?php echo $resume ?></p>
        </div>
        <div id="localisationCard">
          <adresse><?php echo $ville ?>, <?php echo $codePostal ?></adresse>
          <h4>Catégories&nbsp;:&nbsp;</h4>
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