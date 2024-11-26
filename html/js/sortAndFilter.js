/**
 * @file
 * @author Mattéo Kervadec & Antoine Guillerm
 * Ensemble des fonctions et évènements 
 * afin de trier et filtrer les offres de la page de recherche
 */


document.addEventListener('DOMContentLoaded', function() {
  const offersDataElement = document.getElementById('offers-data');
  const arrayOffer = JSON.parse(offersDataElement.getAttribute('data-offers'));
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

  if (categoriesSelection == []) {
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


function displayOffers($array) {
  
}

function displayOffer($offer) {
  document.createElement("a");
}


/* ### Evènements ### */

// Tris

// Filtre

chkBxVisite.addEventListener()
chkBxActivite.addEventListener()
chkBxSpectacle.addEventListener()
chkBxRestauration.addEventListener()
chkBxParc.addEventListener()