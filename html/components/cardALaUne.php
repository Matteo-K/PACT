<form action="detailsOffer.php" method="post" class="carteIndex">
  <input type="hidden" name="idoffre" value="<?php echo $idOffre ?>">
  <figure>
  <?php $alt = !empty($urlImg) ? "photo_principal_de_l'offre" : "Pas_de_photo_attribué_à_l'offre";?>
    <img <?php echo !empty($urlImg) ? "src='$urlImg'" : "" ?> alt=<?php echo $alt; ?>>
    <figcaption>
      <?php $titre = empty($nomOffre) ? "Offre ".$idOffre : $nomOffre ; ?>
      <h3><?php echo $nomOffre ?></h3>
      <div class="information">
        <div class="detailsCardOffer">
          <div class="noteALaUne">
            <span class="blcStarALaUne">
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
              <span><?php echo $gammeDePrix ?></span>
            <?php } ?>
          </div>
          <p><?php echo $resume ?></p>
        </div>
        <div class="localisationCard">
          <address><?php echo $ville ?>, <?php echo $codePostal ?></address>
          <h4>Catégories&nbsp;:&nbsp;</h4>
          <div class="tagsCard">
            <?php foreach ($tags as $key => $tag) { ?>
              <a href="index.php#trifiltre?search=<?php echo $tag ?>" class="tagIndex"><?php echo $tag ?></a>
            <?php } ?>
          </div>
        </div>
      </div>
    </figcaption>
  </figure>
</form>