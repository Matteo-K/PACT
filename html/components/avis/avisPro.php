<div id="avisPro">


    <section id="avisproS1">

        <h2 class="triangle">
        
            Avis les plus récents
        </h2>


        <div>
            <ul id="listeAvis">
                
                <?php
                foreach ($avis as $numAv => $av) {
                    ?> 
                    <li onclick="afficheAvisSelect(<?php echo $numAv ?>)">

                        <div class="noteEtoile">
                            <?php
                            for ($i = 0; $i < $av['note']; $i++) {
                                echo "<div class='star'></div>";
                            }
                            if (5 - $av['note'] != 0) {
                                for ($i = 0; $i < 5 - $av['note']; $i++) {
                                    echo "<div class='star starAvisIncolore'></div>";
                                }
                            }
                            ?>
                            <p><?= $av['note'] ?> / 5</p>
                        </div>

                        <p>
                            <?php echo $av['pseudo'] . " - " . $av['titre'] ?>
                        </p>
                    </li>;
                <?php
                }
                ?>
                
            </ul>
        </div>


    </section>


    <section id="avisproS2">

        <div>

            <h2 class="triangle">
                Nombre d'avis
            </h2>

            <h3>
                <div class="nonLu"></div>
                Non lus
            </h3>

            <h3>
                <div class="nonRepondu"></div>
                Non répondus
            </h3>


            <?php

            if (!$avis) {
                echo '<p>Pas de note pour le moment</p>';
            } else {
                $etoilesPleines = floor($avis[0]['moynote']); // Nombre entier d'étoiles pleines
                $reste = $avis[0]['moynote'] - $etoilesPleines; // Reste pour l'étoile partielle
            ?>
                <div class="notation">
                    <div>
                        <?php
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
                        <p><?php echo number_format($avis[0]['moynote'], 1); ?> / 5 (<?php echo $avis[0]['nbnote']; ?> avis)</p>
                    </div>
                    <div class="notedetaille">
                        <?php
                        // Adjectifs pour les notes
                        $listNoteAdjectif = ["Horrible", "Médiocre", "Moyen", "Très bon", "Excellent"];
                        for ($i = 5; $i >= 1; $i--) {
                            // Largeur simulée pour chaque barre en fonction de vos données
                            $pourcentageParNote = isset($avis[0]["note_$i"]) ? ($avis[0]["note_$i"] / $avis[0]['nbnote']) * 100 : 0;
                        ?>
                            <div class="ligneNotation">
                                <span><?= $listNoteAdjectif[$i-1]; ?></span>
                                <div class="barreDeNotationBlanche">
                                    <div class="barreDeNotationJaune" style="width: <?= $pourcentageParNote; ?>%;"></div>
                                </div>
                                <span>(<?= isset($avis[0]["note_$i"]) ? $avis[0]["note_$i"] : 0; ?> avis)</span>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            <?php
            }
            ?>

        </div> 

        <div class="conteneurAvisPro">
            <div id="detailAvisPro">Cliquez sur un avis pour l'afficher ici.</div>
        </div>

    </section>


   
</div>


<script>

let listeAvis = <?php echo json_encode($avis) ?>;
let affichage = document.getElementById("detailAvisPro");

function afficheAvisSelect(numAvis) {
        
    affichage.textContent = listeAvis[numAvis]["content"];
}
</script>
