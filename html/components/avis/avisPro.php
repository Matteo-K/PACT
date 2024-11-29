<div id="avisPro">


    <section id="avisproS1">

        <h2 class="triangle">
        
            Avis les plus récents
        </h2>


        <div>
            <ul id="listeAvis">
            <li data-content="Voici le contenu complet du message 1" onclick="showPreview(this)">Message 1</li>
            <li data-content="Voici le contenu complet du message 2" onclick="showPreview(this)">Message 2</li>
            <li data-content="Voici le contenu complet du message 3" onclick="showPreview(this)">Message 3</li>
            <li data-content="Voici le contenu complet du message 4" onclick="showPreview(this)">Message 4</li>
            <li data-content="Voici le contenu complet du message 5" onclick="showPreview(this)">Message 5</li>
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

            <div class="conteneurAvisPro">
                <div id="detailAvisPro">Cliquez sur un avis pour l'afficher ici.</div>
            </div>
            </div>

        </div>

        <script>
            function showPreview(element) {
                // Récupère le contenu depuis l'attribut data-content de l'élément cliqué
                const content = element.getAttribute('data-content');
                
                // Met à jour la zone de prévisualisation
                const preview = document.getElementById("detailsAvisPro");
                preview.textContent = content;
            }
        </script>

        


    </section>

</div>