const formHeader = document.querySelector("header form");
const searchBar = document.querySelector("header input");

// Modifie le lien vers la recherche a chaque entré
searchBar.addEventListener("input", (e) => {
  formHeader.setAttribute("action", "search.php?search=" + e.target.value);
});

//Page DetailsOffer by EWEN 
try {
  //Affichage des images a leur selection
  let compteurImages = 0;
  const pImage = document.querySelector('#choixImage > p');
  document.getElementById("photos").addEventListener("change", afficheImage(event));
  
  function afficheImage(event){
      const images = event.target.files;
      const conteneur = document.getElementById("afficheImages");

      Array.from(images).forEach((file) => {
        const reader = new FileReader();
        reader.onload = function (e) {
          if (compteurImages < 10) {
            compteurImages++;
            const imgDiv = document.createElement("figure");
            imgDiv.classList.add("imageOffre");
            imgDiv.innerHTML = `<img src="${e.target.result}" alt="Photo sélectionnée" title="Photo selectionnée">`;
            previewContainer.appendChild(imgDiv);

            imgDiv.querySelector("img").addEventListener("click", function () {
              if (confirm("Voulez-vous vraiment supprimer cette image ?")) {
                compteurImages--;
                imgDiv.remove(); // Supprime l'élément image et son conteneur
                pTag.style.color="black"; //on remet la couleur pas defaut au cas où c'etait en rouge
              }
            });
          }
          else{
            pImage.style.color="red"; //On met le txte en rouge pour signaler que la limite des 10 images est atteinte
          }
        };
        reader.readAsDataURL(file);
      });
    };


  //Affichage des tags a leur ajout
  const inputTag = document.getElementById('inputTag');
  const buttonTag = document.getElementById('ajoutTag');
  const sectionTag = document.getElementById('sectionTag');
  const pTag = document.querySelector('#sectionTag + p');
  let tags = []; // Tableau pour stocker les tags

  // Fonction pour ajouter un tag
  buttonTag.addEventListener('click', ajoutTag()) 
  
  function ajoutTag(){
      const valeurTag = inputTag.value.trim(); // Récupère la valeur de l'input et enlève les espaces

      if (valeurTag && !tags.includes(valeurTag) && tags.length < 6) {
          tags.push(valeurTag); // Ajoute le tag au tableau

          // Crée l'élément pour afficher le tag
          const elementTag = document.createElement('span');
          elementTag.classList.add('tag');
          elementTag.textContent = valeurTag;

          // Ajoute un événement pour supprimer le tag au clic
          elementTag.addEventListener('click', removeTag(valeurTag, elementTag));

          sectionTag.appendChild(elementTag); // Ajoute l'élément à la section

          inputTag.value = ''; // Réinitialise l'input
      } else if (tags.length >= 6) {
        pTag.style.color="red"; //On met le txte en rouge pour signaler que la limite des 6 tags est atteinte
      }
  };

  // Fonction pour supprimer un tag
  function removeTag(valeurTag, elementTag) {
    pTag.style.color="black"; //on remet la couleur par defaut au cas où c'etait en rouge
    tags = tags.filter(tag => tag !== valeurTag); // Supprime le tag du tableau
    sectionTag.removeChild(elementTag); // Supprime l'élément visuel correspondant au tag
    tags.remove(elementTag);
  }
} catch (error) {}

/* Affichage pour un type d'offre particulier */


    document.addEventListener('DOMContentLoaded', function() {
        // Sélection des éléments du formulaire et des radios
        const radioRestaurant = document.getElementById('radioRestaurant');
        const autresCategories = [
            document.getElementById('radioParc'),
            document.getElementById('radioActivite'),
            document.getElementById('radioSpectacle'),
            document.getElementById('radioVisite')
        ];
        
        const specialOffer = document.getElementById('specialOffer'); // Div contenant les requires_once

        // Fonction pour afficher ou masquer la div des require_once
        function toggleSpecialOffer() {
            if (radioRestaurant.checked) {
                // Si "Restaurant" est sélectionné, on cache le contenu des détails
                specialOffer.style.display = 'none';
            } else {
                // Sinon, on affiche les autres détails
                specialOffer.style.display = 'block';
            }
        }

        // Associe la fonction de toggle au clic sur tous les boutons radio
        radioRestaurant.addEventListener('click', toggleSpecialOffer);
        autresCategories.forEach(radio => radio.addEventListener('click', toggleSpecialOffer));

        // Appel initial de la fonction pour vérifier l'état initial
        toggleSpecialOffer();
    });







// Code pour envoyer les images au serveur
// const formData = new FormData();
// Array.from(files).forEach(file => {
//     formData.append('images[]', file);
// });
// fetch('YOUR_SERVER_URL', {
//     method: 'POST',
//     body: formData
// }).then(response => {
//     console.log('Images envoyées avec succès!');
// }).catch(error => {
//     console.error('Erreur lors de l\'envoi:', error);
// });

/* Intéraction horaire */
let counterRep = 1;
let date_ = new Date();
let current_date = String(
  date_.getUTCFullYear() +
    "-" +
    (date_.getUTCMonth() + 1) +
    "-" +
    date_.getUTCDate()
);
/**
 * @brief Ajout d'un div représentation à chaque click du bouton ajouter bloc
 */
function addDateRep() {
  counterRep++;
  const dateContainer = document.getElementById("Representation");

  // Création d'un nouveau bloc
  const newBlock = document.createElement("div");
  newBlock.innerHTML = `
        <input type="date" name="dateRepN${counterRep}" id="dateRepresentation" value="${current_date}" min="${current_date}">
        <span class="hourly1">
            <label for="HRepN${counterRep}_part1.1">Représentation de</label>
            <input type="time" name="HRepN${counterRep}_part1.1" id="HRepN${counterRep}_part1.1">
            <label for="HRepN${counterRep}_part1.2">à</label>
            <input type="time" name="HRepN${counterRep}_part1.2" id="HRepN${counterRep}_part1.2">
        </span>
        <input type="button" value="Retirer" name="btnRetirerRepN${counterRep}" id="btnRetirerRepN${counterRep}" class="blueBtnOffer" onclick="removeDateRep(this)">
    `;

  // Ajout du nouveau bloc au bloc de représentation
  dateContainer.appendChild(newBlock);
}

/**
 * @brief Retire le bloc Représentation
 */
function removeDateRep(button) {
  const rep = button.parentElement;
  rep.remove();
}

function toggleInputs(checkbox) {
  const timeInputs = checkbox.parentNode.querySelectorAll('input[type="time"]');
  const buttons = checkbox.parentNode.querySelectorAll("input[type='button']");
  if (checkbox.checked) {
    // Désactiver les boutons et inputs time
    buttons.forEach((button) => {
      button.disabled = true;
      button.classList.add("btnDisabledHourly");
    });
    timeInputs.forEach((input) => {
      input.disabled = true;
      input.value = ""; // Réinitialiser le contenu des inputs time
    });
  } else {
    // Réactiver les boutons et inputs time
    buttons.forEach((button) => {
      button.disabled = false;
      button.classList.remove("btnDisabledHourly");
    });
    timeInputs.forEach((input) => (input.disabled = false));
  }
}

try {
  const btnsAddHourly = document.querySelectorAll(".btnAddOffer");
  const btnsRmHourly = document.querySelectorAll(".btnRmOffer");

  /**
   * @brief Ajoute une horaire à la journée
   */
  btnsAddHourly.forEach((button) => {
    button.addEventListener("click", () => {
      let nextSpan = button.nextElementSibling;
      let nextBtn = nextSpan.nextElementSibling;
      nextSpan.classList.remove("hourlyHide");
      nextBtn.classList.remove("hourlyHide");
      button.classList.add("hourlyHide");
    });
  });

  /**
   * @brief Retire une horaire à la journée
   */
  btnsRmHourly.forEach((button) => {
    button.addEventListener("click", () => {
      let span = button.previousElementSibling;
      span.querySelectorAll("input").forEach((input) => {
        input.value = "";
        console.log(input);
      });
      span.classList.add("hourlyHide");
      button.classList.add("hourlyHide");
      span.previousElementSibling.classList.remove("hourlyHide");
    });
  });
} catch (error) {}
