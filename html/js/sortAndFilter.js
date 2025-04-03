/**
 * @file
 * @author Mattéo Kervadec & Antoine Guillerm
 * Ensemble des fonctions et évènements 
 * afin de trier et filtrer les offres de la page de recherche
 */

import { geocode } from "./geocode.js";

let currentPage = 1;
let nbElement = 12;

let arrayOffer, page;
const userDataElement = document.getElementById('user-data');
const userType = userDataElement.getAttribute('data-user');
document.addEventListener('DOMContentLoaded', function() {

  const offersDataElement = document.getElementById('offers-data');
  
  const offersData = offersDataElement.getAttribute('data-offers');
  // console.log(offersData); // Débugger

  try {
    arrayOffer = JSON.parse(offersData);
    arrayOffer = Object.values(arrayOffer);
  } catch (error) {
    console.error("Erreur de parsing JSON :", error);
  }

  const params = new URLSearchParams(window.location.search);

  if (params.has('page') && params.get('page').trim() !== '') {
    currentPage = params.get('page');
  }

  goToPage(currentPage);

  const forms = document.querySelectorAll(".search form");
  forms.forEach(form => {
    form.addEventListener("click", (event) => {
      if (event.target.tagName.toLowerCase() === "a") {
        return;
      }
      event.preventDefault();
      form.submit();
    });
  });

});

/// Inputs de recherche ///
const searchInput = document.querySelector("#formHeader input");

/// Inputs Tris ///
const radBtnEnAvant = document.querySelector("#miseEnAvant");

// note
const radBtnNoteCroissant = document.querySelector("#noteCroissant");
const radBtnNoteDecroissant = document.querySelector("#noteDecroissant");

// prix
const radBtnprixCroissant = document.querySelector("#prixCroissant");
const radBtnPrixDecroissant = document.querySelector("#prixDecroissant");

// date
const radBtnAvisCroissant = document.querySelector("#avisCroissant");
const radBtnAvisDecroissant = document.querySelector("#avisDecroissant");

// date
const radBtnDateCreationRecent = document.querySelector("#dateCreationRecent");
const radBtnDateCreationAncien = document.querySelector("#dateCreationAncien");


/// Inputs Filtres ///
// notes
const chkBxNote1 = document.querySelector("#star1");
const chkBxNote2 = document.querySelector("#star2");
const chkBxNote3 = document.querySelector("#star3");
const chkBxNote4 = document.querySelector("#star4");
const chkBxNote5 = document.querySelector("#star5");

// prix
const selectPrixMin = document.querySelector("#prixMin");
const selectPrixMax = document.querySelector("#prixMax");

// statuts
const chkBxOuvert = document.querySelector("#ouvert");

// en ligne / hors ligne (pro)
let chkBxEnLigne;
let chkBxHorsLigne;
if (userType == "pro_public" || userType == "pro_prive") {
  chkBxEnLigne = document.querySelector("#enLigne");
  chkBxHorsLigne = document.querySelector("#horsLigne");
}

// catégories
const chkBxParc = document.querySelector("#Parc");
const chkBxVisite = document.querySelector("#Visite");
const chkBxActivite = document.querySelector("#Activite");
const chkBxSpectacle = document.querySelector("#Spectacle");
const chkBxRestauration = document.querySelector("#Restauration");

// dates
const dateDepart = document.querySelector("#dateDepart");
const dateFin = document.querySelector("#dateFin");

// heures
const heureDebut = document.querySelector("#heureDebut");
const heureFin = document.querySelector("#heureFin");



/* ### Fonction ### */

// Tris
function selectSort(array) {
  
  if (radBtnEnAvant.checked) {
    return sortEnAvant(array);

  } else if (radBtnNoteCroissant.checked) {
    return sortNoteCroissant(array);

  } else if (radBtnNoteDecroissant.checked) {
    return sortNoteDecroissant(array);

  } else if (radBtnprixCroissant.checked) {
    return sortprixCroissant(array);

  } else if (radBtnPrixDecroissant.checked) {
    return sortPrixDecroissant(array);

  } else if (radBtnAvisCroissant.checked) {
    return sortAvisCroissant(array);

  } else if (radBtnPrixDecroissant.checked) {
    return sortAvisDecroissant(array);

  } else if (radBtnDateCreationRecent.checked) {
    return sortDateCreaRecent(array);

  } else if (radBtnDateCreationAncien.checked) {
    return sortDateCreaAncien(array);
  }
  
  return array;
}

function prioriserEnRelief(offre1, offre2) {
  const containsEnReliefA = offre1.option.includes('EnRelief');
    const containsEnReliefB = offre2.option.includes('EnRelief');
    
    if (containsEnReliefA && !containsEnReliefB) {
        return -1;
    } else if (!containsEnReliefA && containsEnReliefB) {
        return 1;
    }
    return 0;
}

function sortEnAvant(array) {
  return array.sort((offre1, offre2) => {
    return prioriserEnRelief(offre1, offre2);
  });
}

function sortNoteCroissant(array) {
  return array.sort((a, b) => {
    return (
      parseFloat(a.noteAvg) - parseFloat(b.noteAvg) || 
      prioriserEnRelief(a, b)
    );
  });
}

function sortNoteDecroissant(array) {
  return array.sort((a, b) => {
    return (
      parseFloat(b.noteAvg) - parseFloat(a.noteAvg) || 
      prioriserEnRelief(a, b)
    );
  });
}

function sortprixCroissant(array) {
  return array.sort((offre1, offre2) => {
    const prix1 = offre1.categorie === "Restaurant" 
      ? getPrixRangeRestaurant(offre1.gammeDePrix)[0] 
      : (offre1.prixMinimal || 0);
    const prix2 = offre2.categorie === "Restaurant" 
      ? getPrixRangeRestaurant(offre2.gammeDePrix)[0] 
      : (offre2.prixMinimal || 0);
    
    return (
      prix1 - prix2 ||
      prioriserEnRelief(offre1, offre2)
    );
  });
}

function sortPrixDecroissant(array) {
  return array.sort((offre1, offre2) => {
    const prix1 = offre1.categorie === "Restaurant" 
      ? getPrixRangeRestaurant(offre1.gammeDePrix)[0] 
      : (offre1.prixMinimal || 0);
    const prix2 = offre2.categorie === "Restaurant" 
      ? getPrixRangeRestaurant(offre2.gammeDePrix)[0] 
      : (offre2.prixMinimal || 0);
    
      return (
        prix2 - prix1 ||
        prioriserEnRelief(offre1, offre2)
      );
  });
}

function sortAvisCroissant(array) {
  return array.sort((offre1, offre2) => {
    const note1 = offre1.nbNote ? parseInt(offre1.nbNote) : 0;
    const note2 = offre2.nbNote ? parseInt(offre2.nbNote) : 0;

    return (
      note1 - note2 || 
      prioriserEnRelief(offre1, offre2)
    );
  });
}

function sortAvisDecroissant(array) {
  return array.sort((offre1, offre2) => {
    const note1 = offre1.nbNote ? parseInt(offre1.nbNote) : 0;
    const note2 = offre2.nbNote ? parseInt(offre2.nbNote) : 0;

    return (
      note2 - note1 ||
      prioriserEnRelief(offre1, offre2)
    );
  });
}

function sortDateCreaRecent(array) {
  return array.sort((offre1, offre2) => {
    const date1 = new Date(offre1.dateCreation);
    const date2 = new Date(offre2.dateCreation);

    return (
      date2.getTime() - date1.getTime() ||
      prioriserEnRelief(offre1, offre2)
    );
  });
}

function sortDateCreaAncien(array) {
  return array.sort((offre1, offre2) => {
    const date1 = new Date(offre1.dateCreation);
    const date2 = new Date(offre2.dateCreation);
    
    return (
      prioriserEnRelief(offre1, offre2) ||
      date1.getTime() - date2.getTime()
    );
  });
}

// Filtres

/**
 * Récupération des catégories sélectionnés
 * @return Liste des catégories sélectionnés
 */
function getSelectedCategories() {
  const categoriesSelection = new Set();
  if (chkBxVisite.checked) categoriesSelection.add("Visite");
  if (chkBxActivite.checked) categoriesSelection.add("Activité");
  if (chkBxSpectacle.checked) categoriesSelection.add("Spectacle");
  if (chkBxParc.checked) categoriesSelection.add("Parc Attraction");
  if (chkBxRestauration.checked) categoriesSelection.add("Restaurant");
  return categoriesSelection;
}

/**
 * Récupérations des notes saisies par l'utilisateur
 * @return liste des notes sélectionnées
 */
function getSelectedNotes() {
  const notesSelection = new Set();
  if (chkBxNote1.checked) notesSelection.add(1);
  if (chkBxNote2.checked) notesSelection.add(2);
  if (chkBxNote3.checked) notesSelection.add(3);
  if (chkBxNote4.checked) notesSelection.add(4);
  if (chkBxNote5.checked) notesSelection.add(5);
  return notesSelection;
}

// Fonction qui détermine la plage de prix en fonction de la gamme pour les restaurants
function getPrixRangeRestaurant(gammeDePrix) {
  switch (gammeDePrix) {
    case '€':
      return [0, 25];
    case '€€':
      return [25, 40];
    case '€€€':
      return [40, Infinity];
    default:
      return [0, Infinity];
  }
}


/**
 * Filtre par statuts
 * @return Récupération de la liste des statuts
 */
function getSelectedStatuts() {
  const statutsSelection = new Set();
  if (chkBxOuvert.checked) statutsSelection.add("EstOuvert");
  return statutsSelection;
}

/**
 * Filtre par statut (unique au pro)
 * @returns liste des statuts
 */
function getSelectedStatutEnLigne() {
  const statutEnLigneHorsLigne = new Set();
  if (userType == "pro_public" || userType == "pro_prive") {
    if (chkBxEnLigne.checked) statutEnLigneHorsLigne.add("actif");
  }
  return statutEnLigneHorsLigne;
}

/**
 * Filtre par heure
 * @returns liste des horaire
 */
function getSelectedHeureRange() {
  const convertirEnMinutes = (heure) => {
    const [hours, minutes] = heure.split(":").map(Number);
    return hours * 60 + minutes;
  };
  const heureDebutValue = heureDebut.value;
  const heureFinValue = heureFin.value;
  return {
    heureDebutMinutes: heureDebutValue ? convertirEnMinutes(heureDebutValue) : null,
    heureFinMinutes: heureFinValue ? convertirEnMinutes(heureFinValue) : null,
  };
}

function filterOffers(offers, search) {
  const categoriesSelection = getSelectedCategories();
  const notesSelection = getSelectedNotes();
  const statutsSelection = getSelectedStatuts();
  const statutEnLigneHorsLigne = getSelectedStatutEnLigne();
  const { heureDebutMinutes, heureFinMinutes } = getSelectedHeureRange();
  const prixMin = parseInt(selectPrixMin.value);
  const prixMax = parseInt(selectPrixMax.value);
  const searchLower = search ? search.toLowerCase() : "";

  return offers.filter(offer => {
    if (categoriesSelection.size > 0 && !categoriesSelection.has(offer.categorie)) return false;
    if (notesSelection.size > 0 && !notesSelection.has(Math.ceil(offer.noteAvg))) return false;
    if (statutsSelection.size > 0 && !statutsSelection.has(offer.ouverture)) return false;
    if (statutEnLigneHorsLigne.size > 0 && !statutEnLigneHorsLigne.has(offer.statut)) return false;

    if (offer.categorie === 'Restaurant') {
      const [prixMinOffre, prixMaxOffre] = getPrixRangeRestaurant(offer.gammeDePrix);
      if (prixMinOffre < prixMin || prixMaxOffre > prixMax) return false;
    } else {
      if (offer.prixMinimal < prixMin || offer.prixMinimal > prixMax) return false;
    }

    if (heureDebutMinutes !== null && heureFinMinutes !== null) {
      let horaires = [];
      if (offer.categorie === 'Spectacle' && offer.horaire) {
        horaires = offer.horaire.map(h => JSON.parse(h));
      } else {
        if (offer.horaireMidi) horaires.push(...offer.horaireMidi.map(h => JSON.parse(h)));
        if (offer.horaireSoir) horaires.push(...offer.horaireSoir.map(h => JSON.parse(h)));
      }
      if (!horaires.some(horaire => {
        const heureDebut = convertirEnMinutes(horaire.heureOuverture || horaire.heureouverture);
        const heureFin = convertirEnMinutes(horaire.heureFermeture || horaire.heurefermeture);
        return heureDebut < heureFinMinutes && heureFin > heureDebutMinutes;
      })) return false;
    }

    // Recherche
    if (searchLower) {
      const categorie = offer.categorie || '';
      const nomOffre = offer.nomOffre || '';
      const gammeDePrix = offer.gammeDePrix || '';
      
      const numeroRue = offer.numeroRue || '';
      const rue = offer.rue || '';
      const ville = offer.ville || '';
      const pays = offer.pays || '';
      const codePostal = offer.codePostal || '';
      
      const adresse = `${numeroRue} ${rue} ${ville} ${pays} ${codePostal}`.toLowerCase();

      const containsTag = offer.tags.some(tag => tag.toLowerCase().includes(searchLower));
      const matchesCategorie = categorie.toLowerCase().includes(searchLower);
      const matchesNomOffre = nomOffre.toLowerCase().includes(searchLower);
      const matchesAdresse = adresse.includes(searchLower);
      const matchesGammeDePrix = gammeDePrix === search;

      if (!(containsTag || matchesCategorie || matchesNomOffre || matchesAdresse || matchesGammeDePrix)) return false;
    }

    return true;
  });
}

/**
 * Compte le nombre de filtre qui sont appliqués
 * @param {array} filtres liste des filtres utilisés
 */
function countFiltre(filtres) {
  let count = 0;

  filtres.forEach(filtre => {
    switch (filtre) {
      case "filtrerParCategorie":
        count += checkboxesCat.filter(chk => chk.checked).length;
        break;

      case "filtrerParNotes":
        count += checkboxesNote.filter(chk => chk.checked).length;
        break;

      case "filtrerParPrix":
        count += selectPrixMin.value === "0" ? 0 : 1;
        count += selectPrixMax.value === "999999" ? 0 : 1;
        break;
        
        case "filtrerParStatuts":
          count += checkboxesStatuts.filter(chk => chk.checked).length;
          break;
          
        case "filtrerParHeure":
          count += heureDebut.value === "" ? 0 : 1;
          count += heureFin.value === "" ? 0 : 1;
        break;

      case "filtrerParStatutEnLigneHorsLigne":
        count += checkboxesEnligneHorsLigne.filter(chk => chk.checked).length;
        break;
    
      default:
        break;
    }
  });
  return count;
}

/**
 * Rénitialise les inputs des tries et filtres
 */
function resetFiltreTri() {
  // Reset recherche
  searchInput.value = ""

  // Reset Tri
  radBtnEnAvant.checked = true;

  // Reset les checkboxs du filtre
  resetCheckbox(checkboxesCat);
  resetCheckbox(checkboxesNote);
  resetCheckbox(checkboxesStatuts);
  if (userType == "pro_public" || userType == "pro_prive") {
    resetCheckbox(checkboxesEnligneHorsLigne);
  }

  // Autres informations du filtre
  selectPrixMin.value = "0";
  selectPrixMax.value = "999999";
  heureDebut.value = "";
  heureFin.value = "";

  // Application des changements
  goToPage(1);
}

/**
 * Rénitialise tout les checkboxs
 * @param {array} array_chckbx liste de checkboxs
 */
function resetCheckbox(array_chckbx) {
  array_chckbx.forEach(checkbox => checkbox.checked = false);
}

/**
 * filtre, tri et affcihe les offres dynamiquement
 * @param {array} array liste d'offres 
 * @param {string} search mot de recherche entré
 * @param {integer} elementStart élément de départ pour la pagination
 * @param {integer} nbElement nombre d'élément pour la pagnation
 */
function sortAndFilter(array, search, elementStart, nbElement) {
  // Filtres + Recherches
  array = filterOffers(array, search);

  let filtres = [
    "filtrerParCategorie",
    "filtrerParNotes",
    "filtrerParPrix",
    "filtrerParStatuts",
    "filtrerParHeure"
  ];

  if (userType == "pro_public" || userType == "pro_prive") {
    filtres.push("filtrerParStatutEnLigneHorsLigne");
  }

  const count = countFiltre(filtres);
  const spanApplication = document.getElementById("filtreApplique");
  const btnReset = document.getElementById("btnReset");

  if (count == 0) {
    spanApplication.style.display = "none";
    btnReset.setAttribute("disabled", "true");
    btnReset.style.backgroundColor = "#a4a1a1";
    
  } else {
    spanApplication.textContent = count;
    spanApplication.style.display = "block";
    btnReset.removeAttribute("disabled");
    btnReset.style.backgroundColor = "#f06565";
  }
  
  // Tris
  array = selectSort(array);
  // Affichage
  displayOffers(array, elementStart, nbElement);
  // Affichage de la pagination
  updatePagination(array.length, nbElement);

  //affichage des ping sur la carte
  addPing(array);
} 

function getCategoryIcon(categorie, chemin) {
  const icons = {
    "Activité": "pointeur-activite.png",
    "Parc Attraction": "pointeur-parc.png",
    "Restaurant": "pointeur-restaurant.png",
    "Spectacle": "pointeur-spectacle.png",
    "Visite": "pointeur-visite.png"
  };
  
  return L.icon({
    iconUrl: chemin + (icons[categorie] || "pointeur-interrogation.png"),
    iconSize: [70, 70],
    iconAnchor: [35, 70],
    popupAnchor: [0, -70]
  });
}

function addPing(array) {
  removeAllPing();

  array.forEach(elt => {
    let imageCategorie;
    imageCategorie = getCategoryIcon(elt["categorie"], "../img/icone/pointeurOffre/");

    geocode(`${elt["numeroRue"]} ${elt["rue"]}, ${elt["codePostal"]} ${elt["ville"]}`)
      .then(location => {
        const latLng = location;
        if (latLng) {
          let marker = L.marker(latLng, {icon: imageCategorie})
            .bindPopup(`
              <div id="popupCarte">
                <h3>${elt['nomOffre']}</h3>
                <div>
                  ${displayStar(parseFloat(elt["noteAvg"]))}
                </div>
                <p><strong>Résumé :</strong> ${elt['resume']}</p>
                <p><strong>Adresse :</strong> <a href="https://www.google.com/maps?q=${encodeURIComponent(elt['numeroRue'] + ' ' + elt['rue'] + ', ' + elt['codePostal'] + ' ' + elt['ville'])}" target="_blank">${elt['numeroRue']} ${elt['rue']}, ${elt['codePostal']} ${elt['ville']}</a></p>
                <div id="divBtnPopup">
                  <a href="https://www.google.com/maps?q=${encodeURIComponent(elt['numeroRue'] + ' ' + elt['rue'] + ', ' + elt['codePostal'] + ' ' + elt['ville'])}" target="_blank">Itinéraire</a>
                  <a href="#" class="viewOffer" data-id="${elt['idOffre']}">Voir l'offre</a>
                </div>
                <form id="offerForm${elt["idOffre"]}" action="detailsOffer.php" method="POST" style="display:none;">
                  <input type="hidden" name="idoffre" id="idoffre" value="${elt["idOffre"]}">
                </form>
              </div>
            `);
          markers.addLayer(marker);

          // Ajouter un gestionnaire d'événements pour le lien "Voir l'offre"
          marker.getPopup().on('contentupdate', function() {
            const viewOfferLink = marker.getPopup().getElement().querySelector('.viewOffer');
            if (viewOfferLink) {
              viewOfferLink.addEventListener('click', function(e) {
                e.preventDefault();
                const offerId = this.getAttribute('data-id');
                const form = document.getElementById(`offerForm${offerId}`);
                if (form) {
                  form.submit();
                } else {
                  console.error(`Formulaire pour l'offre ${offerId} introuvable.`);
                }
              });
            }
          });
        }
      })
      .catch(error => {
        console.error("Erreur lors de la géocodification : ", error);
      });
    map.addLayer(markers);
  });
}

function removeAllPing() {
  markers.eachLayer(marker => {
    map.removeLayer(marker); 
  });
  markers.clearLayers();
}

/**
 * 
 * @param {integer} totalItems taille de la liste
 * @param {integer} nbElement nombre d'élément maximum par page
 */
function updatePagination(totalItems, nbElement) {
  const paginationLinks = document.getElementById('pagination-liste');
  paginationLinks.innerHTML = '';

  const totalPages = Math.ceil(totalItems / nbElement);

  for (let page = 1; page <= totalPages; page++) {
    const pageLink = document.createElement('li');
    const link = document.createElement('a');
    link.href = "#";
    link.textContent = page;

    link.onclick = (event) => {
      event.preventDefault();
      goToPage(page);
    };

    pageLink.appendChild(link);
    paginationLinks.appendChild(pageLink);
  }

  // Mettre à jour l'ID de la page actuelle
  const links = paginationLinks.querySelectorAll('a');
  links.forEach(link => {
    if (parseInt(link.innerText) === currentPage) {
      link.id = "pageActuel";
    } else {
      link.removeAttribute("id");
    }
  });
}


function goToPage(page) {
  currentPage = page;
  let search = searchInput.value;
  sortAndFilter(arrayOffer, search, (page - 1) * nbElement, nbElement);
}

/* ### Affichage des offres ### */

function displayOffers(array, elementStart, nbElement) {
  const bloc = document.querySelector(".searchoffre");
  bloc.innerHTML = "";
  
  if (!array.length) {
    bloc.innerHTML = "<p>Aucune offre trouvée</p>";
    return;
  }
  
  array.slice(elementStart, elementStart + nbElement).forEach(offer => bloc.appendChild(createOfferForm(offer)));
}

function redirectionFormDynamique(event) {
  if (event.target.tagName.toLowerCase() !== "a") {
    event.preventDefault();
    event.currentTarget.submit();
  }
}

function createOfferForm(offer) {
  const form = document.createElement("form");
  form.setAttribute("tabindex", "0");
  form.classList.add("searchA");
  form.action = "/detailsOffer.php";
  form.method = "post";
  
  form.innerHTML = `
    <input type="hidden" name="idoffre" value="${offer.idOffre}">
  `;
  
  form.appendChild(createCard(offer));
  form.addEventListener("click", redirectionFormDynamique);
  form.addEventListener("keydown", (event) => {
    if (event.key === "Enter") {
      redirectionFormDynamique(event);
    }
  });
  
  return form;
}

function createCard(offer) {
  const card = document.createElement("section");
  card.classList.add("carteOffre", "flip-card");
  if (offer.option.includes("EnRelief")) card.classList.add("optionEnRelief");
  
  card.innerHTML = `
    <div class="flip-card-inner">
      ${createFront(offer)}
      ${createBack(offer)}
    </div>
  `;
  
  return card;
}

function createFront(offer) {
  return `
    <article class="flip-card-front">
      <figure>
        ${offer.option.includes("EnRelief") ? '<img class="premiumImg" src="../img/icone/service-premium.png" alt="icone premium" loading="lazy">' : ''}
        <img src="${offer.images?.[0] ?? ''}" alt="${offer.nomOffre}" title="${offer.nomOffre}" loading="lazy">
        <figcaption>
          <h4 class="title ${getStatusClass(offer.statut)}">${offer.nomOffre ?? ''}</h4>
          <div>
            <p class="ville">${offer.ville}, ${offer.codePostal}</p>
            ${displayStar(offer.noteAvg)}
          </div>
        </figcaption>
      </figure>
    </article>
  `;
}

function createBack(offer) {
  return `
    <article class="flip-card-back">
      ${createLogoCategorie(offer)}
      <div class="content">
        <h4 class="title">${offer.nomOffre}</h4>
        ${displayStar(offer.noteAvg)}
        <div class="${userType.startsWith('pro_') ? 'typeOffre' : 'nomPro'}">
          ${userType.startsWith('pro_') ? '' : `Proposé par ${offer.nomUser}`}
        </div>
        <div class="information">
          <div class="resume">${offer.resume ?? ''}</div>
          <address>
            <div>${offer.ville}, ${offer.codePostal}</div>
            <div>${offer.numeroRue ?? ''} ${offer.rue ?? ''}</div>
          </address>
        </div>
        ${ajouterTag(offer)}
      </div>
      ${userType.startsWith('pro_') ? `<p class="StatutAffiche ${getStatusClass(offer.statut)}">${getStatusText(offer.statut)}</p>` : ''}
    </article>
  `;
}

function getStatusClass(statut) {
  return {
    inactif: "horslgnOffre",
    delete: "suppression",
  }[statut] || "";
}

function getStatusText(statut) {
  return {
    actif: "En ligne",
    inactif: "Hors ligne",
    delete: "Suppression",
  }[statut] || "";
}

function createLogoCategorie(offer) {
  const categories = {
    Activité: "activity.png",
    "Parc Attraction": "park.png",
    Restaurant: "restaurant.png",
    Spectacle: "show2.png",
    Visite: "visit.png",
  };
  
  return `
    <figure>
      <img src="../img/icone/offerCategory/${categories[offer.categorie] || 'interrogation.png'}" alt="${offer.categorie}" title="${offer.categorie}" loading="lazy">
      ${offer.categorie === "Restaurant" ? `<figcaption>${offer.gammeDePrix}</figcaption>` : ''}
    </figure>
  `;
}

function ajouterTag(offer) {
  const tagsToShow = offer.tags.slice(0, 2).map(tag => `<a  tabindex="-1" class="tagIndex" href="index.php?search=${tag.replace('_', '+')}#searchIndex">${tag.replace('_', ' ')}</a>`).join('');
  return `<div class="tagsCard">${tagsToShow}${offer.tags.length > 2 ? `<a class="tagIndex" tabindex="-1">+ ${offer.tags.length - 2} autre${offer.tags.length - 2 > 1 ? 's' : ''}</a>` : ''}</div>`;
}

function displayStar(note) {
  let stars = Array(5).fill('<div class="star vide"></div>');
  for (let i = 0; i < Math.floor(note); i++) stars[i] = '<div class="star pleine"></div>';
  if (note % 1 !== 0) stars[Math.floor(note)] = `<div class="star partielle" style="--pourcentage: ${(note % 1) * 100}%"></div>`;
  return `<div class="blocStar">${stars.join('')}<span>${note}/5</span></div>`;
}


/* ### Evènements ### */

document.addEventListener('DOMContentLoaded', function() {
  // Événements de recherche
  searchInput.addEventListener("input", () => goToPage(currentPage));
  
  // Événements des tris
  radBtnEnAvant.addEventListener("click", () => goToPage(currentPage));
  
  // notes
  radBtnNoteCroissant.addEventListener("click", () => goToPage(currentPage));
  radBtnNoteDecroissant.addEventListener("click", () => goToPage(currentPage));
  
  // prix
  radBtnprixCroissant.addEventListener("click", () => goToPage(currentPage));
  radBtnPrixDecroissant.addEventListener("click", () => goToPage(currentPage));
  
  // avis
  radBtnAvisCroissant.addEventListener("click", () => goToPage(currentPage));
  radBtnAvisDecroissant.addEventListener("click", () => goToPage(currentPage));
  
  // date création
  radBtnDateCreationRecent.addEventListener("click", () => goToPage(currentPage));
  radBtnDateCreationAncien.addEventListener("click", () => goToPage(currentPage));
  

  // Événements des filtres
  // notes
  chkBxNote1.addEventListener("click", () => goToPage(1));
  chkBxNote2.addEventListener("click", () => goToPage(1));
  chkBxNote3.addEventListener("click", () => goToPage(1));
  chkBxNote4.addEventListener("click", () => goToPage(1));
  chkBxNote5.addEventListener("click", () => goToPage(1));
  
  // prix
  selectPrixMin.addEventListener("change", () => goToPage(1));
  selectPrixMax.addEventListener("change", () => goToPage(1));
  
  // statuts
  chkBxOuvert.addEventListener("click", () => goToPage(1));
  
  // catégories
  chkBxParc.addEventListener("click", () => goToPage(1));
  chkBxVisite.addEventListener("click", () => goToPage(1));
  chkBxActivite.addEventListener("click", () => goToPage(1));
  chkBxSpectacle.addEventListener("click", () => goToPage(1));
  chkBxRestauration.addEventListener("click", () => goToPage(1));
  
  // dates
  dateDepart.addEventListener("change", () => goToPage(1));
  dateFin.addEventListener("change", () => goToPage(1));

  // heures
  heureDebut.addEventListener("change", () => goToPage(1));
  heureFin.addEventListener("change", () => goToPage(1));
  
  
  // en ligne / hors ligne (pro)
  if (userType == "pro_public" || userType == "pro_prive") {
    chkBxEnLigne.addEventListener("click", () => goToPage(1));
    chkBxHorsLigne.addEventListener("click", () => goToPage(1));
  }

  document.querySelector(".taillebtn button").addEventListener("click", () => resetFiltreTri());
});

const checkboxesCat = [chkBxVisite, chkBxActivite, chkBxSpectacle, chkBxParc, chkBxRestauration];
const checkboxesNote = [chkBxNote1, chkBxNote2, chkBxNote3, chkBxNote4, chkBxNote5];
const checkboxesStatuts = [chkBxOuvert];
const checkboxesEnligneHorsLigne = [chkBxEnLigne, chkBxHorsLigne];