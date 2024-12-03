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

                        <div>

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
                            </div>

                            <p>
                                <?php echo $av['pseudo'] . " - " . $av['titre'] ?>
                            </p>

                        </div>

                        <p>
                            <?php echo $av['content'] ?>
                        </p>

                    </li>
                <?php
                }
                ?>
                
            </ul>
        </div>


    </section>


    <section id="avisproS2">

        <details>

            <summary>
                <span class="custom-marker">▶</span>
                <h2>
                    Nombre d'avis
                </h2>
            </summary>

            <div class="contentDetails">
            
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

        </details> 

        <p id="aucunAvisSelect"> Cliquez sur un avis de la liste pour l'afficher ici. </p>

        <div class="conteneurAvisPro">
            
            <div id="ligneTitreAvis">
                <h2>
                    Auteur
                </h2>
                <img src="./img/icone/trois-points.png" alt="icone de parametre">
            </div>
            
            <div class="noteEtoile">

                <?php
                    for ($i = 0; $i < $av['note']; $i++) {
                        echo "<div class='star'></div>";
                    }
                ?>

            </div>

            <h3>
                Titre
            </h3>

            <p id="contenuAvis">
                Texte de l'avis
            </p>

            <p id="visiteRedaction"> 
                Visité en .... le ../.. - rédigé le ../.. 
            </p>
            
        </div>

    </section>


   
</div>


<script>

let listeAvis = <?php echo json_encode($avis) ?>;
let affichage = document.getElementById("detailAvisPro");

function afficheAvisSelect(numAvis) {
        
    affichage.textContent = listeAvis[numAvis]["content"];
}


document.querySelectorAll("#avisproS2 > details").forEach(details => {
  const content = details.querySelector(".contentDetails");

  // Fonction pour ouvrir avec une animation
  function openDetails() {
    const height = content.scrollHeight; // Calcule la hauteur totale
    content.style.maxHeight = `${height}px`; // Définit la hauteur pour l'animation
    content.addEventListener("transitionend", () => {
      if (details.open) {
        content.style.maxHeight = "none"; // Supprime maxHeight après l'animation
      }
    }, { once: true });
  }

  // Fonction pour fermer avec une animation
  function closeDetails() {
    const height = content.scrollHeight; // Hauteur actuelle
    content.style.maxHeight = `${height}px`; // Définit temporairement la hauteur actuelle
    requestAnimationFrame(() => { // Assure une relecture du style
      content.style.maxHeight = "0"; // Puis réduit à 0 pour l'animation
    });
  }

  // Gérer les événements d'ouverture et de fermeture
  details.addEventListener("toggle", () => {
    if (details.open) {
      openDetails();
    } else {
      closeDetails();
    }
  });
});
</script>
