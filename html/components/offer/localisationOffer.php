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
  <form id="localisationOffer" action="enregOffer.php" method="post">
    <section class="map">
      <section class="sectionParent">
        <section class="sectionLoca">
          <div>
            <label for="adresse2">Adresse postale* :</label>
            <input type="text" id="adresse2" name="adresse2" placeholder="Adresse" value="<?php echo $adresse ?>" required/>
          </div>
          <section>
            <div class="codeP">
              <label for="codepostal">Code postal* :</label>
              <input type="text" id="codepostal" name="codepostal" placeholder="Code Postal" value="<?php echo $codePostal ?>" required/>
            </div>
            <div class="villeL">
              <label for="ville2">Ville* :</label>
              <input type="text" id="ville2" name="ville2" placeholder="Ville" value="<?php echo $ville ?>" required/>
            </div>
          </section>
        </section>
        <button type="button" id="checkAddressBtn">Vérifier l'adresse</button>
      </section>
      <!-- Carte Google Maps -->
      <div id="map"></div>

    </section>
      
    <script>
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
  src="https://maps.googleapis.com/maps/api/js?key=&callback=initMap"
  async defer
></script>
