const formHeader = document.querySelector("header form");
const searchBar = document.querySelector("header input");

// Modifie le lien vers la recherche a chaque entré
searchBar.addEventListener("input", (e) => {
  formHeader.setAttribute("action", "search.php?search=" + e.target.value);
});

//Page DetailsOffer
try {
  let compteurImages = 0;
  document
    .getElementById("photos")
    .addEventListener("change", function (event) {
      const files = event.target.files;
      const previewContainer = document.getElementById("afficheImages");

      Array.from(files).forEach((file) => {
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
              }
            });
          }
        };
        reader.readAsDataURL(file);
      });
    });
} catch (error) {}

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
