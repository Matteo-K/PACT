/**
 * @file
 * @author Mattéo Kervadec & Antoine Guillerm
 * Ensemble des fonctions et évènements 
 * afin de trier et filtrer les offres de la page de recherche
 */
let currentPage = 1;
let nbElement = 10;

let userType, arrayOffer, page;
document.addEventListener('DOMContentLoaded', function() {
  const offersDataElement = document.getElementById('offers-data');
  const userDataElement = document.getElementById('user-data');
  
  const offersData = offersDataElement.getAttribute('data-offers');
  // console.log(offersData); // Débugger

  try {
    arrayOffer = JSON.parse(offersData);
    arrayOffer = Object.values(arrayOffer);
  } catch (error) {
    console.error("Erreur de parsing JSON :", error);
  }
  
  userType = userDataElement.getAttribute('data-user');

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

// catégories
const chkBxParc = document.querySelector("#Parc");
const chkBxVisite = document.querySelector("#Visite");
const chkBxActivite = document.querySelector("#Activite");
const chkBxSpectacle = document.querySelector("#Spectacle");
const chkBxRestauration = document.querySelector("#Restauration");

// dates
// const dateDepart = document.querySelector("#dateDepart");
// const dateFin = document.querySelector("#dateFin");
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

function sortEnAvant(array) {
  return array.sort((offre1, offre2) => {
    const containsEnReliefA = offre1.option.includes('EnRelief');
    const containsEnReliefB = offre2.option.includes('EnRelief');
    
    if (containsEnReliefA && !containsEnReliefB) {
        return -1;
    } else if (!containsEnReliefA && containsEnReliefB) {
        return 1;
    }
    return 0;
  });
}

function sortNoteCroissant(array) {
  return array.sort((a, b) => parseFloat(a.noteAvg) - parseFloat(b.noteAvg));
}

function sortNoteDecroissant(array) {
  return array.sort((a, b) => parseFloat(b.noteAvg) - parseFloat(a.noteAvg));
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
    
    return prix1 - prix2;
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
    
    return prix2 - prix1;
  });
}

function sortAvisCroissant(array) {
  return array.sort((offre1, offre2) => {
    const note1 = offre1.nbNote ? parseInt(offre1.nbNote) : 0;
    const note2 = offre2.nbNote ? parseInt(offre2.nbNote) : 0;

    return note1 - note2;
  });
}

function sortAvisDecroissant(array) {
  return array.sort((offre1, offre2) => {
    const note1 = offre1.nbNote ? parseInt(offre1.nbNote) : 0;
    const note2 = offre2.nbNote ? parseInt(offre2.nbNote) : 0;

    return note2 - note1;
  });
}

function sortDateCreaRecent(array) {
  return array.sort((offre1, offre2) => {
    const date1 = new Date(offre1.dateCreation);
    const date2 = new Date(offre2.dateCreation);

    return date2.getTime() - date1.getTime()
  });
}

function sortDateCreaAncien(array) {
  return array.sort((offre1, offre2) => {
    const date1 = new Date(offre1.dateCreation);
    const date2 = new Date(offre2.dateCreation);
    
    return date1.getTime() - date2.getTime()
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
    } else {
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
    // statutsSelection = ["ouvert", "ferme"];
    return offers;
  }

  return offers.filter(offer => statutsSelection.includes(offer.ouverture));
}





// function filtrerParPeriode(offers) {
//   const heureDepart = heureDebut.value ? new Date(`2024-12-05T${heureDebut.value}:00`) : null;
//   const heureFin = heureFin.value ? new Date(`2024-12-05T${heureFin.value}:00`) : null;

//   if (!heureDepart || !heureFin) {
//     return offers;
//   }

//   const offresFiltrees = [];

//   offers.forEach(offer => {
//     let offreEstVisible = false;

//     if (offer.categorie === 'Restaurant' || offer.categorie === 'Visite' || offer.categorie === 'Parc' || offer.categorie === 'Activite') {

//       const ouverture = new Date(`2024-12-05T${offer.heureOuverture}:00`);
//       const fermeture = new Date(`2024-12-05T${offer.heureFermeture}:00`);

//       if ((ouverture >= heureDepart && ouverture <= heureFin) || 
//           (fermeture >= heureDepart && fermeture <= heureFin) || 
//           (ouverture <= heureDepart && fermeture >= heureFin)) {
//         offreEstVisible = true;
//       }
//     }

//     if (offer.categorie === 'Spectacle') {
//       const heureOffre = new Date(`2024-12-05T${offer.horaire}:00`);

//       if (heureOffre >= heureDepart && heureOffre <= heureFin) {
//         offreEstVisible = true;
//       }
//     }

//     if (offreEstVisible) {
//       offresFiltrees.push(offer);
//     }
//   });

//   return offresFiltrees;
// }



// Fonction de filtre par période
function filtrerParPeriode(offers) {
  const dateDepartValue = new Date(dateDepart.value);
  const dateFinValue = new Date(dateFin.value);
  const heureDebutValue = heureDebut.value;
  const heureFinValue = heureFin.value;

  if (isNaN(dateDepartValue.getTime()) || isNaN(dateFinValue.getTime())) {
    return offers;
  }

  return offers.filter(offer => {
    if (!offer.heureOuverture || !offer.heureFermeture) {
      return false;
    }

    let heureValide = true;
    if (heureDebutValue && heureFinValue) {
      const [heureDebutH, heureDebutM] = heureDebutValue.split(':').map(Number);
      const [heureFinH, heureFinM] = heureFinValue.split(':').map(Number);

      const debutRange = heureDebutH * 60 + heureDebutM;
      const finRange = heureFinH * 60 + heureFinM;

      const [ouvertureH, ouvertureM] = offer.heureOuverture.split(':').map(Number);
      const [fermetureH, fermetureM] = offer.heureFermeture.split(':').map(Number);

      const ouvertureMinutes = ouvertureH * 60 + ouvertureM;
      const fermetureMinutes = fermetureH * 60 + fermetureM;

      if (debutRange <= fermetureMinutes && finRange >= ouvertureMinutes) {
        heureValide = true;
      } else {
        heureValide = false;
      }
    }

    return heureValide;
  });
}



// 1- récup la date et l'heure de début
// 2- récup la date et l'heure de fin
// 3- si l'utilisateur ne chage rien, on affiche toute les offres de base (entre 8h et 23h de base)
// 4- si l'utilisateur change une valeur dans l'input, on prend cette valeur et on met à jour l'affichage

// Fonction de filtre par période
// function filtrerParPeriode(offers) {
//   const dateDepartValue = new Date(dateDepart.value);
//   const dateFinValue = new Date(dateFin.value);
//   const heureDebutValue = heureDebut.value;
//   const heureFinValue = heureFin.value;

//   console.log("Date de départ sélectionnée: ", dateDepartValue);
//   console.log("Date de fin sélectionnée: ", dateFinValue);

//   const debutRange = dateDepartValue && heureDebutValue;
//   const finRange = dateFinValue && heureFinValue;

//   console.log("Heure de début sélectionnée: ", debutRange);
//   console.log("Heure de fin sélectionnée: ", finRange);

//   const [heureDebutH, heureDebutM] = heureDebutValue.split(':').map(Number);
//   const [heureFinH, heureFinM] = heureFinValue.split(':').map(Number);

//   const debutRangeMinutes = heureDebutH * 60 + heureDebutM;
//   const finRangeMinutes = heureFinH * 60 + heureFinM;

//   console.log("Plage horaire de début en minutes: ", debutRangeMinutes);
//   console.log("Plage horaire de fin en minutes: ", finRangeMinutes);

//   return offers.filter(offer => {
//     const dateOffer = new Date(offer.date);
//     console.log("Date de l'offre: ", dateOffer);

//     if (isNaN(dateOffer.getTime())) {
//       console.log("Offre avec date invalide, exclue");
//       return false;
//     }

//     if ((dateDepartValue && dateOffer < dateDepartValue) || (dateFinValue && dateOffer > dateFinValue)) {
//       console.log("Offre hors de la plage de dates, exclue");
//       return false;
//     }

//     if (offer.heureOuverture && offer.heureFermeture) {
//       const [ouvertureH, ouvertureM] = offer.heureOuverture.split(':').map(Number);
//       const [fermetureH, fermetureM] = offer.heureFermeture.split(':').map(Number);

//       const ouvertureMinutes = ouvertureH * 60 + ouvertureM;
//       const fermetureMinutes = fermetureH * 60 + fermetureM;

//       console.log("Heure d'ouverture de l'offre en minutes: ", ouvertureMinutes);
//       console.log("Heure de fermeture de l'offre en minutes: ", fermetureMinutes);

//       if (debutRangeMinutes <= fermetureMinutes && finRangeMinutes >= ouvertureMinutes) {
//         console.log("Les horaires de l'offre chevauchent avec les horaires demandés");
//         return true;
//       } else {
//         console.log("Les horaires de l'offre ne correspondent pas aux horaires demandés, exclue");
//       }
//     } else {
//       console.log("Offre sans horaires valides, exclue");
//     }

//     return false;
//   });
// }



// Fonction de filtre par lieu
function filtrerParLieu(offers) {
  const lieuSelection = [];


  return offers.filter(offer => lieuSelection.includes(offer.note));
}

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
 * filtre, tri et affcihe les offres dynamiquement
 * @param {array} array liste d'offres 
 * @param {string} search mot de recherche entré
 * @param {integer} elementStart élément de départ pour la pagination
 * @param {integer} nbElement nombre d'élément pour la pagnation
 */
function sortAndFilter(array, search, elementStart, nbElement) {
  // Recherche
  array = searchOffer(array, search);

  // Filtres
  array = filtrerParCategorie(array);
  array = filtrerParNotes(array);
  array = filtrerParPrix(array);
  array = filtrerParStatuts(array);
  //array = filtrerParPeriode(array);

  // Tris
  array = selectSort(array);

  // Affichage
  displayOffers(array, elementStart, nbElement);

  // Affichage de la pagination
  updatePagination(array.length, nbElement);
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
  search = searchInput.value;
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
  let card = document.createElement("div");
  card.classList.add("carteOffre");
  card.classList.add("flip-card");
  if (offer.option.includes('EnRelief')) {
    card.classList.add("optionEnRelief");
  }

  let content = document.createElement("div");
  content.classList.add("flip-card-inner");
  
  let front = document.createElement("div");
  front.classList.add("flip-card-front");
  front.style.backgroundImage = offer.images[0];

  let titre = document.createElement("h4");
  titre.textContent = offer.nomOffre;

  let back = document.createElement("div");
  back.classList.add("flip-card-back");

  front.appendChild(titre.cloneNode(true));
  back.appendChild(titre.cloneNode(true));

  content.appendChild(front);
  content.appendChild(back);

  card.appendChild(content);

  let infoOffre = document.createElement("div");
  infoOffre.classList.add("infoOffre");

  infoOffre.appendChild(proStatut(offer));
  infoOffre.appendChild(localisation(offer));
  infoOffre.appendChild(ajouterTag(offer));

  let resume = document.createElement("p");
  resume.classList.add("searchResume");
  if (offer.resume != "") {
    resume.textContent = offer.resume;
  } else {
    resume.textContent = "";
  }

  infoOffre.appendChild(resume);

  //card.appendChild(infoOffre);
  //card.appendChild(avisSearch(offer));

  return card;
}

function proStatut(offer) {
  let proStatut = document.createElement("div");
  proStatut.classList.add("ProStatut");

  // Titre de l'offre
  let titre = document.createElement("p");
  titre.classList.add("searchTitre");
  titre.textContent = offer.nomOffre;

  proStatut.appendChild(titre);

  // statut de l'offre si professionnel
  if (userType == "pro_public" || userType == "pro_prive") {

    let span = document.createElement("span");
    span.classList.add("StatutAffiche");
    if (offer.statut != 'actif') {
      span.classList.add("horslgnOffre");
      span.textContent = "Hors-Ligne";
    } else {
      span.textContent = "En-Ligne";
    }

    proStatut.appendChild(span);
  }
  return proStatut;
}

function localisation(offer) {
  let gras = document.createElement("strong");

  let gammeDePrix = "";
  if (typeof offer.gammeDePrix !== 'undefined') {
    gammeDePrix = offer.gammeDePrix;
  }

  let p = document.createElement("p");
  p.textContent = offer.ville + " " + gammeDePrix + " ⋅ " + offer.categorie;

  gras.appendChild(p);

  return gras;
}

function ajouterTag(offer) {
  let tags = document.createElement("div");
  tags.classList.add("searchCategorie");

  offer.tags.forEach(element => {

    let tag = document.createElement("a");
    tag.classList.add("searchTag");
    tag.textContent = element.replace("_", " ");
    tag.setAttribute("href", "index.php?search="+element.replace("_", "+")+"#searchIndex");
    tags.appendChild(tag);
  });

  return tags;
}

function displayStar(note) {
  let container = document.createElement("span");
  container.classList.add("blcStarSearch");

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
// dateDepart.addEventListener("change", () => goToPage(1));
// dateFin.addEventListener("change", () => goToPage(1));
heureDebut.addEventListener("change", () => goToPage(1));
heureFin.addEventListener("change", () => goToPage(1));