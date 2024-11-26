<?php
require_once "config.php";
require_once __DIR__ . "/../.SECURE/cleAPI.php";
$idOffre = $_GET["idoffre"] ?? null;
$ouvert = $_GET["ouvert"] ?? null;

// Vérifiez si idoffre est défini
if (!$idOffre) {
    header("location: index.php");
    exit();
}

$monOffre = new ArrayOffer($idOffre);

$stmt = $conn->prepare("SELECT * FROM pact.offres WHERE idoffre = :idoffre");
$stmt->bindParam(':idoffre', $idOffre);
$stmt->execute();
$offre = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Fonction pour récupérer les horaires
function getSchedules($conn, $idOffre)
{
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
    $types = ['restaurants' => 'restaurant', 'activites' => 'activité', 'spectacles' => 'spectacle', 'visites' => 'visite'];
    foreach ($types as $type => $key) {
        $stmt = $conn->prepare("SELECT * FROM pact.$type WHERE idoffre = :idoffre");
        $stmt->bindParam(':idoffre', $idOffre);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $typeOffer = $key;
            break; // Sortir de la boucle si une offre est trouvée
        }
    }
} else {
    $typeOffer = "parc_attraction";
}

if (!$result) {
    $stmt = $conn->prepare("SELECT * FROM pact._offre WHERE idoffre = :idoffre");
    $stmt->bindParam(':idoffre', $idOffre);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
?>
        <form id="manageOfferAuto" action="manageOffer.php" method="post">
            <input type="hidden" name="idOffre" value="<?php echo $idOffre ?>">
        </form>
        <script>
            document.getElementById("manageOfferAuto").submit();
        </script>
<?php

    }

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
    <?php require_once "components/header.php"; ?>

    <main class="mainOffer">
        <div class="buttonDetails">
            <?php
            if (($typeUser == "pro_public" || $typeUser == "pro_prive")) {
                $cook = $conn->prepare("SELECT o.idu,o.idoffre,o.nom,o.statut,o.description,o.mail,o.affiche,o.resume FROM pact._offre o WHERE idoffre=$idOffre");
                $cook->execute();
                $offre = $cook->fetchAll(PDO::FETCH_ASSOC);
                $affiche = false;
                foreach ($offre[0] as $key => $value) {
                    if ($value == NULL) {
                        $affiche = true;
                    }
                }
                if ($affiche) {
                    $resto = $conn->prepare("SELECT * FROM pact._restauration WHERE idoffre=$idOffre");
                    $resto->execute();
                    $restau = $resto->fetchAll(PDO::FETCH_ASSOC);

                    $spec = $conn->prepare("SELECT * FROM pact._spectacle WHERE idoffre=$idOffre");
                    $spec->execute();
                    $spect = $spec->fetchAll(PDO::FETCH_ASSOC);

                    $visi = $conn->prepare("SELECT * FROM pact._visite WHERE idoffre=$idOffre");
                    $visi->execute();
                    $visit = $visi->fetchAll(PDO::FETCH_ASSOC);

                    $act = $conn->prepare("SELECT * FROM pact._activite WHERE idoffre=$idOffre");
                    $act->execute();
                    $acti = $act->fetchAll(PDO::FETCH_ASSOC);

                    $parc = $conn->prepare("SELECT * FROM pact._parcattraction WHERE idoffre=$idOffre");
                    $parc->execute();
                    $parca = $parc->fetchAll(PDO::FETCH_ASSOC);

                    if ($restau) {
                        $tema = $restau;
                    } elseif ($spect) {
                        $tema = $spect;
                    } elseif ($visit) {
                        $tema = $visit;
                    } elseif ($acti) {
                        $tema = $acti;
                    } else {
                        $tema = $parca;
                    }

                    foreach ($tema[0] as $key => $value) {
                        if ($value == NULL) {
                            $affiche = true;
                        }
                    }
                    $adr = $conn->prepare("SELECT * FROM pact._localisation WHERE idoffre=$idOffre");
                    $adr->execute();
                    $loca = $adr->fetchAll(PDO::FETCH_ASSOC);

                    if (!$loca) {
                        $affiche = true;
                    }
                }
                if (!$affiche) {
                    $statutActuel = $offre[0]['statut'];
            ?>

                    <form method="post" action="changer_statut.php">
                        <!-- Envoyer l'ID de l'offre pour pouvoir changer son statut -->
                        <input type="hidden" name="offre_id" value="<?php echo $offre[0]['idoffre']; ?>">
                        <input type="hidden" name="nouveau_statut" value="<?php echo $statutActuel === 'inactif' ? 'actif' : 'inactif'; ?>">
                        <input type="hidden" name="ouvert" value="<?php echo $_GET['ouvert']; ?>">
                        <button class="modifierBut" type="submit">
                            <?php echo $statutActuel === 'inactif' ? 'Mettre en ligne' : 'Mettre hors ligne'; ?>
                        </button>
                    </form>
                <?php
                }
                ?>

                <form method="post" action="manageOffer.php">
                    <!-- Envoyer l'ID de l'offre pour pouvoir changer son statut -->
                    <input type="hidden" name="idOffre" value="<?php echo $offre[0]['idoffre']; ?>">
                    <button class="modifierBut" type="submit">
                        <?php echo "Modifier offre"; ?>
                    </button>
                </form>
            <?php
            }

            ?>
        </div>
        <h2 id="titleOffer"><?php echo htmlspecialchars($result["nom_offre"]); ?></h2>
        <h3 id="typeOffer"><?php echo str_replace("_", " ", ucfirst(strtolower($typeOffer))) ?> à <?php echo $lieu['ville']?></h3>
        <?php
        if (($typeUser == "pro_public" || $typeUser == "pro_prive")) {
        ?>
            <h3 class="DetailsStatut"><?php echo $statutActuel == 'actif' ? "En-Ligne" : "Hors-Ligne"; ?></h3>
        <?php
        }
        ?>
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

            if($typeOffer == "restaurant"){
                array_push($tags, ['nomtag' => $result['gammedeprix']]);
            }

            foreach ($tags as $tag):
                if ($tag["nomtag"] != NULL) {
            ?>
                    <a class="tag" href="search.php"><?php echo htmlspecialchars(str_replace("_"," ",ucfirst(strtolower($tag["nomtag"])))); ?></a>
                <?php }
            endforeach;

            if ($ouvert == "EstOuvert") {
                ?>
                <a class="ouvert" href="search.php">Ouvert</a>
            <?php
            } else if ($ouvert == "EstFermé") {
            ?>
                <a class="ferme" href="search.php">Fermé</a>
            <?php
            }
            ?>

        </div>

        <div id="infoPro">
            <?php
            $stmt = $conn->prepare("SELECT * FROM pact._offre WHERE idoffre ='$idOffre'");
            $stmt->execute();
            $tel = $stmt->fetch(PDO::FETCH_ASSOC);


            if ($lieu) {
            ?>
                <div>
                    <img src="./img/icone/lieu.png">
                    <a href="https://www.google.com/maps?q=<?php echo urlencode($lieu["numerorue"] . " " . $lieu["rue"] . ", " . $lieu["codepostal"] . " " . $lieu["ville"]); ?>" target="_blank" id="lieu"><?php echo htmlspecialchars($lieu["numerorue"] . " " . $lieu["rue"] . ", " . $lieu["codepostal"] . " " . $lieu["ville"]); ?></a>
                </div>

            <?php
            }
            if ($result["telephone"] && $tel["affiche"] == TRUE) {
            ?>
                <div>
                    <img src="./img/icone/tel.png">
                    <a href="tel:<?php echo htmlspecialchars($result["telephone"]); ?>"><?php echo htmlspecialchars($result["telephone"]); ?></a>
                </div>
            <?php
            }
            if ($result["mail"]) {
            ?>
                <div>
                    <img src="./img/icone/mail.png">
                    <a href="mailto:<?php echo htmlspecialchars($result["mail"]); ?>"><?php echo htmlspecialchars($result["mail"]); ?></a>
                </div>

            <?php
            }
            if ($result["urlsite"]) {
            ?>
                <div>
                    <img src="./img/icone/globe.png">
                    <a href="<?php echo htmlspecialchars($result["urlsite"]); ?>"><?php echo htmlspecialchars($result["urlsite"]); ?></a>
                </div>

            <?php
            }
            ?>

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
        <article id="descriptionOffre">
            <p>Pas de note pour le moment</p>
            <section>
                <h3>Description</h3>
                <p><?php echo htmlspecialchars($result["description"]); ?></p>
            </section>
        </article>


        <section id="infoComp">
            <h2>Informations Complémentaires</h2>
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
                                if (empty($horaireMidi) && empty($horaireSoir)) {
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
        <div id="afficheLoc">
            <div id="carte"></div>
            <div id="contact-info">
                <?php
                if ($lieu) {
                ?>
                    <div>
                        <img src="./img/icone/lieu.png">
                        <a href="https://www.google.com/maps?q=<?php echo urlencode($lieu["numerorue"] . " " . $lieu["rue"] . ", " . $lieu["codepostal"] . " " . $lieu["ville"]); ?>" target="_blank" id="lieu"><?php echo htmlspecialchars($lieu["numerorue"] . " " . $lieu["rue"] . ", " . $lieu["codepostal"] . " " . $lieu["ville"]); ?></a>
                    </div>

                <?php
                }
                if ($result["telephone"] && $tel["affiche"] == TRUE) {
                ?>
                    <div>
                        <img src="./img/icone/tel.png">
                        <a href="tel:<?php echo htmlspecialchars($result["telephone"]); ?>"><?php echo htmlspecialchars($result["telephone"]); ?></a>
                    </div>
                <?php
                }
                if ($result["mail"]) {
                ?>
                    <div>
                        <img src="./img/icone/mail.png">
                        <a href="mailto:<?php echo htmlspecialchars($result["mail"]); ?>"><?php echo htmlspecialchars($result["mail"]); ?></a>
                    </div>

                <?php
                }
                if ($result["urlsite"]) {
                ?>
                    <div>
                        <img src="./img/icone/globe.png">
                        <a href="<?php echo htmlspecialchars($result["urlsite"]); ?>"><?php echo htmlspecialchars($result["urlsite"]); ?></a>
                    </div>

                <?php
                }
                ?>
            </div>
        </div>


        <?php
        if ($typeOffer == "parcs_attractions") {
        ?>
            <img src="<?php echo $result["urlplan"] ?>">
        <?php
        }
        
        if($typeUser === "pro_prive" || $typeUser ==="pro_public"){
            require_once __DIR__ . "/components/avis/avisPro.php";
        }else{
        ?>
        <div class="avis">
            <nav>
                <h3>Avis</h3>
                <h3>Publiez un avis</h3>
            </nav>
            
        <?php
            require_once __DIR__ . "/components/avis/avisMembre.php";
        }
        ?>
        </div>
    </main>
    <?php
    require_once "./components/footer.php";
    ?>


    <script>
        let map;
        let geocoder;
        let marker; // Variable pour stocker le marqueur actuel

        // Initialisation de la carte Google
        function initMap() {
            map = new google.maps.Map(document.getElementById("carte"), {
                center: {
                    lat: 48.8566,
                    lng: 2.3522
                }, // Paris comme point de départ
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
            geocoder.geocode({
                'address': fulladresse
            }, function(results, status) {
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
                }
            });
        }
    </script>

    <!-- Inclure l'API Google Maps avec votre clé API -->
    <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $cleAPI ?>&callback=initMap" async defer></script>


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