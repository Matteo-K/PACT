/**
 * @file
 * @author Mattéo Kervadec & Antoine Guillerm
 * Ensemble des fonctions et évènements 
 * afin de trier et filtrer les offres de la page de recherche
 */

document.addEventListener("DOMContentLoaded", () => {

  // La liste des offres est stocké dans la variable "arrayOffer";

  /// Input de Tri ///
  const radBtnEnAvant = document.getElementById("miseEnAvant");

  // note
  const radBtnNoteCroissant = document.getElementById("noteCroissant");
  const radBtnNoteDecroissant = document.getElementById("noteDecroissant");

  // prix
  const radBtnprixCroissant = document.getElementById("prixCroissant");
  const radBtnPrixDecroissant = document.getElementById("prixDecroissant");

  // date
  const radBtnDateRecent = document.getElementById("dateRecent");
  const radBtnDateAncien = document.getElementById("dateAncien");

  /// Input de Filtre ///
  // note
  const chkBxNote1 = document.getElementById("1star");
  const chkBxNote2 = document.getElementById("2star");
  const chkBxNote3 = document.getElementById("3star");
  const chkBxNote4 = document.getElementById("4star");
  const chkBxNote5 = document.getElementById("5star");

  // prix
  const selectPrixMin = document.getElementById("prixMin");
  const selectPrixMax = document.getElementById("prixMax");

  // statut
  const chkBxOuvert = document.getElementById("ouvert");
  const chkBxFerme = document.getElementById("ferme");

  // catégorie
  const chkBxVisite = document.getElementById("Visite");
  const chkBxActivite = document.getElementById("Activite");
  const chkBxSpectacle = document.getElementById("Spectacle");
  const chkBxRestauration = document.getElementById("Restauration");
  const chkBxParc = document.getElementById("Parc");

  // date
  const dateDepart = document.getElementById("dateDepart");
  const heureDebut = document.getElementById("heureDebut");
  const dateFin = document.getElementById("dateFin");
  const heureFin = document.getElementById("heureFin");


  /* ### Fonction ### */

  /// Tri

  /// Filtre

  /* ### Evènements ### */

  /// Tri

  /// Fonction
});