<?php 
$avisTemp = [];
$avisN0 = $avis[0];
foreach ($avis as $key => $av) {
    $avisTemp[$av["idc"]] = $av;
}
$avis = $avisTemp;
?>
<div id="avisPro">
    <section id="avisproS1">
        <!-- Ancien h2 -->
        <select name="TridateAvis" id="TridateAvis">
            <option value="recent" selected>Avis les plus récents</option>
            <option value="ancien">Avis les plus ancien</option>
        </select>
        <div>
            <ul id="listeAvis">   
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
                <h2>
                    <?php
                    if($avis) {
                         echo count($avis);
                    }else {
                        echo 0;
                    } ?>
                </h2>
            </summary>
            <div class="contentDetails">
            <?php 
                if($avis) {
            ?>
            <h3>
                <div class="nonLu"></div>
                <input type="checkbox" name="fltAvisNonLus" id="fltAvisNonLus">
                <label for="fltAvisNonLus">Non lus</label>
            </h3>
            <h3>
                <?php echo $avisN0["avisnonlus"] ?>
            </h3>
            <h3>
                <div class="nonRepondu"></div>
                <input type="checkbox" name="fltAvisNonRep" id="fltAvisNonRep">
                <label for="fltAvisNonRep">Non répondus</label>
            </h3>
            <h3>
                <?php echo $avisN0["avisnonrepondus"] ?>
            </h3>
                <?php
                    $etoilesPleines = floor($avisN0['moynote']); // Nombre entier d'étoiles pleines
                    $reste = $avisN0['moynote'] - $etoilesPleines; // Reste pour l'étoile partielle
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
                            <p><?php echo number_format($avisN0['moynote'], 1); ?> / 5 (<?php echo $avisN0['nbnote']; ?> avis)</p>
                        </div>
                        <div class="notedetaille">
                            <?php
                            // Adjectifs pour les notes
                            $listNoteAdjectif = ["Horrible", "Médiocre", "Moyen", "Très bon", "Excellent"];
                            for ($i = 5; $i >= 1; $i--) {
                                // Largeur simulée pour chaque barre en fonction de vos données
                                $pourcentageParNote = isset($avisN0["note_$i"]) ? ($avisN0["note_$i"] / $avisN0['nbnote']) * 100 : 0;
                            ?>
                                <div class="ligneNotation">
                                    <span><?= $listNoteAdjectif[$i-1]; ?></span>
                                    <div class="barreDeNotationBlanche">
                                        <div class="barreDeNotationJaune" style="width: <?= $pourcentageParNote; ?>%;"></div>
                                    </div>
                                    <span>(<?= isset($avisN0["note_$i"]) ? $avisN0["note_$i"] : 0; ?> avis)</span>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
            <?php
                }
                else {
                    echo "<p> Aucune donnée a afficher : vous n'avez pas encore d'avis </p>";
                }
            ?>
            </div>
        </details> 
        <p id="aucunAvisSelect"> Cliquez sur un avis de la liste pour l'afficher ici. </p>
        <div class="conteneurAvisPro">
            <div id="ligneTitreAvis">
                <img src="./img/profile_picture/default.svg" alt="Photo du membre">
                <h2>
                    Auteur
                </h2>
                <img src="./img/icone/trois-points.png" alt="icone de parametre">
            </div>
            <div class="noteEtoile">
                <?php
                    for ($i = 0; $i < 5; $i++) {
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
            <div id="imagesAvisPro">
            </div>
            <p id="visiteRedaction"> 
                Visité en .... le ../.. - rédigé le ../.. 
            </p>
        </div>
    </section>
</div>


<script>

// afficheListeAvis = document.querySelectorAll("#listeAvis > li");
// afficheListeAvis.forEach(li => {
//     li.addEventListener(afficheAvisSelect())
// });


let listeAvis = <?php echo json_encode($avis) ?>;
document.addEventListener('DOMContentLoaded', function() {
    displayArrayAvis(listeAvis);
});

let conteneurAvis = document.querySelector(".conteneurAvisPro");

let photoAuteurAvis = document.querySelector("#ligneTitreAvis > h2");
let auteurAvis = document.querySelector("#ligneTitreAvis > h2");
let etoilesAvis = document.querySelectorAll(".conteneurAvisPro > .noteEtoile > .star");
let titreAvis = document.querySelector(".conteneurAvisPro > h3");

let contenuAvis = document.getElementById("contenuAvis");
let dateAvis = document.getElementById("visiteRedaction");

//On récupère les couleurs du css pour les attribuer aux etoiles
const root = document.documentElement;
const accentColor = getComputedStyle(root).getPropertyValue('--accent').trim();
const secondaryColor = getComputedStyle(root).getPropertyValue('--secondary').trim();
const primaryColor = getComputedStyle(root).getPropertyValue('--primary').trim();


function afficheAvisSelect(numAvis) {

    conteneurAvis.style.display = "flex";
    document.getElementById("aucunAvisSelect").style.display = "none";

    closeDetails();

    //Changement de couleur du li sélectionné et on remet les autres en gris
    document.querySelectorAll("#listeAvis > li").forEach((li, numLi) => {
        li.style.background = `linear-gradient(90deg, ${accentColor} 0%, ${accentColor} 80%, transparent 100%)`;
        if (numAvis == numLi) {
            li.style.background = `linear-gradient(90deg, ${primaryColor} 0%, ${primaryColor} 90%, transparent 100%)`;
        }
    });
    
    //changement photo auteur
    photoAuteurAvis.src = listeAvis[numAvis]['membre_url'];
    
    //changement pseudo auteur
    auteurAvis.textContent = listeAvis[numAvis]['pseudo'];
    
    //changement couleur etoiles (on remet tout jaune puis grise certaines)
    for (i = 0; i < 5; i++) {
        etoilesAvis[i].style.backgroundColor = accentColor;
    }
    
    if (listeAvis[numAvis]['note'] < 5) {
        for (i = 4; i >= listeAvis[numAvis]['note']; i--) {
            etoilesAvis[i].style.backgroundColor = secondaryColor;
        }
    }
    
    //changement titre avis
    titreAvis.textContent = listeAvis[numAvis]['titre'];
    
    //changement texte
    contenuAvis.textContent = listeAvis[numAvis]['content'];
    
    //changement date publication et visite
    dateAvis.textContent = "Visité en" +  listeAvis[numAvis]['mois'] + " " + listeAvis[numAvis]['annee'] + formatDateDiff(listeAvis[numAvis]['datepublie']);
}


function formatDateDiff(dateString) {
  // Créer des objets Date à partir de la date donnée et de la date actuelle
  const dateDB = new Date(dateString);
  const dateNow = new Date();
  
  // Fixer les objets Date à minuit pour calculer les jours
  const dateDBMidnight = new Date(dateDB);
  dateDBMidnight.setHours(0, 0, 0, 0);
  
  const dateNowMidnight = new Date(dateNow);
  dateNowMidnight.setHours(0, 0, 0, 0);
  
  // Calculer la différence en jours (à partir de minuit)
  const diffInDays = Math.floor((dateNowMidnight - dateDBMidnight) / (1000 * 60 * 60 * 24));
  
  // Calculer la différence en heures et minutes
  const diffInMilliseconds = dateNow - dateDB;
  const diffInHours = Math.floor(diffInMilliseconds / (1000 * 60 * 60));
  const diffInMinutes = Math.floor((diffInMilliseconds % (1000 * 60 * 60)) / (1000 * 60));

  // Déterminer le message à afficher
  if (diffInDays === 0) {
      if (diffInMinutes === 0) {
      return " Rédigé à l'instant";
    } else if (diffInHours > 1) {
      return ` Rédigé il y a ${diffInHours - 1} heure${diffInHours > 2 ? 's' : ''}`;
    } else {
      return ` Rédigé il y a ${diffInMinutes} minute${diffInMinutes > 1 ? 's' : ''}`;
    }
  } else if (diffInDays === 1) {
      // La date est hier
    return " Rédigé hier";
} else if (diffInDays > 1 && diffInDays <= 7) {
    // La date est dans les 7 derniers jours
    return ` Rédigé il y a ${diffInDays} jour${diffInDays > 1 ? 's' : ''}`;
} else {
    // La date est plus ancienne que 7 jours ou dans le futur
    return ` Rédigé le ${dateDB.toLocaleDateString("fr-FR")} à ${dateDB.toLocaleTimeString("fr-FR", {
      hour: "2-digit",
      minute: "2-digit",
      })}`;
    }
}

const blocDetails = document.querySelector("#avisproS2 > details")
const contenuDetails = document.querySelector("#avisPro2 > details > .contentdetails");

//Animation du bloc details
blocDetails.addEventListener("toggle", () => {
    if (blocDetails.open) {
        openDetails();
    } else {
        closeDetails();
    }
});

    // Fonction pour ouvrir avec une animation
function openDetails() {
    const height = contenuDetails.scrollHeight; // Calcule la hauteur totale
    contenuDetails.style.maxHeight = `${height}px`; // Définit la hauteur pour l'animation
    contenuDetails.addEventListener("transitionend", () => {
        if (blocDetails.open) {
            contenuDetails.style.maxHeight = "none"; // Supprime maxHeight après l'animation
    }
}, { once: true });
    conteneurAvis.style.display = "none";
}

// Fonction pour fermer avec une animation
function closeDetails() {
    const height = contenuDetails.scrollHeight; // Hauteur actuelle
    contenuDetails.style.maxHeight = `${height}px`; // Définit temporairement la hauteur actuelle
    requestAnimationFrame(() => { // Assure une relecture du style
        contenuDetails.style.maxHeight = "0"; // Puis réduit à 0 pour l'animation
    });
    conteneurAvis.style.display = "flex";
}


/* ### Gestion des tri et filtre des avis ### */

const selectTri = document.getElementById("TridateAvis");
const chbxNonLu = document.getElementById("fltAvisNonLus");
const chbxNonRep = document.getElementById("fltAvisNonRep");

selectTri.addEventListener('change', () => displayArrayAvis(listeAvis));
chbxNonLu.addEventListener('change', () => displayArrayAvis(listeAvis));
chbxNonRep.addEventListener('change', () => displayArrayAvis(listeAvis));

function displayArrayAvis(arrayAvis) {
    const blocListAvis = document.getElementById("listeAvis");
    
    let array = Object.entries(arrayAvis);

    // filtre
    array = filtreNonLu(array);
    array = filtreNonRep(array);

    // tri
    array = triAvis(array);

    blocListAvis.innerHTML = "";

    array.forEach(avis => {
        blocListAvis.appendChild(displayAvis(avis[1]));
    });
}

/**
 * Sélectionne le tri des avis
 */
function triAvis(arrayAvis) {
  
  if (selectTri.value === 'recent') {
    return triDateRecent(arrayAvis);
  } else if (selectTri.value === 'ancien') {
    return triDateAncien(arrayAvis);
  }
  return arrayAvis;
}

/**
 * Tri la liste des avis du plus récent au plus ancien
 */
function triDateRecent(arrayAvis) {
    return arrayAvis.sort((avis1, avis2) => {
        const date1 = new Date(avis1[1].datepublie);
        const date2 = new Date(avis2[1].datepublie);

        return date2.getTime() - date1.getTime()
    });
}

/**
 * Tri la liste des avis du plus ancien au plus récent
 */
function triDateAncien(arrayAvis) {
    return arrayAvis.sort((avis1, avis2) => {
        const date1 = new Date(avis1[1].datepublie);
        const date2 = new Date(avis2[1].datepublie);

        return date1.getTime() - date2.getTime()
    });
}

/**
 * Retourne une liste avec seulement lesavis non lus
 */
function filtreNonLu(arrayAvis) {
    if (chbxNonLu.checked) {
        return arrayAvis.filter(avis => {
            return avis[1].lu == false;
        });
    }
    return arrayAvis;
}

/**
 * Retourne une liste avec seulement lesavis non répondu
 */
function filtreNonRep(arrayAvis) {
    if (chbxNonRep.checked) {
        return arrayAvis.filter(avis => {
            return avis[1].idc_reponse == null;
        });
    }
    return arrayAvis;
}

/**
 * Affichage d'un avis
 */
function displayAvis(avis) {
    let li = document.createElement("li");
    li.setAttribute("onclick","afficheAvisSelect("+ avis.idc +")");

    let blocTitre = document.createElement("div");
    let titre = document.createElement("p");
    titre.textContent = avis.pseudo + " - " + avis.titre;

    blocTitre.appendChild(displayStar(avis.note));
    blocTitre.appendChild(titre);

    let content = document.createElement("p");
    content.textContent = avis.content;
    
    li.appendChild(blocTitre);
    li.appendChild(content);

    return li;
}

/**
 * interprète le nombre d'étoile colorié affiché
 * @param {Integer} note nombre d'étoile colorié correpondant à la note de l'avis
 * @returns bloc div qui contient les étoiles
 */
function displayStar(note) {
  let container = document.createElement("div");
  container.classList.add("noteEtoile");

  const etoilesPleines = Math.floor(note);
  const reste = note - etoilesPleines;

  for (let i = 1; i <= etoilesPleines; i++) {
    let star = document.createElement('div');
    star.classList.add('star', 'pleine');
    container.appendChild(star);
  }
  
  if (reste > 0) {
    let pourcentageRempli = reste * 100;
    let starPartielle = document.createElement('div');
    starPartielle.classList.add('star', 'partielle');
    starPartielle.style.setProperty('--pourcentage', `${pourcentageRempli}%`);
    container.appendChild(starPartielle);
  }
  
  let totalEtoiles = 5;
  let etoilesRestantes = totalEtoiles - etoilesPleines - (reste > 0 ? 1 : 0);
  
  for (let i = 0; i < etoilesRestantes; i++) {
    let star = document.createElement('div');
    star.classList.add('star', 'vide');
    container.appendChild(star);
  }
  return container;
}
</script>
