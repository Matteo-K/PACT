/**
 * @author Ewen JAIN
 * adaptabilité Mattéo KERVADEC
 */

// Variable global
let listElements = [];
// max 6
/**
 * Génère un nouvelle élément dans une zone d'éléments avec un input caché pour le post
 * @param {String} valeurElement Valeur de l'élément à indiquer sur l'élément générer
 * @param {document} input Input de saisie de champ qui contient la valeur de l'élément
 * @param {document} zoneElement Zone final de l'élément généré
 * @param {document} msgErreur Zone de message d'erreur pour le retour utilisateur
 * @param {String} nomPost nom de l'élément pour le retrouvé dans le POST ex: tags[]
 * @param {Integer} nbMaxElements limite maximum d'éléments de la zone
 * @param {String} typeElement le type de balide utilisé pour l'élément 
 * @param {Array} classElement liste de class à rajouter sur l'éléments
 * @param {Integer} indiceListElem indice de listElements qui correspond à la liste d'élément traité
 */
function ajoutElement(valeurElement, input, zoneElement, msgErreur, nomPost, nbMaxElements, typeElement, classElement, indiceListElem) {

  if (valeurElement && !listElements[indiceListElem].includes(valeurElement) && listElements[indiceListElem].length < nbMaxElements) {

    listElements[indiceListElem].push(valeurElement); // Ajoute le tag dans le tableau

    // Crée l'élément visuel pour afficher le tag
    const elementTag = document.createElement(typeElement); // span
    classElement.forEach(element => {
      elementTag.classList.add(element); // tag
    });
    elementTag.textContent = valeurElement;

    //On créé une image pour guider l'utilisateur sur le suppression du tag
    let imgCroix = document.createElement("img");
    imgCroix.setAttribute("src", "../img/icone/croix.png");

    // Crée l'input caché pour soumettre le tag avec le formulaire
    const hiddenInputTag = document.createElement("input");
    hiddenInputTag.type = "hidden";
    hiddenInputTag.value = valeurElement;
    hiddenInputTag.name = nomPost;

    // Ajoute un événement pour supprimer le tag au clic
    elementTag.addEventListener("click", function () {
      listElements[indiceListElem].splice(
        listElements[indiceListElem].indexOf(valeurElement), 1
      ); // Retire le tag du tableau
      zoneElement.removeChild(hiddenInputTag); // Supprime l'input caché
      zoneElement.removeChild(elementTag); // Supprime l'élément visuel du tag
    });

    // Ajoute l'élément visuel et l'input caché au à la section, et l'image à l'élément visuel
    elementTag.appendChild(imgCroix);
    zoneElement.appendChild(elementTag); 
    zoneElement.appendChild(hiddenInputTag);

    // Réinitialise l'input
    input.value = "";

  // Gestion des cas d'erreur
  } else if (listElements[indiceListElem].length >= nbMaxElements) {
    msgErreur.textContent = "Vous êtes limité à " + nbMaxElements + " éléments";
  } else if (listElements[indiceListElem].includes(valeurElement)) {
    msgErreur.textContent = "l'élément " + valeurElement + " a déjà été ajouté !";
  }
}

/**
 * 
 * @param {String} val valeur de l'input de filtre
 * @param {Integer} indiceListElem indice de listElements qui correspond à la liste d'élément traité
 * @param {document} blocAutocomplete bloc contenant une liste de suggestion
 * @param {document} msgErreur bloc de message d'erreur
 * @param {Array} listeSuggestion liste des suggestions de type String
 * @param {*} nomFonction fonction enclenchés dès le click sur un suggestion
 * @param  {...any} params paramètre de la fonction
 */
function updateSuggestions(val, indiceListElem, blocAutocomplete, msgErreur, listeSuggestion, nomFonction, ...params) {

  // vide le message d'erreur
  msgErreur.textContent = "";

  // Nettoyer les suggestions précédentes
  blocAutocomplete.innerText = "";  
  blocAutocomplete.style.display = "block";    

  //On remplace les caractères accentués par leur version sans accents
  let texte = supprAccents(val.toLowerCase());
  
  // Filtrer les tags correspondant à la saisie
  let suggestions = listeSuggestion.filter(element =>
    supprAccents(element.toLowerCase()).includes(texte.toLowerCase())
  );

  // Ajouter les suggestions dans la liste
  suggestions.forEach(element => {
    const itemAutoComplete = document.createElement("li");
    itemAutoComplete.textContent = element;
    
    // Quand un utilisateur clique sur une suggestion
    itemAutoComplete.addEventListener("click", () => {
      nomFonction(element, ...params, indiceListElem);
      blocAutocomplete.innerText = ""; // Vide les suggestions
      blocAutocomplete.style.display = "none"; 
    });
    
    blocAutocomplete.appendChild(itemAutoComplete);
  });
}

/**
 * Supprime les accents (diacritiques) d'une chaîne de caractères. 
 * @param {string} txt Le texte dont les accents doivent être supprimés.
 * @returns {string} Le texte sans accents.
 */
function supprAccents(txt) {
  return txt.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
}

/**
 * Initialise l'autocomplétion
 * @param {document} input input du filtrage et sélections d'éléments
 * @param {document} blocAutocomplete bloc contenant une liste de suggestion
 * @param {document} msgErreur bloc de message d'erreur
 * @param {Array} listeSuggestion liste des suggestions de type String
 * @param {*} nomFonction fonction enclenchés dès le click sur un suggestion
 * @param  {...any} params paramètre de la fonction
 */
function createAutoCompletion(input, blocAutocomplete, msgErreur, listeSuggestion, nomFonction, ...params) {
  const indiceListElem = listElements.length;
  listElements.push([]);

  // On detecte chaque saisie de caractère dans l'input
  input.addEventListener("input", (event) => {
    updateSuggestions(event.target.value, indiceListElem, blocAutocomplete, msgErreur, listeSuggestion, nomFonction, ...params);
  });
  
  // On detecte le focus de l'input
  input.addEventListener("focus", () => {
    updateSuggestions(input.value, indiceListElem, blocAutocomplete, msgErreur, listeSuggestion, nomFonction, ...params);
  });

  // Cacher les suggestions si on clique ailleurs
  document.addEventListener("click", (event) => {
    if (!event.target.closest(blocAutocomplete) && event.target !== inputTag) {
      autocompleteList.innerText = "";
      autocompleteList.style.display = "none"; 
    }
  });
}