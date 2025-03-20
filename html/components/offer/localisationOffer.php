<?php
// Requête de récupération des données avec l'id de l'offre
$stmt = $conn->prepare("SELECT * FROM pact._localisation WHERE idoffre = ?");
$stmt->execute([$idOffre]);
$localisation = $stmt->fetch(PDO::FETCH_ASSOC);

if (isset($localisation["codepostal"])) {
  $adresse = $localisation["numerorue"] . " " . $localisation["rue"];
  $codePostal = $localisation["codepostal"];
  $ville = $localisation["ville"];
} else {
  $adresse = "";
  $codePostal = "";
  $ville = "";
}
?>
<!-- Construction du formulaire -->
<form id="localisationOffer" action="enregOffer.php" method="post">
  <section class="map">
    <section class="sectionParent">
      <section class="sectionLoca">
        <div>
          <label for="adresse2">Adresse postale* :</label>
          <input type="text" id="adresse2" name="adresse2" placeholder="Adresse" value="<?php echo $adresse ?>" required />
          <span id="msgAdresse" class="msgError"></span>
        </div>
        <section>
          <div class="codeP">
            <label for="codepostal">Code postal* :</label>
            <input type="text" id="codepostal" name="codepostal" placeholder="Code Postal" value="<?php echo $codePostal ?>" required />
            <span id="msgCodePostal" class="msgError"></span>
          </div>
          <div class="villeL">
            <label for="ville2">Ville* :</label>
            <input type="text" id="ville2" name="ville2" placeholder="Ville" value="<?php echo $ville ?>" required />
            <span id="msgVille" class="msgError"></span>
          </div>
        </section>
      </section>
      <button type="button" id="checkAddressBtn">Vérifier l'adresse</button>
    </section>
    <!-- Carte Google Maps -->
    <div id="map"></div>

  </section>

  <!-- Leaflet CSS & JS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <script type="module">
    import {
      geocode
    } from './js/geocode.js';
    // Vérificationdes champs conforme
    const inputAdresse = document.querySelector("#adresse2");
    const inputCodePostal = document.querySelector("#codepostal");
    const inputVille = document.querySelector("#ville2");

    const msgAdresse = document.querySelector("#msgAdresse");
    const msgCodePostal = document.querySelector("#msgCodePostal");
    const msgVille = document.querySelector("#msgVille");

    // Vérificationdes champs conforme
    inputAdresse.addEventListener("focus", () => {
      msgAdresse.textContent = "";
      inputAdresse.classList.remove("inputErreur");
    });
    inputCodePostal.addEventListener("focus", () => {
      msgCodePostal.textContent = "";
      inputCodePostal.classList.remove("inputErreur");
    });
    inputVille.addEventListener("focus", () => {
      msgVille.textContent = "";
      inputVille.classList.remove("inputErreur");
    });
    inputAdresse.addEventListener("blur", checkAdresse);
    inputCodePostal.addEventListener("blur", checkCodePostal);
    inputVille.addEventListener("blur", checkVille);

    /**
     * Vérifie si les input sont conforme pour être enregistrer
     * @returns {boolean} - Renvoie true si tous les input sont conformes aux données. False sinon
     */
    function checkOfferValidity(event) {
      // test adresse
      let adresse = checkAdresse();
      // test code postal
      let code_postal = checkCodePostal();
      // test ville
      let ville = checkVille();
      return adresse && code_postal && ville;
    }

    /**
     * Vérifie si l'adresse mail est correcte
     * @returns {boolean} - Renvoie true si l'input est conforme. False sinon.
     */
    function checkAdresse() {
      let res = true;
      const adressePattern = /^\d+\s[A-Za-zÀ-ÿ]+(?:\s[A-z][a-zÀ-ÿ]+)*$/g;
      if (!adressePattern.test(inputAdresse.value.trim())) {
        msgAdresse.textContent =
          "Adresse incorrecte. Exemple 123 Rue de la Liberté";
        res = false;
        inputAdresse.classList.add("inputErreur");
      }
      return res;
    }

    function checkCodePostal() {
      let res = true;
      const codePostalPattern = /^\d{5}$/g;
      if (!codePostalPattern.test(inputCodePostal.value.trim())) {
        msgCodePostal.textContent =
          "Ex : 22300";
        res = false;
        inputCodePostal.classList.add("inputErreur");
      }
      return res;
    }

    function checkVille() {
      let res = true;
      const villePattern = /^[a-zA-ZÀ-ÿ '-]+$/g;
      if (!villePattern.test(inputVille.value.trim())) {
        msgVille.textContent =
          "Nom de ville incorrecte. Exemple Lannion.<br> Pas d'accent, ni caractère spécial";
        res = false;
        inputVille.classList.add("inputErreur");
      }
      return res;
    }

    // Carte dynamique avec google map
    let map;
    let geocoder;
    let marker; // Variable pour stocker le marqueur actuel

    // Initialisation de la carte Google
    map = L.map('map').setView([48.46, -2.85], 10);
    L.tileLayer('/components/proxy.php?z={z}&x={x}&y={y}', {
      maxZoom: 22
    }).addTo(map);

    // Fonction pour vérifier que tous les champs sont remplis
    function checkInputsAndGeocode() {
      const ville = document.getElementById("ville2").value.trim();
      const codepostal = document.getElementById("codepostal").value.trim();
      const adresse = document.getElementById("adresse2").value.trim();

      // Vérification que les champs ne sont pas vides
      if (!ville && !codepostal && !adresse) {
        alert("Veuillez remplir tous les champs : Ville, Code postal et Adresse.");
      } else {
        geocode(address)
          .then(location => {
            if (location) {
              map.setView(location, 13);
              L.marker(location).addTo(map);
            }
          })
          .catch(error => {
            console.error("Erreur lors de la géocodification : ", error);
          });
      }
    }
  </script>