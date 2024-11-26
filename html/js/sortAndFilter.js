/**
 * @file
 * @author Mattéo Kervadec & Antoine Guillerm
 * Ensemble des fonctions et évènements 
 * afin de trier et filtrer les offres de la page de recherche
 */

let userType, arrayOffer;
document.addEventListener('DOMContentLoaded', function() {
  const offersDataElement = document.getElementById('offers-data');
  const userDataElement = document.getElementById('user-data');
  arrayOffer = JSON.parse(offersDataElement.getAttribute('data-offers'));
  userType = userDataElement.getAttribute('data-user');
});



/// Input de Tri ///
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

/// Input de Filtre ///
// note
const chkBxNote1 = document.querySelector("#star1");
const chkBxNote2 = document.querySelector("#star2");
const chkBxNote3 = document.querySelector("#star3");
const chkBxNote4 = document.querySelector("#star4");
const chkBxNote5 = document.querySelector("#star5");

// prix
const selectPrixMin = document.querySelector("#prixMin");
const selectPrixMax = document.querySelector("#prixMax");

// statut
const chkBxOuvert = document.querySelector("#ouvert");
const chkBxFerme = document.querySelector("#ferme");

// catégorie
const chkBxVisite = document.querySelector("#Visite");
const chkBxActivite = document.querySelector("#Activite");
const chkBxSpectacle = document.querySelector("#Spectacle");
const chkBxRestauration = document.querySelector("#Restauration");
const chkBxParc = document.querySelector("#Parc");

// date
const dateDepart = document.querySelector("#dateDepart");
const heureDebut = document.querySelector("#heureDebut");
const dateFin = document.querySelector("#dateFin");
const heureFin = document.querySelector("#heureFin");


/* ### Fonction ### */

// Tris

// Filtres

// Fonction de filtre par catégorie
function filtrerParCategorie(offers) {
  const categoriesSelection = [];
  
  if (chkBxParc.checked) categoriesSelection.push("Parc");
  if (chkBxVisite.checked) categoriesSelection.push("Visite");
  if (chkBxActivite.checked) categoriesSelection.push("Activite");
  if (chkBxSpectacle.checked) categoriesSelection.push("Spectacle");
  if (chkBxRestauration.checked) categoriesSelection.push("Restauration");

  if (categoriesSelection.length == 0) {
    categoriesSelection = ["Parc", "Visite", "Activite", "Spectacle", "Restauration"];
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

  return offers.filter(offer => notesSelection.includes(offer.note));
}


function displayOffers(array, elementStart, nbElement) {
  let offers = array.slice(elementStart, nbElement);
  offers.forEach(element => {displayOffer(element)});
}

function displayOffer(offer) {
  const bloc = document.querySelector(".searchoffre");

  let form = document.createElement("form");
  form.classList.add("searchA");
  form.setAttribute("action", "/detailsOffer.php?&ouvert=");
  form.setAttribute("method", "post");

  let input = document.createElement("input");
  input.setAttribute("type", "hidden");
  input.setAttribute("name", "idoffre");
  input.setAttribute("value", offer.idOffre);

  form.appendChild(input);
  form.appendChild(createCard(offer));
  form.appendChild(avisSearch(offer));

  bloc.appendChild(form);
}

function createCard(offer) {
  let card = document.createElement("div");
  card.classList.add("carteOffre");

  // Image principale
  let img = document.createElement("img");
  img.classList.add("searchImage");
  if (offer.length = 0) {
    img.setAttribute("alt", "Pas_de_photo_attribué_à_l'offre");
  } else {
    img.setAttribute("alt", "photo_principal_de_l'offre");
    img.setAttribute("src", offer.images[0]);
  }
  card.appendChild(img);photo_principal_de_l

  let infoOffre = document.createElement("div");
  infoOffre.classList.add("infoOffre");

  infoOffre.appendChild(proStatut(offer));
  infoOffre.appendChild(localisation(offer));
  infoOffre.appendChild(ajouterTag(offer));

  let resume = document.createElement("p");
  resume.classList.add("searchResume");
  if (offer.resume != "") {
    resume.textContent = "Pas de resume saisie";
  } else {
    resume.textContent = offer.resume;
  }

  infoOffre.appendChild(note(offer));

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
    parent.appendChild(tag);
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

displayOffers(arrayOffer, 0, 15);

/* ### Evènements ### */

// Tris

// Filtre

chkBxVisite.addEventListener();
chkBxActivite.addEventListener();
chkBxSpectacle.addEventListener();
chkBxRestauration.addEventListener();
chkBxParc.addEventListener();