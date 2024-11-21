/**
 * @file
 * @author Mattéo Kervadec & Antoine Guillerm
 * Ensemble des fonctions et évènements 
 * afin de trier et filtrer les offres de la page de recherche
 */

document.addEventListener("DOMContentLoaded", () => {

  // La liste des offres est stocké dans la variable "arrayOffer";

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
  const chkBxNote1 = document.querySelector("#1star");
  const chkBxNote2 = document.querySelector("#2star");
  const chkBxNote3 = document.querySelector("#3star");
  const chkBxNote4 = document.querySelector("#4star");
  const chkBxNote5 = document.querySelector("#5star");

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

  /// Tri

  /// Filtre

  /* ### Evènements ### */

  /// Tri

  /// Fonction
});