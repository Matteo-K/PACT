<?php 
$avisTemp = [];
$avisN0;
if (count($avis) != 0) {
    $avisN0 = $avis[0];
}
foreach ($avis as $key => $av) {
    $avisTemp[$av["idc"]] = $av;
}
$avis = $avisTemp;



?>
<div id="avisPro">
    <section id="avisproS1">
        <!-- Ancien h2 -->
        <select name="TridateAvis" id="TridateAvis">
            <option value="nonLu" selected>Avis non lus</option>
            <option value="recent">Avis les plus récents</option>
            <option value="ancien">Avis les plus ancien</option>
        </select>
        <div>
            <ul id="listeAvis">   
            </ul>
        </div>
    </section>
    <section id="avisproS2">
        <details open>
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
                    Non lus
                    <input type="checkbox" name="fltAvisNonLus" id="fltAvisNonLus">
                    <label for="fltAvisNonLus">Filtrer par</label>
                </h3>
                <h3>
                    <?php echo $avisN0["avisnonlus"] ?>
                </h3>
                <h3>
                    <div class="nonRepondu"></div>
                    Non répondus
                    <input type="checkbox" name="fltAvisNonRep" id="fltAvisNonRep">
                    <label for="fltAvisNonRep">Filtrer par</label>
                </h3>
                <h3>
                    <?php echo $avisN0["avisnonrepondus"] ?>
                </h3>
                <?php                
                    $stmt = $conn->prepare("SELECT nomabonnement FROM pact._abonner WHERE idOffre = ?");
                    // Exécuter la requête en passant les paramètres
                    $stmt->execute([$idOffre]);
                    
                    $abonnement = $stmt->fetch();

                    if ($abonnement[0] == "Premium") {
                        ?>
                            <h3>
                                <div class="blacklist">
                                    <img id="blackImg" src="./img/icone/blacklist.png" alt="icone blacklistage">
                                </div>
                                Blacklister
                                <label id="PopupTicket">détails</label>
                            </h3>
                            <h3>
                                <?php
                                    $stmt = $conn->prepare("SELECT count(*) FROM pact._blacklist WHERE idOffre = ?");
                                    // Exécuter la requête en passant les paramètres
                                    $stmt->execute([$idOffre]);
                                    
                                    echo ($stmt->fetch()["count"]);  
                                ?>
                            </h3>
                        <?php
                    }
                ?>
                
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
                <img src="./img/icone/blacklist.png" alt="icone de blacklistage" class="btnBlackList blacklist">
                <img src="./img/icone/signalement.png" alt="icone de parametre" class="signalementSupp signaler signalerAvis">
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

        <div id="blocReponsePro">
            <div class="conteneurReponsePro">
                <img src="./img/icone/reponse.png" alt="icone de reponse">
                <article>
                    <img src="img/icone/bin.png" alt="icône de suppression" title="supprimer ma réponse">
                    <h3> Votre réponse à membre </h3>
                    <p> Contenu de la réponse </p>
                </article>
            </div>

            <form action="/enregAvis.php?pro" method="post">
                <h2>
                    Répondre a membre
                </h2>
                <input type="submit" class="modifierBut">
                <textarea name="reponsePro" id="reponsePro" placeholder="Entrez votre réponse à propos de cet avis"></textarea>
                <input type="hidden" name="hiddenInputIdAvis" value="">
                <input type="hidden" name="idoffre" value="<?=$idOffre?>">
                <input type="hidden" name="action" value="ecrireReponse">
            </form>
        </div>
        
    </section>
</div>




<script>

// afficheListeAvis = document.querySelectorAll("#listeAvis > li");
// afficheListeAvis.forEach(li => {
//     li.addEventListener(afficheAvisSelect())
// });

const titreOffre = document.title;

let listeAvis = <?php echo json_encode($avis) ?>;

let currentPage = 1;
let nbElement = 50;
document.addEventListener('DOMContentLoaded', function() {
    displayArrayAvis(listeAvis);
});

let avisSelect = -1;
let avisPrecedent = -1;

let conteneurAvis = document.querySelector(".conteneurAvisPro");

let photoAuteurAvis = document.querySelectorAll("#ligneTitreAvis > img")[0];
let auteurAvis = document.querySelector("#ligneTitreAvis > h2");
let etoilesAvis = document.querySelectorAll(".conteneurAvisPro > .noteEtoile > .star");
let titreAvis = document.querySelector(".conteneurAvisPro > h3");

let contenuAvis = document.getElementById("contenuAvis");
let dateAvis = document.getElementById("visiteRedaction");

//On récupère les couleurs du css pour les attribuer aux etoiles
const root = document.documentElement;
const accentColor = getComputedStyle(root).getPropertyValue('--accent').trim();
const secondaryColor = getComputedStyle(root).getPropertyValue('--secondary').trim();
const primaryColor = "rgba(28, 164, 237, 0.6)";

const aucunAvisSelect = document.getElementById("aucunAvisSelect");
const blocDetails = document.querySelector("#avisproS2 > details");
const contenuDetails = document.querySelector("#avisproS2 .contentDetails");

const blocReponseAvis = document.getElementById("blocReponsePro");

const conteneurReponseAvis = document.querySelector("#blocReponsePro .conteneurReponsePro");
const contenuReponseAvis = document.querySelector("#blocReponsePro .conteneurReponsePro p");
const titreReponseAvisEcrit =  document.querySelector("#blocReponsePro .conteneurReponsePro h3");
const bin =  document.querySelector("#blocReponsePro .conteneurReponsePro article img");

const formReponseAvis = document.querySelector("#blocReponsePro form");
const titreReponseAvis = document.querySelector("#avisproS2 form h2");
const inputIdAvis = document.querySelector('#avisproS2 form input[type="hidden"]');

const txtNbAvis = document.querySelector('#avisPro details h3:nth-child(2)');

const imgSignaleAvis =document.querySelector("#avisProS2 .signaler");

const blacklistAvis =document.querySelector("#avisProS2 .blacklist");

function updateOnglet(arrayAvis) {
    // Calcul du nombre de non lu
    const nb_nonLu = filtreNonLu([...arrayAvis]);

    if (nb_nonLu.length > 0) {
        document.title = `(${nb_nonLu.length}) ${titreOffre}`;
    } else {
        document.title = titreOffre;
    }
}

function afficheAvisSelect(idAvis) {

    //Selection de l'avis de la liste qui sera traité 
    avisSelect = document.getElementById(`avis${idAvis}`);

    //Ajout d'une classe pour le style
    avisSelect.classList.add("avisSelect");

    //Affichage des détails de l'avis à droite de l'écran
    conteneurAvis.style.display = "flex";
    aucunAvisSelect.style.display = "none";

    //On ferme l'affichage du bloc avec les nb d'avis
    if(blocDetails && blocDetails.open){
        blocDetails.open = false;
    }

    //On enlève la classe de l'ancien avis sélectionnée
    if (avisPrecedent != -1) {
        avisPrecedent.classList.remove("avisSelect");
    }

    //Modification de l'icone de signalement un avis pour qu'elle reste fonctionelle
    imgSignaleAvis.classList = `signalementSupp signaler signaler_${idAvis}`;

    blacklistAvis.classList = `btnBlackList blacklist avis_${idAvis}`;

    console.log(blacklistAvis.classList);
    //changement photo auteur
    photoAuteurAvis.src = listeAvis[idAvis]['membre_url'];
    
    //changement pseudo auteur
    auteurAvis.textContent = listeAvis[idAvis]['pseudo'];
    
    //changement couleur etoiles (on remet tout jaune puis grise certaines)
    for (i = 0; i < 5; i++) {
        etoilesAvis[i].style.backgroundColor = accentColor;
    }

    listeAvis[idAvis].lu = true;
    updateOnglet(Object.entries(listeAvis));

    if (listeAvis[idAvis]['note'] < 5) {
        for (i = 4; i >= listeAvis[idAvis]['note']; i--) {
            etoilesAvis[i].style.backgroundColor = "var(--background-avis)";
        }
    }
    
    //changement titre avis
    titreAvis.textContent = listeAvis[idAvis]['titre'];
    
    //changement texte
    contenuAvis.textContent = listeAvis[idAvis]['content'];
    
    //changement date publication et visite
    dateAvis.textContent = "Visité en " +  listeAvis[idAvis]['mois'] + " - " + listeAvis[idAvis]['annee'] + formatDateDiff(listeAvis[idAvis]['datepublie']);

    //On modifie le bloc de rédaction réponse (titre + inputs caché) 
    if(avisSelect.classList.contains("avisNonRepondu") || avisSelect.classList.contains("avisNonLu")){
        formReponseAvis.style.display = "flex";
        titreReponseAvis.textContent = "Répondre à " + listeAvis[idAvis]['pseudo'];
        inputIdAvis.value = idAvis;
        conteneurReponseAvis.style.display = "none";
    }
    //Ou la réponse à afficher si il y en à déjà une 
    else{
        conteneurReponseAvis.style.display = "flex";
        contenuReponseAvis.textContent = listeAvis[idAvis]['contenureponse'];
        titreReponseAvisEcrit.textContent = "Votre réponse à " + listeAvis[idAvis]['pseudo'];
        formReponseAvis.style.display = "none";

        bin.addEventListener("click", function() {
            if (listeAvis[idAvis] && listeAvis[idAvis]['idc_reponse']) {
                supAvis(listeAvis[idAvis]['idc_reponse'], <?=$idOffre?>, "supprimerReponse")
                    .then(response => {
                        console.log("Review deleted successfully:", response);
                        // Optionally update the UI here
                    })
                    .catch(error => {
                        console.error("Failed to delete review:", error);
                    });
            } else {
                console.error("Invalid review or response ID");
            }
        });
    }
    
    //On passe l'avis de non lu a lu (en affichage et en BDD)
    if(avisSelect.classList.contains("avisNonLu")){
        avisSelect.classList.remove("avisNonLu");
        iconeNonLu = avisSelect.querySelector(".nonLu");
        iconeNonLu.remove();

        avisSelect.classList.add("avisNonRepondu");
        let divNonRep = document.createElement("div");
        divNonRep.classList.add("nonRepondu");
        avisSelect.querySelector("div").appendChild(divNonRep);

        listeAvis[idAvis]['lu'] = true;

        fetch('lectureAvis.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 'id': idAvis })
        });
        
        txtNbAvis.textContent = parseInt(txtNbAvis.textContent) - 1;
    }

    //On référence l'avis actuel comme avis précédent pour la suite 
    avisPrecedent = document.getElementById(`avis${idAvis}`);
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

//Animation du bloc details
blocDetails.addEventListener("toggle", () => {
    if (blocDetails.open) {
        openDetails();
    } else {
        closeDetails();
    }
});

    // Fonction pour ouvrir le bloc du nb de réponses avec une animation
function openDetails() {
    let height = contenuDetails.scrollHeight;
    contenuDetails.style.maxHeight = `${height}px`; // Définit la hauteur
    contenuDetails.addEventListener("transitionend", () => {
        if (blocDetails.open) {
            contenuDetails.style.maxHeight = "none"; // Supprime maxHeight après l'animation
        }
    }, { once: true });
    conteneurAvis.style.display = "none"; // Masquer l'avis affiché
    blocReponseAvis.style.display = "none"; // Masquer la réponse / la rédaction de réponse
}

function closeDetails() {
    let height = contenuDetails.scrollHeight;
    contenuDetails.style.maxHeight = `${height}px`; // Définit temporairement la hauteur
    requestAnimationFrame(() => {
        contenuDetails.style.maxHeight = "0"; // Réduit à 0 pour l'animation
    });
    if (getComputedStyle(aucunAvisSelect).display == "none") {
        conteneurAvis.style.display = "flex"; // Réaffiche le conteneur principal si un avis est select
        blocReponseAvis.style.display = "block";
     }
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
    updateOnglet(array);

    // filtre
    if (chbxNonLu.checked) {
        array = filtreNonLu(array);
    }
    if (chbxNonRep.checked) {
        array = filtreNonRep(array);
    }

    // tri
    array = triAvis(array);

    blocListAvis.innerHTML = "";

    if (array.length != 0) {
        array.forEach(avis => {
            blocListAvis.appendChild(displayAvis(avis[1]));
        });
    } else {
        let avis = document.createElement("p");
        avis.textContent = "Aucun avis trouvé";
        bloc.appendChild(avis);
    }
}

/**
 * Sélectionne le tri des avis
 */
function triAvis(arrayAvis) {
  
    if (selectTri.value === 'nonLu') {
        return triNonLu(arrayAvis);
    } else if (selectTri.value === 'recent') {
        return triDateRecent(arrayAvis);
    } else if (selectTri.value === 'ancien') {
        return triDateAncien(arrayAvis);
    }
    return arrayAvis;
}

/**
 * Tri la liste des avis du plus récent au plus ancien
 */
function triNonLu(arrayAvis) {
    return arrayAvis.sort((avis1, avis2) => {
        const avis1NonLu = !avis1.lu
        const avis2NonLu = !avis2.lu
    
        if (avis1NonLu && !avis2NonLu) {
            return -1;
        } else if (!avis1NonLu && avis2NonLu) {
            return 1;
        }
        return 0;
    });
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
    // chbxNonLu.style.color = accentColor;
    return arrayAvis.filter(avis => {
        return !avis[1].lu;
    });
}

/**
 * Retourne une liste avec seulement lesavis non répondu
 */
function filtreNonRep(arrayAvis) {
    // chbxNonRep.style.color = accentColor;
    return arrayAvis.filter(avis => {
        return avis[1].idc_reponse == null;
    });
}

/**
 * Affichage d'un avis
 */
function displayAvis(avis) {
    let li = document.createElement("li");
    li.id = "avis" + avis.idc;
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

    const blocListAvis = document.getElementById("listeAvis");

    if (!avis.lu && avis.idc_reponse == null) {
        li.classList.add("avisNonLu");
        let divNonLu = document.createElement("div");
        divNonLu.classList.add("nonLu");
        blocTitre.appendChild(divNonLu);
    }
    else if (avis.idc_reponse == null) {
        li.classList.add("avisNonRepondu");
        let divNonRep = document.createElement("div");
        divNonRep.classList.add("nonRepondu");
        blocTitre.appendChild(divNonRep);
    }

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
