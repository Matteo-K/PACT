 <?php
 
 // Requête de récupération des données avec l'id de l'offre
  $stmt = $conn->prepare("SELECT * FROM pact._localisation WHERE idoffre = ?");
  $stmt->execute([$idOffre]);
  $localisation = $stmt->fetch(PDO::FETCH_ASSOC);

  if (isset($localisation["codepostal"])) {
    $adresse = $localisation["numerorue"] . " ". $localisation["rue"];
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
            <input type="text" id="adresse2" name="adresse2" placeholder="Adresse" value="<?php echo $adresse ?>" required/>
            <span id="msgAdresse" class="msgError"></span>
          </div>
          <section>
            <div class="codeP">
              <label for="codepostal">Code postal* :</label>
              <input type="text" id="codepostal" name="codepostal" placeholder="Code Postal" value="<?php echo $codePostal ?>" required/>
              <span id="msgCodePostal" class="msgError"></span>
            </div>
            <div class="villeL">
              <label for="ville2">Ville* :</label>
              <input type="text" id="ville2" name="ville2" placeholder="Ville" value="<?php echo $ville ?>" required/>
              <span id="msgVille" class="msgError"></span>
            </div>
          </section>
        </section>
        <button type="button" id="checkAddressBtn">Vérifier l'adresse</button>
      </section>
      <!-- Carte Google Maps -->
      <div id="map"></div>

    </section>
      
    <script>
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
              "Nom de ville incorrecte. Exemple Lannion";
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
function initMap() {
    map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: 48.8566, lng: 2.3522 }, // Paris comme point de départ
        zoom: 8,
    });
    geocoder = new google.maps.Geocoder();

    // Détecte l'appui sur la touche 'Enter' dans les champs de texte
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault(); // Empêche le comportement par défaut d'envoi du formulaire
            checkInputsAndGeocode();
        }
    });

    // Ajout d'un écouteur d'événement pour le bouton "Vérifier l'adresse"
    document.getElementById("checkAddressBtn").addEventListener("click", function() {
        checkInputsAndGeocode();
    });
}

// Fonction pour vérifier que tous les champs sont remplis
function checkInputsAndGeocode() {
    const ville = document.getElementById("ville2").value.trim();
    const codepostal = document.getElementById("codepostal").value.trim();
    const adresse = document.getElementById("adresse2").value.trim();

    // Vérification que les champs ne sont pas vides
    if (!ville && !codepostal && !adresse) {
        alert("Veuillez remplir tous les champs : Ville, Code postal et Adresse.");
    } else {
        geocodeadresse(`${adresse}, ${codepostal}, ${ville}`);
    }
}

// Fonction pour géocoder l'adresse
function geocodeadresse(fulladresse) {
    geocoder.geocode({ 'address': fulladresse }, function(results, status) {
        if (status === 'OK' && results[0]) {
            // Supprimer l'ancien marqueur s'il existe
            if (marker) {
                marker.setMap(null);
            }

            // Centrer la carte sur la nouvelle localisation et appliquer un zoom
            map.setCenter(results[0].geometry.location);
            map.setZoom(15); // Zoom sur la nouvelle adresse

            // Ajouter un nouveau marqueur sur la carte
            marker = new google.maps.Marker({
                map: map,
                position: results[0].geometry.location
            });
        } else {
            // Alerter si le géocodage échoue ou si aucun résultat n'est trouvé
            alert('Adresse introuvable. Veuillez vérifier l\'adresse, le code postal ou la ville.');AIzaSyDYU5lrDiXzchFgSAijLbonudgJaCfXrRE
        }
    });
}
</script>
<!-- Inclure l'API Google Maps avec votre clé API -->
<script
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDYU5lrDiXzchFgSAijLbonudgJaCfXrRE&callback=initMap"
  async defer
></script>
