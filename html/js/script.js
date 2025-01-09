//Page DetailsOffer by EWEN
try {

  document.addEventListener("DOMContentLoaded", () => {
      
    // Variables de sélection des éléments
    const inputTag = document.getElementById("inputTag");
    const autocompleteList = document.getElementById("autocompletion");
    
    // Fonction pour filtrer et afficher les suggestions
    function updateSuggestions(val) {

      // Nettoyer les suggestions précédentes
      autocompleteList.innerText = "";  
      autocompleteList.style.display = "block";    

      //On remplace les caractères accentués par leur version sans accents
      let texte = supprAccents(val.toLowerCase());
      
      // Filtrer les tags correspondant à la saisie
      suggestions = listeTags.filter(tag =>
        supprAccents(tag.toLowerCase()).includes(texte.toLowerCase())
      );

      // Ajouter les suggestions dans la liste
      suggestions.forEach(tag => {
        const itemAutoComplete = document.createElement("li");
        itemAutoComplete.textContent = tag;
        
        // Quand un utilisateur clique sur une suggestion
        itemAutoComplete.addEventListener("click", () => {
          ajoutTag(tag);
          autocompleteList.innerText = ""; // Vide les suggestions
          autocompleteList.style.display = "none"; 
        });
        
        autocompleteList.appendChild(itemAutoComplete);
      });
    }
    
    // On detecte chaque saisie de caractère dans l'input
    inputTag.addEventListener("input", (event) => {
      updateSuggestions(event.target.value);
    });
    
    // On detecte le focus de l'input
    inputTag.addEventListener("focus", () => {
      updateSuggestions(inputTag.value); 
    });
    
    // Cacher les suggestions si on clique ailleurs
    document.addEventListener("click", (event) => {
      if (!event.target.closest("#autocompletion") && event.target !== inputTag) {
        autocompleteList.innerText = "";
        autocompleteList.style.display = "none"; 
      }
    });

    function supprAccents(txt) {
      return txt.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
    }
    
  });
    
  } catch (error) { }


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
} catch (error) { }


// Js détails Park 

try {
  //Affichage des images a leur selection
  let compteurImages2 = 0;
  const pImage = document.querySelector("#choixImage2 > p");
  document
    .getElementById("ajoutPhoto2")
    .addEventListener("change", afficheImage);

  function afficheImage(event) {
    const images = event.target.files;
    const conteneur = document.getElementById("afficheImages2");

    Array.from(images).forEach((file) => {
      const reader = new FileReader();
      reader.onload = function (e) {
        if (compteurImages2 < 5) {
          compteurImages2++;
          const figureImg = document.createElement("figure");
          figureImg.classList.add("imageOffre");
          figureImg.innerHTML = `<img src="${e.target.result}" alt="Photo sélectionnée" title="Cliquez pour supprimer">`;
          conteneur.appendChild(figureImg);

          figureImg.addEventListener("click", function () {
            if (confirm("Voulez-vous vraiment supprimer cette image ?")) {
              compteurImages2--;
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
  }
} catch (error) { }
