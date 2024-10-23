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
  const pImage = document.querySelector("#choixImage > p");
  document.querySelectorAll("#afficheImages + label")[0].addEventListener("change", afficheImage);

  function afficheImage(event) {
    const images = event.target.files;
    const conteneur = document.getElementById("afficheImages");
    const pImage = document.querySelector("#choixImage > p");

      Array.from(images).forEach((file) => {
        const reader = new FileReader();
        reader.onload = function (e) {
          if (compteurImages < 10) {
            compteurImages++;
            const figureImg = document.createElement("figure");
            figureImg.classList.add("imageOffre");
            figureImg.innerHTML = `<img src="${e.target.result}" alt="Photo sélectionnée" title="Cliquez pour supprimer">`;
            conteneur.appendChild(figureImg);

            figureImg.addEventListener("click", function () {
              if (confirm("Voulez-vous vraiment supprimer cette image ?")) {
                compteurImages--;
                figureImg.remove(); // Supprime l'élément image et son conteneur
                pImage.style.color = "black"; //on remet la couleur par défaut au cas où c'etait en rouge
              }
            });
          } else {
            pImage.style.color = "red"; //On met le txte en rouge pour signaler que la limite des 10 images est atteinte
          }
        };
        reader.readAsDataURL(file);
      });

    //Affichage des tags a leur ajout
    const inputTag = document.getElementById("inputTag");
    const buttonTag = document.getElementById("ajoutTag");
    const sectionTag = document.getElementById("sectionTag");
    const pTag = document.querySelector("#sectionTag + p");
    let tags = []; // Tableau pour stocker les tags

    // Fonction pour ajouter un tag
    buttonTag.addEventListener("click", ajoutTag);
    inputTag.addEventListener("keypress", ajoutTagKeyboard);
    let compteurTags = 0;

    //detection d'un appui sur entrée
    function ajoutTagKeyboard(e) {
      if (e.code == "Enter") {
        ajoutTag();
      }
    }

    function ajoutTag() {

      const valeurTag = inputTag.value.trim(); // Récupère la valeur de l'input et enlève les espaces

      if (valeurTag && !tags.includes(valeurTag) && compteurTags < 6) {
        compteurTags++;
        tags.push(valeurTag); // Ajoute le tag au tableau

        // Crée l'élément pour afficher le tag
        const elementTag = document.createElement("span");
        elementTag.classList.add("tag");
        elementTag.textContent = valeurTag;

        // Ajoute un événement pour supprimer le tag au clic
        elementTag.addEventListener("click", function () {
          tags.splice(tags.indexOf(valeurTag), 1); // Supprime un élément à l'index trouvé
          sectionTag.removeChild(elementTag); // Supprime l'élément visuel correspondant au tag
          pTag.style.color = "black"; //on remet la couleur par defaut au cas où c'etait en rouge
          compteurTags--;
        });

        sectionTag.appendChild(elementTag); // Ajoute l'élément à la section

        inputTag.value = ""; // Réinitialise l'input
      } 
      else if (tags.length >= 6) {
        pTag.style.color = "red"; //On met le txte en rouge pour signaler que la limite des 6 tags est atteinte
      } 
      else if (tags.includes(valeurTag)) {
        alert("Ce tag à déjà été entré !");
      }
    }
  }
} catch (error) {}

try {
  /* Affichage pour un type d'offre particulier */
  // Sélection des éléments du formulaire et des radios
  const radioRestaurant = document.getElementById("radioRestaurant");
  const radioPark = document.getElementById("radioParc");
  const radioActivite = document.getElementById("radioActivite");
  const radioSpectacle = document.getElementById("radioSpectacle");
  const radioVisite = document.getElementById("radioVisite");

  const ParkOffer = document.getElementById("park");
  const ActiviteOffer = document.getElementById("activity");
  const SpectacleOffer = document.getElementById("show");
  const VisiteOffer = document.getElementById("visit");

  function hidenOffer() {
    ParkOffer.style.display = "none";
    ActiviteOffer.style.display = "none";
    SpectacleOffer.style.display = "none";
    VisiteOffer.style.display = "none";
  }

  // Fonction pour afficher ou masquer la div des require_once
  hidenOffer();
  function toggleSpecialOffer() {
    hidenOffer();
    if (radioPark.checked) {
      ParkOffer.style.display = "block";
    } else if (radioActivite.checked) {
      ActiviteOffer.style.display = "block";
    } else if (radioSpectacle.checked) {
      SpectacleOffer.style.display = "block";
    } else if (radioVisite.checked) {
      VisiteOffer.style.display = "block";
    }
  }

  // Associe la fonction de toggle au clic sur tous les boutons radio
  radioRestaurant.addEventListener("input", toggleSpecialOffer);
  radioPark.addEventListener("input", toggleSpecialOffer);
  radioActivite.addEventListener("input", toggleSpecialOffer);
  radioSpectacle.addEventListener("input", toggleSpecialOffer);
  radioVisite.addEventListener("input", toggleSpecialOffer);

  // autresCategories.forEach(radio => radio.addEventListener('click', toggleSpecialOffer));

  // Appel initial de la fonction pour vérifier l'état initial
  toggleSpecialOffer();
} catch (error) {}

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

/* gestion des payments */

try {
  const radBtnCB = document.querySelector("#carte_bancaire");
  const radBtnVB = document.querySelector("#virement_bancaire");
  const radBtnPaypal = document.querySelector("#paypal");

  const Form_CB = document.getElementById("Form_CB");
  const Form_VB = document.getElementById("Form_VB");
  const Form_paypal = document.getElementById("Form_paypal");

  /**
   * @brief set-up tout les formulaires des moyens de payment
   */
  function updateForms() {
    hideCB();

    // Afficher le formulaire correspondant au bouton radio sélectionné
    if (radBtnCB.checked) {
      Form_CB.classList.remove("payment_hide");
    } else if (radBtnVB.checked) {
      Form_VB.classList.remove("payment_hide");
    } else if (radBtnPaypal.checked) {
      Form_paypal.classList.remove("payment_hide");
    }
  }

  function hideCB() {
    Form_CB.classList.add("payment_hide");
    Form_VB.classList.add("payment_hide");
    Form_paypal.classList.add("payment_hide");
  }
  radBtnCB.addEventListener("input", updateForms);
  radBtnVB.addEventListener("input", updateForms);
  radBtnPaypal.addEventListener("input", updateForms);

  updateForms();
} catch (error) {}
