<a class="searchA" href="/detailsOffer.php?idoffre=<?php echo $idOffre; ?>&ouvert=<?php echo $restaurantOuvert; ?>">
    <div class="carteOffre">
            <?php 
            $alt = !empty($urlImg) ? "photo_principal_de_l'offre" : "Pas_de_photo_attribué_à_l'offre";
            ?>
            <img class="searchImage" loading="lazy" <?php echo !empty($urlImg) ? "src='$urlImg'" : "" ?> alt=<?php echo $alt; ?>>
        <div class="infoOffre">
            <div class="ProStatut">
                <p class="searchTitre"><?php echo $nomOffre!=NULL?$nomOffre :"Pas de nom d'offre"; ?></p>
                <span class="StatutAffiche <?php echo $statut=='actif'?"":"horslgnOffre";?>">
                    <?php echo $statut=='actif'?"En-Ligne":"Hors-Ligne";?>
                </span>
            </div>

            <strong><p class="villesearch"><?php echo $ville . $gammeText . " ⋅ " . $nomTag; ?></p></strong>

            <div class="searchCategorie">
                <?php
                if ($tag!="") {
                    foreach ($tag as $value) {
                        $value=str_replace('_',' ',$value);
                        ?><span class="searchTag"><?php echo $value." " ?></span><?php
                    }
                }
                ?>
            </div>

            <p class="searchResume"><?php echo ($resume)?$resume:"Pas de resume saisie";?></p>

            <section class="searchNote">
                <p><?php echo $noteAvg; ?></p>
            
                <p id="couleur-<?php echo $idOffre; ?>" class="searchStatutO">
                    <?php echo ($restaurantOuvert == "EstOuvert") ? "Ouvert" : "Fermé"; ?>
                </p>
            </section>


            <script>
                let st_<?php echo $idOffre; ?> = document.getElementById("couleur-<?php echo $idOffre; ?>");
                if ("<?php echo $restaurantOuvert; ?>" === "EstOuvert") {
                    st_<?php echo $idOffre; ?>.classList.add("searchStatutO");
                } else {
                    st_<?php echo $idOffre; ?>.classList.add("searchStatutF");
                }
            </script>
        </div>
        <div class="searchAvis">
            <p class="avisSearch">Les avis les plus récent :</p>
            <p>Pas d'avis</p>
        </div>
    </div>
</a>