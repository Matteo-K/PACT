/**
 * @file
 * @author Mattéo Kervadec & Antoine Guillerm
 * Ensemble des fonctions et évènements 
 * afin de trier et filtrer les offres de la page de recherche
 */
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

function filtrerParPeriode(offers) {
  const dateDepartValue = new Date(dateDepart.value);
  const dateFinValue = new Date(dateFin.value);

  const heureDebutValue = heureDebut.value ? heureDebut.value.split(':').map(Number) : null;
  const heureFinValue = heureFin.value ? heureFin.value.split(':').map(Number) : null;

  // Vérification de la validité des dates
  if (isNaN(dateDepartValue.getTime()) || isNaN(dateFinValue.getTime())) {
    return offers;
  }

  return offers.filter(offer => {
    // Vérifier si l'offre a des horaires et une date valide
    if (!offer.dateRepresentation || !offer.horaire) {
      return false;
    }

    // Vérifier si l'offre est dans la plage de dates
    const dateRepresentation = new Date(offer.dateRepresentation);
    if (dateRepresentation < dateDepartValue || dateRepresentation > dateFinValue) {
      return false;
    }

    // Vérifier si l'offre est dans la plage horaire
    if (heureDebutValue && heureFinValue) {
      const [debutH, debutM] = heureDebutValue;
      const [finH, finM] = heureFinValue;

      const debutMinutes = debutH * 60 + debutM;
      const finMinutes = finH * 60 + finM;

      return offer.horaire.some(horaire => {
        const [heureOuvertureH, heureOuvertureM] = horaire.heureOuverture.split(':').map(Number);
        const [heureFermetureH, heureFermetureM] = horaire.heureFermeture.split(':').map(Number);

        const ouvertureMinutes = heureOuvertureH * 60 + heureOuvertureM;
        const fermetureMinutes = heureFermetureH * 60 + heureFermetureM;

        return (
          (debutMinutes <= fermetureMinutes && finMinutes >= ouvertureMinutes)
        );
      });
    }

    // Si aucune heure n'est définie, on considère que l'offre est valide
    return true;
  });
}




// Fonction de filtre par période
// function filtrerParPeriode(offers) {
//   const dateDepartValue = new Date(dateDepart.value);
//   const dateFinValue = new Date(dateFin.value);
//   const heureDebutValue = heureDebut.value;
//   const heureFinValue = heureFin.value;

  // test console
  // autre: console.log(offers[0].horaireMidi);
  // autre: console.log(offers[0].horaireSoir);
  
//   console.log(offers[0].horaire); // spectacle
//   let data = [];
//   offers[0].horaire.forEach(element => {
//     data.push(JSON.parse(element));
//   });

//   console.table(data);
//   console.log(data[0].daterepresentation);

//   if (isNaN(dateDepartValue.getTime()) || isNaN(dateFinValue.getTime())) {
//     return offers;
//   }

//   return offers.filter(offer => {
//     if (!offer.heureOuverture || !offer.heureFermeture) {
//       return false;
//     }

//     let heureValide = true;
//     if (heureDebutValue && heureFinValue) {
//       const [heureDebutH, heureDebutM] = heureDebutValue.split(':').map(Number);
//       const [heureFinH, heureFinM] = heureFinValue.split(':').map(Number);

//       const debutRange = heureDebutH * 60 + heureDebutM;
//       const finRange = heureFinH * 60 + heureFinM;

//       const [ouvertureH, ouvertureM] = offer.heureOuverture.split(':').map(Number);
//       const [fermetureH, fermetureM] = offer.heureFermeture.split(':').map(Number);

//       const ouvertureMinutes = ouvertureH * 60 + ouvertureM;
//       const fermetureMinutes = fermetureH * 60 + fermetureM;

//       if (debutRange <= fermetureMinutes && finRange >= ouvertureMinutes) {
//         heureValide = true;
//       } else {
//         heureValide = false;
//       }
//     }

//     return heureValide;
//   });
// }



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
// function filtrerParLieu(offers) {
//   const lieuSelection = [];


//   return offers.filter(offer => lieuSelection.includes(offer.note));
// }

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
  array = filtrerParPeriode(array);

  if (userType == "pro_public" || userType == "pro_prive") {
    array = filtrerParStatutEnLigneHorsLigne(array);
  }

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

  let figcaption = document.createElement("figcaption");

  let h4 = document.createElement("h4");
  h4.classList.add("title");
  if (userType == "pro_public" || userType == "pro_prive") {
    h4.classList.add("StatutAffiche");
    if (offer.statut != "actif") {
      h4.classList.add("horslgnOffre");
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
    if (offer.statut == "actif") {
      enLigne.textContent = "En ligne";
    } else {
      enLigne.classList.add("horslgnOffre");
      enLigne.textContent = "Hors ligne";
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

  if (offer.tags.length > 0) { 
    offer.tags.forEach(element => {
      
      if (element != "") {
        let tag = document.createElement("a");
        tag.classList.add("tagIndex");
        tag.textContent = element.replace("_", " ");
        tag.setAttribute("href", "index.php?search="+element.replace("_", "+")+"#searchIndex");
        tags.appendChild(tag);
      }
    });
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
  heureDebut.addEventListener("change", () => goToPage(1));
  heureFin.addEventListener("change", () => goToPage(1));
  
  
  // en ligne / hors ligne (pro)
  if (userType == "pro_public" || userType == "pro_prive") {
    chkBxEnLigne.addEventListener("click", () => goToPage(1));
    chkBxHorsLigne.addEventListener("click", () => goToPage(1));
  }
});