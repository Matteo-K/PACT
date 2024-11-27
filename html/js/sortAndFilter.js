/**
 * @file
 * @author Mattéo Kervadec & Antoine Guillerm
 * Ensemble des fonctions et évènements 
 * afin de trier et filtrer les offres de la page de recherche
 */

let nbElement = 15;
let userType, arrayOffer, page;
document.addEventListener('DOMContentLoaded', function() {
  const offersDataElement = document.getElementById('offers-data');
  const userDataElement = document.getElementById('user-data');
  
  const offersData = offersDataElement.getAttribute('data-offers');
  //console.log(offersData); // Débugger

  arrayOffer = JSON.parse(offersData);
  arrayOffer = Object.values(arrayOffer);

  userType = userDataElement.getAttribute('data-user');

  const params = new URLSearchParams(window.location.search);

  if (params.has('page') && params.get('page').trim() !== '') {
      page = params.get('page');
  } else {
      page = 1;
  }

  sortAndFilter(arrayOffer, nbElement * (page-1), nbElement);

  document.querySelectorAll(".searchoffre form").forEach(form => {
    form.addEventListener("click", (event) => {
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
const radBtnDateRecent = document.querySelector("#dateRecent");
const radBtnDateAncien = document.querySelector("#dateAncien");


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
  const sortFunctions = {
    "radBtnEnAvant": sortEnAvant,
    "radBtnNoteCroissant": sortNoteCroissant,
    "radBtnNoteDecroissant": sortNoteDecroissant,
    "radBtnprixCroissant": sortprixCroissant,
    "radBtnPrixDecroissant": sortPrixDecroissant,
    "radBtnDateRecent": sortDateRecent,
    "radBtnDateAncien": sortDateAncien
  };

  for (let key in sortFunctions) {
    if (key.checked) {
      console.log("tri avec " + key);
      array = sortFunctions[key](array);
      break;
    }
  }

  return array;
}

function sortEnAvant(array) {
  return array;
}

function sortNoteCroissant(array) {
  return array;
}

function sortNoteDecroissant(array) {
  return array;
}

function sortprixCroissant(array) {
  return array;
}

function sortPrixDecroissant(array) {
  return array;
}

function sortDateRecent(array) {
  return array.sort((offre1, offre2) => offre2.dateCreation - offre1.dateCreation);
}

function sortDateAncien(array) {
  return array.sort((offre1, offre2) => offre1.dateCreation - offre2.dateCreation);
}




// Filtres

// Fonction de filtre par catégorie
function filtrerParCategorie(offers) {
  const categoriesSelection = [];
  
  if (chkBxParc.checked) categoriesSelection.push("Parc Attraction");
  if (chkBxVisite.checked) categoriesSelection.push("Visite");
  if (chkBxActivite.checked) categoriesSelection.push("Activité");
  if (chkBxSpectacle.checked) categoriesSelection.push("Spectacle");
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
    const prixOffre = offer.prix;
    return prixOffre >= prixMin && prixOffre <= prixMax;
  });
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
  const dateDepart = new Date(dateDepart.value);
  const dateFin = new Date(dateFin.value);
  const heureDebut = heureDebut.value;
  const heureFin = heureFin.value;

  if (isNaN(dateDebut.getTime()) || isNaN(dateFin.getTime())) {
    return offers;
  }

  return offers.filter(offer => {
    const dateOffre = new Date(offer.date);
    const heureOffre = offer.date.split('T')[1];

    const dateValide = dateOffre >= dateDebut &&  dateOffre <= dateFin;

    const heureValide = (heureDebut && heureFin) ? (heureOffre >= heureDebut && heureOffre <= heureFin) : true;

    return dateValide && heureValide;
  });
}


// Fonction de filtre par lieu
// function filtrerParLieu(offers) {
//   const lieuSelection = [];


//   return offers.filter(offer => lieuSelection.includes(offer.note));
// }


// Fonction global
function sortAndFilter(array, elementStart, nbElement) {
  // Filtres
  array = filtrerParCategorie(array);
  // array = filtrerParNotes(array);
  array = filtrerParPrix(array);
  array = filtrerParStatuts(array);
  // array = filtrerParPeriode(array);

  // Tris
  array = selectSort(array);

  // Affichage
  displayOffers(array, elementStart, nbElement);
}

/* ### Affichage des offres ### */

function displayOffers(array, elementStart, nbElement) {
  const bloc = document.querySelector(".searchoffre");
  if (array.length != 0) {
    bloc.innerHTML = "";
    let offers = array.slice(elementStart, nbElement);
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
  infoOffre.appendChild(note(offer));

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

    let tag = document.createElement("span");
    tag.classList.add("searchTag");
    tag.textContent = element.replace("_", " ");
    tags.appendChild(tag);
  });

  return tags;
}

function note(offer) {
  let section = document.createElement("section");
  section.classList.add("searchNote");

  let note = document.createElement("p");
  note.textContent = offer.noteAvg;
  section.appendChild(note);
  
  let ouverture = document.createElement("p");
  ouverture.id = "couleur-" + offer.idOffre;
  if (offer.ouverture == "EstOuvert") {
    ouverture.classList.add("searchStatutO");
    ouverture.textContent = "Ouvert";
  } else {
    ouverture.classList.add("searchStatutF");
    ouverture.textContent = "Fermé";
  }
  section.appendChild(ouverture);

  return section;
}

function avisSearch(offer) {
  let div = document.createElement("div");
  div.classList.add("searchAvis");

  let titre = document.createElement("p");
  titre.classList.add("avisSearch");
  titre.textContent = "Les avis les plus récent :";

  let tempPasAvis = document.createElement("p");
  tempPasAvis.textContent = "Pas d'avis";

  div.appendChild(titre);
  div.appendChild(tempPasAvis);

  return div;
}


/* ### Evènements ### */

// Événements des tris
radBtnEnAvant.addEventListener("click", () => sortAndFilter(arrayOffer, (page - 1) * nbElement, nbElement));

// notes
radBtnNoteCroissant.addEventListener("click", () => sortAndFilter(arrayOffer, (page - 1) * nbElement, nbElement));
radBtnNoteDecroissant.addEventListener("click", () => sortAndFilter(arrayOffer, (page - 1) * nbElement, nbElement));

// prix
radBtnprixCroissant.addEventListener("click", () => sortAndFilter(arrayOffer, (page - 1) * nbElement, nbElement));
radBtnPrixDecroissant.addEventListener("click", () => sortAndFilter(arrayOffer, (page - 1) * nbElement, nbElement));

// dates
radBtnDateRecent.addEventListener("click", () => sortAndFilter(arrayOffer, (page - 1) * nbElement, nbElement));
radBtnDateAncien.addEventListener("click", () => sortAndFilter(arrayOffer, (page - 1) * nbElement, nbElement));



// Événements des filtres
// notes
chkBxNote1.addEventListener("click", () => sortAndFilter(arrayOffer, (page - 1) * nbElement, nbElement));
chkBxNote2.addEventListener("click", () => sortAndFilter(arrayOffer, (page - 1) * nbElement, nbElement));
chkBxNote3.addEventListener("click", () => sortAndFilter(arrayOffer, (page - 1) * nbElement, nbElement));
chkBxNote4.addEventListener("click", () => sortAndFilter(arrayOffer, (page - 1) * nbElement, nbElement));
chkBxNote5.addEventListener("click", () => sortAndFilter(arrayOffer, (page - 1) * nbElement, nbElement));

// prix
selectPrixMin.addEventListener("change", () => sortAndFilter(arrayOffer, (page - 1) * nbElement, nbElement));
selectPrixMax.addEventListener("change", () => sortAndFilter(arrayOffer, (page - 1) * nbElement, nbElement));

// statuts
chkBxOuvert.addEventListener("click", () => sortAndFilter(arrayOffer, (page - 1) * nbElement, nbElement));
chkBxFerme .addEventListener("click", () => sortAndFilter(arrayOffer, (page - 1) * nbElement, nbElement));

// catégories
chkBxParc.addEventListener("click", () => sortAndFilter(arrayOffer, (page - 1) * nbElement, nbElement));
chkBxVisite.addEventListener("click", () => sortAndFilter(arrayOffer, (page - 1) * nbElement, nbElement));
chkBxActivite.addEventListener("click", () => sortAndFilter(arrayOffer, (page - 1) * nbElement, nbElement));
chkBxSpectacle.addEventListener("click", () => sortAndFilter(arrayOffer, (page - 1) * nbElement, nbElement));
chkBxRestauration.addEventListener("click", () => sortAndFilter(arrayOffer, (page - 1) * nbElement, nbElement));

// dates
dateDepart.addEventListener("change", () => sortAndFilter(arrayOffer, (page - 1) * nbElement, nbElement));
heureDebut.addEventListener("change", () => sortAndFilter(arrayOffer, (page - 1) * nbElement, nbElement));
dateFin.addEventListener("change", () => sortAndFilter(arrayOffer, (page - 1) * nbElement, nbElement));
heureFin.addEventListener("change", () => sortAndFilter(arrayOffer, (page - 1) * nbElement, nbElement));