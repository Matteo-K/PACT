<form action="/detailsOffer.php" method="post" class="searchA">
  <input type="hidden" name="idoffre" value="<?php echo $idOffre ?>">
  <div class="carteOffre flip-card <?= in_array("EnRelief", $options) ? "optionEnRelief" : "" ?>">
    <div class="flip-card-inner">
      <figure class="flip-card-front">
        <img src="<?= $urlImg ?>" alt="<?= $nomOffre ?>" title="<?= $nomOffre ?>">
        <figcaption>
          <h4 class="title"><?= $nomOffre ?></h4>
          <div>
            <p class="ville">
              <?= $ville ?>
              <span class="cardCodePostal">
                <?= ", ".$codePostal ?>
              </span>
            </p>
            <div class="blocStar">
              <?php  
                $etoilesPleines = floor($noteAvg); // Nombre entier d'étoiles pleines
                $reste = $noteAvg - $etoilesPleines;
                // Étoiles pleines
                for ($i = 1; $i <= $etoilesPleines; $i++) { ?>
                  <div class="star pleine"></div>
                <?php }
                // Étoile partielle
                if ($reste > 0) {
                  $pourcentageRempli = $reste * 100; // Pourcentage rempli ?>
                  <div class="star partielle" style="--pourcentage: ' . $pourcentageRempli . '%;"></div>
                <?php }
                // Étoiles vides
                for ($i = $etoilesPleines + ($reste > 0 ? 1 : 0); $i < 5; $i++) { ?>
                  <div class="star vide"></div>
              <?php } ?>
              <span>
                <?= $noteAvg."/5" ?>
              </span>
            </div>
          </div>
        </figcaption>
      </figure>
      <div class="flip-card-back">
        <?php
        $imageCategorie;
        $chemin = "../img/icone/offerCategory/";
        switch ($categorie) {
          case 'Activité':
            $imageCategorie = "activity.png";
            break;
            
          case 'Parc Attraction':
            $imageCategorie = "park.png";
            break;

          case 'Restaurant':
            $imageCategorie = "restaurant.png";
            break;

          case 'Spectacle':
            $imageCategorie = "show2.png";
            break;

          case 'Visite':
            $imageCategorie = "Visit.png";
            break;

          default:
            $imageCategorie = "interrogation.png";
            break;
        }
        ?>
        <figure>
          <img src="<?= $chemin . $imageCategorie ?>" alt="<?= $categorie ?>">
          <?php if ($categorie == "Restaurant") { ?>
            <figcaption><?= $gammeDePrix ?></figcaption>
          <?php } ?>
        </figure>
        <div>
          <h4 class="title"><?= $nomOffre ?></h4>
          <div class="blocStar">
              <?php  
                $etoilesPleines = floor($noteAvg); // Nombre entier d'étoiles pleines
                $reste = $noteAvg - $etoilesPleines;
                // Étoiles pleines
                for ($i = 1; $i <= $etoilesPleines; $i++) { ?>
                  <div class="star pleine"></div>
                <?php }
                // Étoile partielle
                if ($reste > 0) {
                  $pourcentageRempli = $reste * 100; // Pourcentage rempli ?>
                  <div class="star partielle" style="--pourcentage: ' . $pourcentageRempli . '%;"></div>
                <?php }
                // Étoiles vides
                for ($i = $etoilesPleines + ($reste > 0 ? 1 : 0); $i < 5; $i++) { ?>
                  <div class="star vide"></div>
              <?php } ?>
              <span>
                <?= $noteAvg."/5" ?>
              </span>
              <span>
                (<?= $nbNote ?> note<?= $nbNote > 1 ? "s" : "" ?>)
              </span>
            </div>
          </div>
          <div class="nomPro">
            Proposé par <?= $nomUser ?>
          </div>
          <div class="content">
            <div class="resume">
              <?= $resume ?>
            </div>
            <address>
              <div><?= $ville . " " . $codePostal ?></div>
              <div><?= $numerorue . " " . $rue ?></div>
            </address>
          </div>
          <div class="tagsCard">
            <?php
              if (count($tags) > 0) {
                foreach ($tags as $key => $tag) { ?>
                  <a href="index.php?search=<?php echo $tag ?>#searchIndex" class="tagIndex"><?php echo $tag ?></a>
                <?php 
                } 
              }
            ?> 
          </div>
        </div>
      </div>
    </div>
  </div>
</form>