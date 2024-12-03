/**
 * @file
 * @author Mattéo Kervadec & Antoine Guillerm
 * Ensemble des fonctions et évènements 
 * afin de trier et filtrer les offres de la page de recherche
 */
let currentPage = 1;
let nbElement = 15;

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
const dateDepart = document.querySelector("#dateDepart");
const heureDebut = document.querySelector("#heureDebut");
const dateFin = document.querySelector("#dateFin");
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
  return array.sort((a, b) => attribuerEtoiles(parseFloat(a.noteAvg)) - attribuerEtoiles(parseFloat(b.noteAvg)));
}

function sortNoteDecroissant(array) {
  return array.sort((a, b) => attribuerEtoiles(parseFloat(b.noteAvg)) - attribuerEtoiles(parseFloat(a.noteAvg)));
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
    return parseInt(offre1.nbNote) - parseInt(offre2.nbNote);
  });
}

function sortAvisDecroissant(array) {
  return array.sort((offre1, offre2) => {
    return parseInt(offre2.nbNote) - parseInt(offre1.nbNote);
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
      const prixMaxOffreRestaurant = prixRange[1];

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
    // statutsSelection = ["ouvert", "ferme"];
    return offers;
  }

  return offers.filter(offer => statutsSelection.includes(offer.ouverture));
}


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
    if (!offer.date) {
      return false;
    }

    const dateOffre = new Date(offer.date);
    const heureOffre = offer.date.split('T')[1];

    const dateValide = dateOffre >= dateDepartValue && dateOffre <= dateFinValue;
    const heureValide = (heureDebutValue && heureFinValue) ? (heureOffre >= heureDebutValue && heureOffre <= heureFinValue) : true;

    return dateValide && heureValide;
  });
}


// Fonction de filtre par lieu
function filtrerParLieu(offers) {
  const lieuSelection = [];


  return offers.filter(offer => lieuSelection.includes(offer.note));
}


// Fonction global
function sortAndFilter(array, elementStart, nbElement) {
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
  window.scrollTo({
    top: 0,
    left: 0,
    behavior: 'smooth'
  });
  sortAndFilter(arrayOffer, (page - 1) * nbElement, nbElement);
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

  // Image principale
  let img = document.createElement("img");
  img.classList.add("searchImage");
  if (offer.images.length == 0) {
    img.setAttribute("alt", "Pas_de_photo_attribué_à_l'offre");
  } else {
    img.setAttribute("alt", "photo_principal_de_l'offre");
    img.setAttribute("src", offer.images[0]);
  }
  card.appendChild(img);

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
    resume.textContent = "Pas de resume saisie";
  }

  infoOffre.appendChild(resume);

  card.appendChild(infoOffre);
  card.appendChild(avisSearch(offer));

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
    tag.setAttribute("href", "search.php?search="+element.replace("_", "+"));
    tags.appendChild(tag);
  });

  return tags;
}

function note(offer) {
  let section = document.createElement("section");
  section.classList.add("searchNote");

  let divStar = document.createElement("div");
  divStar.classList.add("noteSearch");

  divStar.appendChild(displayStar(offer));

  let note = document.createElement("p");
  note.textContent = offer.noteAvg;
  divStar.appendChild(note);
  
  section.appendChild(divStar);

  return section;
}

function avisSearch(offer) {
  let div = document.createElement("div");
  div.classList.add("searchAvis");

  let titre = document.createElement("p");
  titre.classList.add("avisSearch");
  titre.textContent = "Les avis les plus récent :";

  let divTitre = document.createElement("div");

  // Ouvert fermé
  let ouverture = document.createElement("p");
  ouverture.id = "couleur-" + offer.idOffre;
  if (offer.ouverture == "EstOuvert") {
    ouverture.classList.add("searchStatutO");
    ouverture.textContent = "Ouvert";
  } else {
    ouverture.classList.add("searchStatutF");
    ouverture.textContent = "Fermé";
  }

  divTitre.appendChild(titre)
  divTitre.appendChild(ouverture);

  let tempPasAvis = document.createElement("p");

  div.appendChild(divTitre);
  div.appendChild(note(offer));
  div.appendChild(tempPasAvis);
  div.appendChild(displayAvis(offer));

  return div;
}

function displayStar(offer) {
  let container = document.createElement("span");
  container.classList.add("blcStarSearch");

  const etoilesPleines = Math.floor(offer.noteAvg);
  const reste = offer.noteAvg - etoilesPleines;

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

function displayAvis(offer) {
  let blcAvis = document.createElement("div");

  console.log(offer).avis;
  return blcAvis;
}

/* ### Evènements ### */

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
heureDebut.addEventListener("change", () => goToPage(1));
dateFin.addEventListener("change", () => goToPage(1));
heureFin.addEventListener("change", () => goToPage(1));