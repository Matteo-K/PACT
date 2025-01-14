<div class="swiper-slide">
    <div class="searchA" data-idoffre="<?php echo $idOffre; ?>" data-ouvert="<?php echo $restaurantOuvert; ?>">
        <div class="carteOffre">
            <?php $alt = !empty($urlImg) ? "photo_principal_de_l'offre" : "Pas_de_photo_attribué_à_l'offre"; ?>
            <img class="searchImage" <?php echo !empty($urlImg) ? "src='$urlImg'" : "" ?> alt=<?php echo $alt; ?>>
            <div class="infoOffre">
                <p class="searchTitre"><?php echo $nomOffre != NULL ? $nomOffre : "Pas de nom d'offre"; ?></p>
                <strong><p class="villesearch"><?php echo $ville . $gammeText . " ⋅ " . $nomTag; ?></p></strong>

                <div class="searchCategorie">
                    <?php
                    if ($tag != "") {
                        foreach ($tag as $value) {
                            $value = str_replace('_', ' ', $value);
                            ?><span class="searchTag"><?php echo $value . " "; ?></span><?php
                        }
                    }
                    ?>
                </div>
                <p class="searchResume"><?php echo ($resume) ? $resume : "Pas de résumé saisi"; ?></p>
                <section class="searchNote">
                    <p><?php echo $noteAvg; ?></p>
                    <p id="couleur-<?php echo $idOffre; ?>" class="<?php echo $restaurantOuvert == "EstOuvert" ? "searchStatutO" : "searchStatutF"; ?>">
                        <?php echo ($restaurantOuvert == "EstOuvert") ? "Ouvert" : "Fermé"; ?>
                    </p>
                </section>
            </div>
            <div class="searchAvis">
                <p class="avisSearch">Les avis les plus récents :</p>
                <p>Pas d'avis</p>
            </div>
        </div>
    </div>
</div>
