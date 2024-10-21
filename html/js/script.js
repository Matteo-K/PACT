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
try {
  const btnsAddHourly = document.querySelectorAll(".btnAddOffer");
  const btnsRmHourly = document.querySelectorAll(".btnRmOffer");
  const chckbxClose = document.querySelectorAll(
    "#hourlyOffer input[type='checkbox']"
  );

  btnsAddHourly.forEach((button) => {
    button.addEventListener("click", () => {
      let nextSpan = button.nextElementSibling;
      let nextBtn = nextSpan.nextElementSibling;
      nextSpan.classList.remove("hourlyHide");
      nextBtn.classList.remove("hourlyHide");
      button.classList.add("hourlyHide");
    });
  });

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

  function toggleInputs(checkbox) {
    const timeInputs =
      checkbox.parentNode.querySelectorAll('input[type="time"]');
    const buttons = checkbox.parentNode.querySelectorAll(
      "input[type='button']"
    );
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
} catch (error) {}
