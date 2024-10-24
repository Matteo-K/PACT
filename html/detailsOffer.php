<?php
require_once "config.php";
$idOffre = $_GET["idoffre"] ?? null;

// Vérifiez si idoffre est défini
if (!$idOffre) {
    header("location: index.php");
    exit();
}

// Fonction pour récupérer les horaires
function getSchedules($conn, $idOffre) {
    $schedules = [
        'midi' => [],
        'soir' => []
    ];

    // Récupérer les horaires du midi et du soir
    $stmtMidi = $conn->prepare("SELECT * FROM pact._horaireMidi WHERE idOffre = :idOffre");
    $stmtMidi->bindParam(':idOffre', $idOffre, PDO::PARAM_INT);
    $stmtMidi->execute();
    $schedules['midi'] = $stmtMidi->fetchAll(PDO::FETCH_ASSOC);

    $stmtSoir = $conn->prepare("SELECT * FROM pact._horaireSoir WHERE idOffre = :idOffre");
    $stmtSoir->bindParam(':idOffre', $idOffre, PDO::PARAM_INT);
    $stmtSoir->execute();
    $schedules['soir'] = $stmtSoir->fetchAll(PDO::FETCH_ASSOC);

    return $schedules;
}

// Récupérer les horaires
$schedules = getSchedules($conn, $idOffre);

// Rechercher l'offre dans les parcs d'attractions
$stmt = $conn->prepare("SELECT * FROM pact.parcs_attractions WHERE idoffre = :idoffre");
$stmt->bindParam(':idoffre', $idOffre);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$result) {
    // Recherche dans les restaurants, activités, spectacles, et visites
    $types = ['restaurants', 'activites', 'spectacles', 'visites'];
    foreach ($types as $type) {
        $stmt = $conn->prepare("SELECT * FROM pact.$type WHERE idoffre = :idoffre");
        $stmt->bindParam(':idoffre', $idOffre);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $typeOffer = $type;
            break; // Sortir de la boucle si une offre est trouvée
        }
    }
} else{
    $typeOffer = "parcs_attractions";
}

if (!$result) {
    echo "Aucune offre trouvée avec cet id.<br>";
    exit();
}

// Récupérer les détails de localisation
$stmt = $conn->prepare("SELECT * FROM pact.localisations_offres WHERE idoffre = :idoffre");
$stmt->bindParam(':idoffre', $idOffre);
$stmt->execute();
$lieu = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch photos for the offer
$stmt = $conn->prepare("SELECT * FROM pact._illustre WHERE idoffre = :idoffre ORDER BY url ASC");
$stmt->bindParam(':idoffre', $idOffre);
$stmt->execute();
$photos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="style.css">

    <title><?php echo htmlspecialchars($result["nom_offre"]); ?></title>
</head>
<body>
    <?php require_once "components/headerTest.php"; ?>

    <main class="mainOffer">
        <h2 id="titleOffer"><?php echo htmlspecialchars($result["nom_offre"]); ?></h2>
        
        <div>
            <?php 
            // Fetch tags associated with the offer
            $stmt = $conn->prepare("
                SELECT t.nomTag FROM pact._offre o
                LEFT JOIN pact._tag_parc tp ON o.idOffre = tp.idOffre
                LEFT JOIN pact._tag_spec ts ON o.idOffre = ts.idOffre
                LEFT JOIN pact._tag_Act ta ON o.idOffre = ta.idOffre
                LEFT JOIN pact._tag_restaurant tr ON o.idOffre = tr.idOffre
                LEFT JOIN pact._tag_visite tv ON o.idOffre = tv.idOffre
                LEFT JOIN pact._tag t ON t.nomTag = COALESCE(tp.nomTag, ts.nomTag, ta.nomTag, tr.nomTag, tv.nomTag)
                WHERE o.idOffre = :idoffre
                ORDER BY o.idOffre");
            $stmt->bindParam(':idoffre', $idOffre);
            $stmt->execute();
            $tags = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($tags as $tag): ?>
                <a class="tag" href="search.php"><?php echo htmlspecialchars(ucfirst(strtolower($tag["nomtag"]))); ?></a>
            <?php endforeach; ?>
        </div>

        <div>
            <img src="./img/icone/lieu.png">
            <p id="lieu"><?php echo htmlspecialchars($lieu["numerorue"] . " " . $lieu["rue"] . ", " . $lieu["codepostal"] . " " . $lieu["ville"]); ?></p>
            <img src="./img/icone/tel.png">
            <a href="tel:<?php echo htmlspecialchars($result["telephone"]); ?>"><?php echo htmlspecialchars($result["telephone"]); ?></a>
            <img src="./img/icone/mail.png">
            <a href="mailto:<?php echo htmlspecialchars($result["mail"]); ?>"><?php echo htmlspecialchars($result["mail"]); ?></a>
            <img src="./img/icone/globe.png">
            <a href="<?php echo htmlspecialchars($result["urlsite"]); ?>"><?php echo htmlspecialchars($result["urlsite"]); ?></a>
        </div>

        <div class="swiper-container">
            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                <?php
                    foreach ($photos as $picture) {
                ?>
                        <div class="swiper-slide">
                            <img src="<?php echo $picture['url']; ?>" />
                        </div>
                <?php
                    }
                ?>
                </div>
            </div>

        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        </div>

        <div thumbsSlider="" class="swiper myThumbSlider">
            <div class="swiper-wrapper">
            <?php
                foreach ($photos as $picture) {
            ?>
                    <div class="swiper-slide">
                        <img src="<?php echo $picture['url']; ?>" />
                    </div>
            <?php
                }
            ?>
            </div>
        </div>
        
        <p>Pas de note pour this moment</p>
        <section>
            <h4>Description</h4>
            <p><?php echo htmlspecialchars($result["description"]); ?></p>
        </section>

        <section id="InfoComp">
            <h4>Informations Complémentaires</h4>
            <table>
                <thead>
                    <tr>
                        <th colspan="2">Horaires</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Tableau de tous les jours de la semaine
                    $joursSemaine = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

                    // Afficher les horaires pour chaque jour de la semaine
                    foreach ($joursSemaine as $jour): ?>
                        <tr>
                            <td class="jourSemaine"><?php echo htmlspecialchars($jour); ?></td>
                            <td>
                                <?php
                                $horaireMidi = array_filter($schedules['midi'], fn($h) => $h['jour'] === $jour);
                                $horaireSoir = array_filter($schedules['soir'], fn($h) => $h['jour'] === $jour);

                                // Collect hours
                                $horairesAffichage = [];
                                if (!empty($horaireMidi)) {
                                    $horairesAffichage[] = htmlspecialchars(current($horaireMidi)['heureouverture']) . " à " . htmlspecialchars(current($horaireMidi)['heurefermeture']);
                                } 
                                if (!empty($horaireSoir)) {
                                    $horairesAffichage[] = htmlspecialchars(current($horaireSoir)['heureouverture']) . " à " . htmlspecialchars(current($horaireSoir)['heurefermeture']);
                                }
                                if(empty($horaireMidi) && empty($horaireSoir)){
                                    $horairesAffichage[] = "Fermé";
                                }
                                echo implode(' et ', $horairesAffichage);
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
        <!-- Carte Google Maps -->
        <div id="map"></div>
    </main>

    <?php require_once "components/footer.php"; ?>
    
    <script>
        let map;
        let geocoder;
        let marker; // Variable pour stocker le marqueur actuel

        // Récupérer les informations de l'adresse depuis PHP
        const lieu = {
            numerorue: "<?php echo htmlspecialchars($lieu['numerorue']); ?>",
            rue: "<?php echo htmlspecialchars($lieu['rue']); ?>",
            codepostal: "<?php echo htmlspecialchars($lieu['codepostal']); ?>",
            ville: "<?php echo htmlspecialchars($lieu['ville']); ?>"
        };

        console.log("Adresse complète : ", "<?php echo $lieu['numerorue'] . ' ' . $lieu['rue'] . ', ' . $lieu['codepostal'] . ' ' . $lieu['ville']; ?>");
        // Initialisation de la carte Google
        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                center: { lat: 48.8566, lng: 2.3522 }, // Paris comme point de départ
                zoom: 8,
            });
            geocoder = new google.maps.Geocoder();

            // Effectuer le géocodage dès que la carte est chargée
            checkInputsAndGeocode();
        }

        function checkInputsAndGeocode() {
            const adresse = "<?php echo $lieu['numerorue'] . ' ' . $lieu['rue'] . ', ' . $lieu['codepostal'] . ' ' . $lieu['ville']; ?>";

            if (!adresse || adresse.trim() === "") {
                alert("L'adresse est manquante.");
            } else {
                geocodeadresse(adresse);
            }
        }

        function geocodeadresse(fulladresse) {
            console.log("Adresse envoyée pour géocodage : ", fulladresse);
            geocoder.geocode({ 'address': fulladresse }, function(results, status) {
                if (status === google.maps.GeocoderStatus.OK && results[0]) {
                    console.log("Résultat du géocodage : ", results[0]);

                    // Supprimer l'ancien marqueur s'il existe
                    if (marker) {
                        marker.setMap(null);
                    }

                    // Centrer la carte et placer le marqueur
                    map.setCenter(results[0].geometry.location);
                    map.setZoom(15);
                    marker = new google.maps.Marker({
                        map: map,
                        position: results[0].geometry.location
                    });
                } else {
                    console.error("Échec du géocodage : ", status, results); // Affichez plus d'informations
                    alert("Échec du géocodage : " + status + ". Vérifiez l'adresse.");
                }
            });
        }

    </script>

        <!-- Inclure l'API Google Maps avec votre clé API -->
        <script src="https://maps.googleapis.com/maps/api/js?key=&callback=initMap" async defer></script>


    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <!-- Initialize Swiper -->
    <script>
        var swiper = new Swiper(".myThumbSlider", {
            loop: true,
            spaceBetween: 10,
            slidesPerView: 4,
            freeMode: true,
            watchSlidesProgress: true,
        });
        var swiper2 = new Swiper(".mySwiper", {
            loop: true,
            autoplay: {
                delay: 5000,
            },
            spaceBetween: 10,
            navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
            },
            thumbs: {
            swiper: swiper,
            },
        });
    </script>
    <script src="js/setColor.js"></script>
</body>
</html>
