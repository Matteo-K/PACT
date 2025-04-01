<form action="/detailsOffer.php" method="post" class="searchA">
  <input type="hidden" name="idoffre" value="<?php echo $idOffre ?>">
  <section class="carteOffre flip-card <?= in_array("EnRelief", $options) ? "optionEnRelief" : "" ?>">
    <div class="flip-card-inner">
      <article class="flip-card-front">
        <figure>
          <?php
            if (in_array("EnRelief", $options)) {
              ?>
                <img loading="lazy" class="premiumImg" src="../img/icone/service-premium.png" alt="icone premium">
              <?php
            }
          ?>
          <img loading="lazy" src="<?= $urlImg ?>" alt="<?= $nomOffre ?>" title="<?= $nomOffre ?>">
          <figcaption>
            <h4 class="title"><?= $nomOffre ?></h4>
            <div>
              <p class="ville">
                <?= $ville . ", ". $codePostal?>
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
                    <div class="star partielle" style="--pourcentage: <?= $pourcentageRempli ?>%;"></div>
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
      </article>
      <article class="flip-card-back">
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
              $imageCategorie = "visit.png";
              break;

            default:
              $imageCategorie = "interrogation.png";
              break;
          }
        ?>
        <figure>
          <img loading="lazy" src="<?= $chemin . $imageCategorie ?>" alt="<?= $categorie ?>" title="<?= $categorie ?>">
          <?php if ($categorie == "Restaurant") { ?>
            <figcaption><?= $gammeDePrix ?></figcaption>
          <?php } ?>
        </figure>
        <div class="content">
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
                <div class="star partielle" style="--pourcentage: <?= $pourcentageRempli ?>%;"></div>
              <?php }
              // Étoiles vides
              for ($i = $etoilesPleines + ($reste > 0 ? 1 : 0); $i < 5; $i++) { ?>
                <div class="star vide"></div>
            <?php } ?>
            <span>
              <?= $noteAvg."/5" ?>
            </span>
            <span>
              (<?= empty($nbNote) ? 0 : $nbNote ?> note<?= $nbNote > 1 ? "s" : "" ?>)
            </span>
          </div>
          <div class="nomPro">
            Proposé par <?= $nomUser ?>
          </div>
          <div class="information">
            <div class="resume">
              <?= $resume ?>
            </div>
            <address>
              <div><?= $ville . ", " . $codePostal ?></div>
              <div id="numRue"><?= $numerorue . " " . $rue ?></div>
            </address>
          </div>
          <div class="tagsCard">
            <?php
              $nbTagMax = 2;
              $plusTag = 0;

              if (count($tags) > 0) {
                if (count($tags) > $nbTagMax) {
                  $plusTag = count($tags) - $nbTagMax;
                  $tagsToShow = array_slice($tags, 0, $nbTagMax);
                } else {
                  $tagsToShow = $tags;
                }

                foreach ($tagsToShow as $tag) {
                  if (!empty($tag)) { ?>
                    <a href="index.php?search=<?php echo $tag ?>#searchIndex" class="tagIndex"><?php echo $tag ?></a>
                  <?php }
                }

                if ($plusTag > 0) { ?>
                  <a class="tagIndex">+ <?= $plusTag ?> autre<?= $plusTag > 1 ? "s" : "" ?></a>
                <?php }
              }
            ?> 
          </div>
        </div>
      </article>
    </div>
  </section>
</form>
