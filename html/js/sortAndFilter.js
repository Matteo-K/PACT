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
const chkBxFerme = document.querySelector("#ferme");

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

function attribuerEtoiles(note) {
  if (note <= 1) return 1;
  else if (note <= 2) return 2;
  else if (note <= 3) return 3;
  else if (note <= 4) return 4;
  else return 5;
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

// Fonction de filtre par catégorie
function filtrerParCategorie(offers) {
  const categoriesSelection = [];
  
  if (chkBxVisite.checked) categoriesSelection.push("Visite");
  if (chkBxActivite.checked) categoriesSelection.push("Activité");
  if (chkBxSpectacle.checked) categoriesSelection.push("Spectacle");
  if (chkBxParc.checked) categoriesSelection.push("Parc Attraction");
  if (chkBxRestauration.checked) categoriesSelection.push("Restaurant");

  if (categoriesSelection.length == 0) {
    // categoriesSelection = ["Parc", "Visite", "Activite", "Spectacle", "Restauration"];
    return offers;
  }

  return offers.filter(offer => categoriesSelection.includes(offer.categorie));
}


// Fonction de filtre par notes
function filtrerParNotes(offers) {
  const notesSelection = [];

  if (chkBxNote1.checked) notesSelection.push(1);
  if (chkBxNote2.checked) notesSelection.push(2);
  if (chkBxNote3.checked) notesSelection.push(3);
  if (chkBxNote4.checked) notesSelection.push(4);
  if (chkBxNote5.checked) notesSelection.push(5);

  if (notesSelection.length == 0) {
    // notesSelection = [1, 2, 3, 4, 5];
    return offers;
  }
  
  return offers.filter(offer => {
    const noteArrondie = Math.ceil(offer.noteAvg);
    return notesSelection.includes(noteArrondie);
  });
}


// Fonction de filtre par prix
function filtrerParPrix(offers) {
  const prixMin = parseInt(selectPrixMin.value);
  const prixMax = parseInt(selectPrixMax.value);

  return offers.filter(offer => {
    if (offer.categorie === 'Restaurant') {
      const prixRange = getPrixRangeRestaurant(offer.gammeDePrix);
      const prixMinOffreRestaurant = prixRange[0];
      const prixMaxOffreRestaurant = prixRange[0];

      return prixMinOffreRestaurant >= prixMin && prixMaxOffreRestaurant <= prixMax;
    } 
    
    else {
      const prixMinOffreAutres = (offer.prixMinimal || 0);
      return prixMinOffreAutres >= prixMin && prixMinOffreAutres <= prixMax;
    }
  });
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


// Fonction de filtre par statuts
function filtrerParStatuts(offers) {
  const statutsSelection = [];

  if (chkBxOuvert.checked) statutsSelection.push("EstOuvert");
  if (chkBxFerme.checked) statutsSelection.push("EstFermé");

  if (statutsSelection.length == 0) {
    return offers;
  }

  return offers.filter(offer => statutsSelection.includes(offer.ouverture));
}


// Fonction de filtre par statuts
function filtrerParStatutEnLigneHorsLigne(offers) {
  const statutEnLigneHorsLigne = [];

  if (chkBxEnLigne.checked) statutEnLigneHorsLigne.push("actif");
  if (chkBxHorsLigne.checked) statutEnLigneHorsLigne.push("inactif");

  if (statutEnLigneHorsLigne.length == 0) {
    return offers;
  }

  return offers.filter(offer => statutEnLigneHorsLigne.includes(offer.statut));
}


// Fonction de filtre par heure
function filtrerParHeure(offers) {
  // Récupérer les heures sélectionnées par l'utilisateur
  const heureDebutValue = heureDebut.value;
  const heureFinValue = heureFin.value;

  // Convertir l'heure en minutes depuis minuit pour faciliter la comparaison
  const convertirEnMinutes = (heure) => {
    console.log(heure);
    const [hours, minutes] = heure.split(":").map(Number);
    return hours * 60 + minutes;
  };

  // Si les heures saisies sont invalides, retourner toutes les offres
  if (!heureDebutValue || !heureFinValue || isNaN(convertirEnMinutes(heureDebutValue)) || isNaN(convertirEnMinutes(heureFinValue))) {
    return offers;
  }

  const heureDebutMinutes = convertirEnMinutes(heureDebutValue);
  const heureFinMinutes = convertirEnMinutes(heureFinValue);

  return offers.filter(offer => {
    // Vérifier les horaires pour les spectacles
    if (offer.categorie === 'Spectacle') {
      const horairesSpectacle = offer.horaire || [];

      let data = [];
      horairesSpectacle.forEach(element => {
        data.push(JSON.parse(element));
      });

      console.table(data);

      // Pour chaque horaire précis de spectacle
      return data.some(horaire => {
        console.log(horaire.horaire);
        const heureDebutSpectacle = convertirEnMinutes(horaire.heureouverture);  
        const heureFinSpectacle = convertirEnMinutes(horaire.heurefermeture); 

        // Vérifier si l'horaire du spectacle chevauche l'intervalle de l'utilisateur
        return (heureDebutSpectacle < heureFinMinutes && heureFinSpectacle > heureDebutMinutes);
      });
    }
    
    // Vérifier les horaires pour les autres types d'offres
    else {

      const horaireMidi = offer.horaireMidi || [];
      const horaireSoir = offer.horaireSoir || [];

      let data = [];
      horaireMidi.forEach(element => {
        data.push(JSON.parse(element));
      });

      horaireSoir.forEach(element => {
        data.push(JSON.parse(element));
      });

      // Pour chaque horaire de l'offre
      return data.some(horaire => {
        const heureDebut = convertirEnMinutes(horaire.heureOuverture);
        const heureFin = convertirEnMinutes(horaire.heureFermeture);
        // Vérifier si l'horaire de l'offre chevauche l'intervalle de l'utilisateur
        return (heureDebut < heureFinMinutes && heureFin > heureDebutMinutes);
      });
    }
  });
}


// Fonction de filtre par date

/**
 * Filtre la liste d'offres suivant le mot clé de recherche pour correspondre
 * - l'un des tags
 * - une catégorie d'offre
 * - une partie du nom de l'offre
 * - une partie de l'adresse
 * - une gamme de prix
 * @param {array} offers - Liste des offres à filtrer
 * @param {string} search - Mot de recherche
 * @returns {array} - Liste des offres filtrées par rapport au mot de recherche
 */
function searchOffer(offers, search) {

  if (!search) {
    return offers;
  }

  return offers.filter((item) => {
    
    // Extraire les données nécessaires
    const categorie = item.categorie || '';
    const nomOffre = item.nomOffre || '';
    const gammeDePrix = item.gammeDePrix || '';
    
    const numeroRue = item.numeroRue || '';
    const rue = item.rue || '';
    const ville = item.ville || '';
    const pays = item.pays || '';
    const codePostal = item.codePostal || '';
    
    const adresse = `${numeroRue} ${rue} ${ville} ${pays} ${codePostal}`.toLowerCase();

    // Filtre les données
    const containsTag = item.tags.some(tag => tag.toLowerCase().includes(search.toLowerCase()));
    const matchesCategorie = categorie && categorie.toLowerCase().includes(search.toLowerCase());
    const matchesNomOffre = nomOffre && nomOffre.toLowerCase().includes(search.toLowerCase());
    const matchesAdresse = adresse && adresse.includes(search.toLowerCase());
    const matchesGammeDePrix = gammeDePrix === search;

    return containsTag || matchesCategorie || matchesNomOffre || matchesAdresse || matchesGammeDePrix;
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
      case filtrerParCategorie:
        count += checkboxesCat.filter(chk => chk.checked).length;
        break;

      case filtrerParNotes:
        count += checkboxesNote.filter(chk => chk.checked).length;
        break;

      case filtrerParPrix:
        count += selectPrixMin.value === "0" ? 0 : 1;
        count += selectPrixMax.value === "999999" ? 0 : 1;
        break;
        
        case filtrerParStatuts:
          count += checkboxesStatuts.filter(chk => chk.checked).length;
          break;
          
        case filtrerParHeure:
          count += heureDebut.value === "" ? 0 : 1;
          count += heureFin.value === "" ? 0 : 1;
        break;

      case filtrerParStatutEnLigneHorsLigne:
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
  // Recherche
  console.log(array);
  array = searchOffer(array, search);

  // Filtres
  let filtres = [
    filtrerParCategorie,
    filtrerParNotes,
    filtrerParPrix,
    filtrerParStatuts,
    filtrerParHeure
  ];

  if (userType == "pro_public" || userType == "pro_prive") {
    filtres.push(filtrerParStatutEnLigneHorsLigne);
  }
  
  // Application des filtres
  array = filtres.reduce((acc, filtre) => filtre(acc), array);
  const count = countFiltre(filtres);
  const spanApplication = document.getElementById("filtreApplique");
  const btnReset = document.getElementById("btnReset");

  if (count == 0) {
    spanApplication.style.display = "none";
    btnReset.setAttribute("onclick", "");
    btnReset.style.backgroundColor = "#a4a1a1";
    
  } else {
    spanApplication.textContent = count;
    spanApplication.style.display = "block";
    btnReset.setAttribute("onclick", "resetModal()");
    btnReset.style.backgroundColor = "#f06565";
  }
  
  // Tris
  array = selectSort(array);
  console.log(array);
  // Affichage
  displayOffers(array, elementStart, nbElement);
  // Affichage de la pagination
  updatePagination(array.length, nbElement);

  //affichage des ping sur la carte
  addPing(array);
}

function addPing(array) {
  removeAllPing();

  array.forEach(elt => {
    let chemin = "../img/icone/pointeurOffre/";
    switch (elt[""]){};
    geocode(`${elt["numeroRue"]} ${elt["rue"]}, ${elt["codePostal"]} ${elt["ville"]}`)
      .then(location => {
        const latLng = location;
        if (latLng) {
          let marker = L.marker(latLng)
            .bindPopup(`
              <div id="popupCarte">
                <h3>${elt['nomOffre']}</h3>
                <div>
                  ${displayStar(parseFloat(elt["noteAvg"])).outerHTML}
                  <p>${elt["noteAvg"]} /5</p>
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
    map.removeLayer(marker); // Affiche les coordonnées de chaque marqueur
  });
  markers.clearLayers();  // Vider le tableau après suppression
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
  if (array.length != 0) {
    let offers = array.slice(elementStart, elementStart + nbElement);
    offers.forEach(element => {displayOffer(element)});
  } else {
    let pasOffre = document.createElement("p");
    pasOffre.textContent = "Aucune offre trouvée";
    bloc.appendChild(pasOffre);
  }
}

function displayOffer(offer) {
  const bloc = document.querySelector(".searchoffre");

  let form = document.createElement("form");
  form.classList.add("searchA");
  form.setAttribute("action", "/detailsOffer.php");
  form.setAttribute("method", "post");

  let input = document.createElement("input");
  input.setAttribute("type", "hidden");
  input.setAttribute("name", "idoffre");
  input.setAttribute("value", offer.idOffre);

  form.appendChild(input);
  form.appendChild(createCard(offer));

  form.addEventListener("click", (event) => {
    if (event.target.tagName.toLowerCase() === "a") {
      return;
    }
    event.preventDefault();
    form.submit();
  });

  bloc.appendChild(form);
}

function createCard(offer) {
  let card = document.createElement("section");
  card.classList.add("carteOffre");
  card.classList.add("flip-card");
  if (offer.option.includes('EnRelief')) {
    card.classList.add("optionEnRelief");
  }

  let content = document.createElement("div");
  content.classList.add("flip-card-inner");

  let front = createFront(offer);
  let back = createBack(offer);

  content.appendChild(front);
  content.appendChild(back);

  card.appendChild(content);

  return card;
}

function createFront(offer) {
  let article = document.createElement("article");
  article.classList.add("flip-card-front");

  let figure = document.createElement("figure");

  let img = document.createElement("img");
  img.setAttribute("src", offer.images?.[0] ?? "");
  img.setAttribute("alt", offer.nomOffre);
  img.setAttribute("title", offer.nomOffre);
  img.setAttribute("loading","lazy");

  let figcaption = document.createElement("figcaption");

  let h4 = document.createElement("h4");
  h4.classList.add("title");
  if (userType == "pro_public" || userType == "pro_prive") {
    h4.classList.add("StatutAffiche");
    switch (offer.statut) {
      case 'inactif':
        h4.classList.add("horslgnOffre");
        break;

      case 'delete':
        h4.classList.add("suppression");
        break;
    }
  }
  h4.textContent = offer.nomOffre ?? "";

  let div = document.createElement("div");

  let p = document.createElement("p");
  p.classList.add("ville");
  p.textContent = offer.ville + ", " + offer.codePostal;

  let stars = displayStar(offer.noteAvg);
  stars.classList.add("blocStar");

  let note = document.createElement("span");
  note.textContent = offer.noteAvg + "/5";

  stars.appendChild(note);

  div.appendChild(p);
  div.appendChild(stars);

  figcaption.appendChild(h4);
  figcaption.appendChild(div);

  if (offer.option.includes('EnRelief')) {
    let imgRelief = document.createElement("img");
    imgRelief.classList.add("premiumImg");
    imgRelief.setAttribute("src", "../img/icone/service-premium.png");
    imgRelief.setAttribute("alt", "icone premium");
    figure.appendChild(imgRelief);
  }

  figure.appendChild(img);
  figure.appendChild(figcaption);

  article.appendChild(figure);

  return article;
}

function createBack(offer) {
  let article = document.createElement("article");
  article.classList.add("flip-card-back");

  let figure = createLogoCategorie(offer);

  let content = document.createElement("div");
  content.classList.add("content");
  
  let h4 = document.createElement("h4");
  h4.classList.add("title");
  h4.textContent = offer.nomOffre;

  let stars = displayStar(offer.noteAvg);
  stars.classList.add("blocStar");

  let note = document.createElement("span");
  note.textContent = offer.noteAvg + "/5";

  let blcNbNote = document.createElement("span");
  let nbNote = offer.nbNote ?? 0;
  blcNbNote.textContent = "("+nbNote + "note" + (nbNote > 1 ? "s" : "") + ")";

  let information = document.createElement("div");
  information.classList.add("information");

  let resume = document.createElement("div");
  resume.classList.add("resume");
  resume.textContent = offer.resume ?? "";

  let adresse = document.createElement("address");

  let ville = document.createElement("div");
  ville.textContent = (offer.ville ?? "") + ", " + (offer.codePostal ?? "");

  let adressePostal = document.createElement("div");
  adressePostal.textContent = (offer.numeroRue ?? "") + " " + (offer.rue ?? "");

  let tags = ajouterTag(offer);
  tags.classList.add("tagsCard");

  adresse.appendChild(ville);
  adresse.appendChild(adressePostal);

  information.appendChild(resume);
  information.appendChild(adresse);

  stars.appendChild(note);
  stars.appendChild(blcNbNote);

  content.appendChild(h4);
  content.appendChild(stars);

  let infoTypeUser = document.createElement("div");
  if (userType == "pro_public" || userType == "pro_prive") {
    infoTypeUser.classList.add("typeOffre");
    // Décrit le type de l'offre (premium, en relief, a la une)
  } else {
    infoTypeUser.classList.add("nomPro");
    infoTypeUser.textContent = "Proposé par " + offer.nomUser;
  }

  content.appendChild(infoTypeUser);
  content.appendChild(information);
  content.appendChild(tags);

  article.appendChild(figure);

  if (userType == "pro_public" || userType == "pro_prive") {
    let enLigne = document.createElement("p");
    enLigne.classList.add("StatutAffiche");

    switch (offer.statut) {
      case 'actif':
        enLigne.textContent = "En ligne";
        break;

      case 'inactif':
        enLigne.classList.add("horslgnOffre");
        enLigne.textContent = "Hors ligne";
        break;

      case 'delete':
        enLigne.classList.add("suppression");
        enLigne.textContent = "Suppression";
        break;
    }
    article.appendChild(enLigne);
  }

  article.appendChild(content);

  return article;
}

function createLogoCategorie(offer) {

  let figure = document.createElement("figure");

  let imageCategorie;
  let chemin = "../img/icone/offerCategory/";
  switch (offer.categorie) {
    case 'Activité':
      imageCategorie = "activity.png";
      break;
      
    case 'Parc Attraction':
      imageCategorie = "park.png";
      break;

    case 'Restaurant':
      imageCategorie = "restaurant.png";
      break;

    case 'Spectacle':
      imageCategorie = "show2.png";
      break;

    case 'Visite':
      imageCategorie = "visit.png";
      break;

    default:
      imageCategorie = "interrogation.png";
      break;
  }

  let img = document.createElement("img");
  img.setAttribute("src", chemin + imageCategorie);
  img.setAttribute("alt", offer.categorie);
  img.setAttribute("title", offer.categorie);

  figure.appendChild(img);

  if (offer.categorie == "Restaurant") {
    let figcaption = document.createElement("figcaption");
    figcaption.textContent = offer.gammeDePrix;

    figure.appendChild(figcaption);
  }
  return figure;
}

function ajouterTag(offer) {
  let tags = document.createElement("div");
  let nbTagMax = 2;
  let plusTag = 0;

  if (offer.tags.length > 0) { 
    let tagsToShow = offer.tags.slice(0, nbTagMax);
    
    tagsToShow.forEach(element => {
      if (element !== "") {
        let tag = document.createElement("a");
        tag.classList.add("tagIndex");
        tag.textContent = element.replace("_", " ");
        tag.setAttribute("href", "index.php?search=" + element.replace("_", "+") + "#searchIndex");
        tags.appendChild(tag);
      }
    });

    if (offer.tags.length > nbTagMax) {
      plusTag = offer.tags.length - nbTagMax;
      let moreTag = document.createElement("a");
      moreTag.classList.add("tagIndex");
      moreTag.textContent = `+ ${plusTag} autre${plusTag > 1 ? "s" : ""}`;
      tags.appendChild(moreTag);
    }
  }

  return tags;

}

function displayStar(note) {
  let container = document.createElement("div");

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
  chkBxFerme .addEventListener("click", () => goToPage(1));
  
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
const checkboxesStatuts = [chkBxOuvert, chkBxFerme];
const checkboxesEnligneHorsLigne = [chkBxEnLigne, chkBxHorsLigne];