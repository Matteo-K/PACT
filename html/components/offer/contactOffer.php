<?php 
  // Requête de récupération des données avec l'id de l'offre
  $stmt = $conn->prepare("SELECT mail, telephone, affiche, urlsite FROM pact._offre WHERE idoffre= ?");
  $stmt->execute([$idOffre]);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($result === false) {
    $mail = "";
    $phone = "";
    $displayNumber = true;
    $linkWeb = "https://";
  } else {
    $mail = $result["mail"];
    $phone = $result["telephone"] == null ? "" : $result["telephone"];
    $displayNumber = $result["affiche"] == null ? true : $result["affiche"];
    $linkWeb = $result["urlsite"] == null ? "https://" : $result["urlsite"];
  }
?>
<!-- Construction du formulaire -->
<form id="contactOffer" action="enregOffer.php" method="post">
  <label for="mail" class="labelTitre">Adresse mail de contact pour votre offre*</label>
  <input type="email" name="mail" id="mail" required="required" placeholder="Adresse mail - (exemple@mail.com)" value="<?php echo $mail; ?>">
  <span id="msgEmail" class="msgError"></span>
  <label for="phone" class="labelTitre">Numéro de fixe&nbsp;:&nbsp;</label>
  <div>
    <input type="tel" name="phone" id="phone" placeholder="Numéro fixe" 
    value="<?php echo $phone; ?>"> <span id="msgTel" class="msgError"></span>
  </div>
  <div id="acceptPhone">
    <h4 class="labelTitre">Consentez vous à afficher votre numéro de portable sur l’offre &nbsp;?&nbsp;</h4>
    <div>
      <label for="Oui">
        <input type="radio" name="DisplayNumber" id="Oui" value="Oui" <?php echo $displayNumber?"checked":""?>>
        <span class="checkmark"></span>
        Oui
      </label>
    </div>
    <div>
      <label for="Non">
        <input type="radio" name="DisplayNumber" id="Non" value="Non" <?php echo !$displayNumber?"checked":""?>>
        <span class="checkmark"></span>
        Non
      </label>
    </div>
  </div>
  <label class="labelTitre" for="webSide">Site web</label>
  <label class="labelSousTitre" for="webSide">Si vous avez un site web pour votre offre, vous pouvez insérer son lien ici pour qu’il apparaîsse sur l’offre</label>
  <input type="text" name="webSide" id="webSide" 
  placeholder="Lien vers votre site web" value="<?php echo $linkWeb; ?>">

  <span id="msgWeb" class="msgError"></span>

  <script>

    const inputEmail = document.querySelector("#mail");
    const inputTel = document.querySelector("#phone");
    const inputUrl = document.querySelector("#webSide");

    const msgEmail = document.querySelector("#msgEmail");
    const msgTel = document.querySelector("#msgTel");
    const msgUrl = document.querySelector("#msgWeb");

    // Vérificationdes champs conforme
    inputEmail.addEventListener("focus", () => {
      msgEmail.textContent = "";
      inputEmail.classList.remove("inputErreur");
    });
    inputTel.addEventListener("focus", () => {
      msgTel.textContent = "";
      inputTel.classList.remove("inputErreur");
    });
    inputUrl.addEventListener("focus", () => {
      msgUrl.textContent = "";
      inputUrl.classList.remove("inputErreur");
    });
    inputEmail.addEventListener("blur", checkEmail);
    inputTel.addEventListener("blur", checkPhoneNumber);
    inputUrl.addEventListener("blur", checkUrlWeb);

    /**
     * Vérifie si les input sont conforme pour être enregistrer
     * @returns {boolean} - Renvoie true si tous les input sont conformes aux données. False sinon
     */
    function checkOfferValidity(event) {
      // test email
      let email = checkEmail();
      // test téléphone
      let phone = checkPhoneNumber();
      // test site web
      let url = checkUrlWeb();
      return email && phone && url;
    }

    /**
     * Vérifie si l'adresse mail est correcte
     * @returns {boolean} - Renvoie true si l'input est conforme. False sinon.
     */
    function checkEmail() {
      let res = true;
      const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/g;
      if (!emailPattern.test(inputEmail.value.trim())) {
        msgEmail.textContent = 
            "Email incorrecte. Exemple ewen@jain-etudiants.univ-rennes1.com";
        res = false;
        inputEmail.classList.add("inputErreur");
      }
      return res;
    }

    /**
     * Vérifie si le numéro de téléphone est correcte
     * @returns {boolean} - Renvoie true si l'input est conforme. False sinon.
     */
    function checkPhoneNumber() {
      let res = true;
      const phonePattern = /^(?:(?:\+33[0-9]{9})|(?:0[1-9][0-9]{8})|(?:[0-9]{10})|(?:[0-9]{2}[\s./]?[0-9]{2}[\s./]?[0-9]{2}[\s./]?[0-9]{2}[\s./]?[0-9]{2}))$/g;
      // Vérifie si quelque chose est entré dans le champs
      if (inputTel.value.trim() != "") {
        if (!phonePattern.test(inputTel.value.trim())) {
          msgTel.textContent =
              "Numéro de téléphone incorrecte. Exemple 07.28.39.17.28 ou +33123456789";
          res = false;
          inputTel.classList.add("inputErreur");
        }
      }
      return res;
    }

    /**
     * Vérifie si le lien web est correcte
     * @returns {boolean} - Renvoie true si l'input est conforme. False sinon.
     */
    function checkUrlWeb() {
      let res = true;
      const urlPattern = /^(https?:\/\/)?(www\.)?([a-zA-Z0-9-]+(\.[a-zA-Z]{2,})+)(\/[^\s]*)?$/g;
      // Vérifie si quelque chose est entré dans le champs
      if (!(inputUrl.value.trim() == "" || inputUrl.value.trim() == "https://")) {
        if (!urlPattern.test(inputUrl.value.trim())) {
          msgUrl.textContent =
              "Site web incorrecte. Exemple https://www.creperie-le-dundee.fr";
          res = false;
          inputUrl.classList.add("inputErreur");
        }
      }
      return res;
    }
  </script>