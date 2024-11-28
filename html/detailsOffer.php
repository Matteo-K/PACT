<?php
require_once "config.php";
require_once __DIR__ . "/../.SECURE/cleAPI.php";
$idOffre = $_POST["idoffre"] ?? null;
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
$stmt = $conn->prepare("SELECT * FROM pact.offrescomplete WHERE idoffre = :idoffre");
$stmt->bindParam(':idoffre', $idOffre);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$result) {
?>
    <form id="manageOfferAuto" action="manageOffer.php" method="post">
        <input type="hidden" name="idOffre" value="<?php echo $idOffre ?>">
    </form>
    <script>
        document.getElementById("manageOfferAuto").submit();
    </script>
<?php

} else {
    $typeOffer = $result[0]['categorie'];
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

$stmt = $conn->prepare(" SELECT a.*,
    AVG(a.note) OVER() AS moynote,
    COUNT(a.note) OVER() AS nbnote,
    SUM(CASE WHEN a.note = 1 THEN 1 ELSE 0 END) OVER() AS note_1,
    SUM(CASE WHEN a.note = 2 THEN 1 ELSE 0 END) OVER() AS note_2,
    SUM(CASE WHEN a.note = 3 THEN 1 ELSE 0 END) OVER() AS note_3,
    SUM(CASE WHEN a.note = 4 THEN 1 ELSE 0 END) OVER() AS note_4,
    SUM(CASE WHEN a.note = 5 THEN 1 ELSE 0 END) OVER() AS note_5,
    m.url AS membre_url,
    r.idc_reponse,
    r.denomination AS reponse_denomination,
    r.contenureponse,
    r.reponsedate,
    r.idpro
FROM 
    pact.avis a
JOIN 
    pact.membre m ON m.pseudo = a.pseudo
LEFT JOIN 
    pact.reponse r ON r.idc_avis = a.idc
WHERE 
    a.idoffre = ?
ORDER BY 
    a.datepublie desc
");
$stmt->execute([$idOffre]);
$avis = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="style.css">

    <title><?php echo htmlspecialchars($result[0]["nom"]); ?></title>
</head>

<body>
    <?php require_once "components/header.php"; ?>

    <main class="mainOffer">
        <fieldset class="info">
            <legend>Information de l'offre</legend>

            <?php
            if (($typeUser == "pro_public" || $typeUser == "pro_prive")) {
                $cook = $conn->prepare("SELECT o.idu,o.idoffre,o.nom,o.statut,o.description,o.mail,o.affiche,o.resume FROM pact._offre o WHERE idoffre=$idOffre");
                $cook->execute();
                $offre = $cook->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <h3 class="Enligne"><?php echo $offre[0]['statut'] ?></h3>

            <div class="buttonDetails">
                <?php
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
                            <button class="modifierBut" type="submit">
                                <?php echo $statutActuel === 'inactif' ? 'Mettre en ligne' : 'Mettre hors ligne'; ?>
                            </button>
                        </form>
                    <?php
                    }
                    ?>

                    <div class="form-container">
                        <form method="post" action="manageOffer.php">
                            <input type="hidden" name="idOffre" value="<?php echo $offre[0]['idoffre']; ?>">
                            <input type="hidden" name="page" value="2">
                            <button 
                                class="modifierBut <?php echo $offre[0]['statut'] === 'actif' ? 'disabled' : ''; ?>" 
                                type="submit"
                                onmouseover="showMessage(event)"
                                onmouseout="hideMessage(event)"
                                <?php if ($offre[0]['statut'] === 'actif') { ?>
                                    onclick="return false;"
                                <?php } ?>
                            >
                                <?php echo "Modifier offre"; ?>
                            </button>
                        </form>
                                
                        <!-- Message affiché au survol du bouton désactivé -->
                                
                    </div>



                <?php
                }

                ?>
            </div>
        </fieldset>
        <?php if ($offre[0]['statut'] === 'actif') { ?>
            <section id="hoverMessage" class="hover-message"">Veuillez mettre votre offre hors ligne pour la modifier</section>
        <?php } ?>
        <h2 id="titleOffer"><?php echo htmlspecialchars($result[0]["nom"]); ?></h2>
        <h3 id="typeOffer"><?php echo str_replace("_", " ", ucfirst(strtolower($typeOffer))) ?> à <?php echo $lieu['ville'] ?></h3>
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

            if ($typeOffer == "restaurant") {
                array_push($tags, ['nomtag' => $result[0]['gammedeprix']]);
            }

            foreach ($tags as $tag):
                if ($tag["nomtag"] != NULL) {
            ?>
                    <a class="tag" href="search.php?search=<?= str_replace("_", "+", $tag["nomtag"] )?>"><?php echo htmlspecialchars(str_replace("_", " ", ucfirst(strtolower($tag["nomtag"])))); ?></a>
                <?php }
            endforeach;

            if ($ouvert == "EstOuvert") {
                ?>
                <a class="ouvert" href="search.php?search=ouvert">Ouvert</a>
            <?php
            } else if ($ouvert == "EstFermé") {
            ?>
                <a class="ferme" href="search.php?search=ferme">Fermé</a>
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
            if ($result[0]["telephone"] && $tel["affiche"] == TRUE) {
            ?>
                <div>
                    <img src="./img/icone/tel.png">
                    <a href="tel:<?php echo htmlspecialchars($result[0]["telephone"]); ?>"><?php echo htmlspecialchars($result[0]["telephone"]); ?></a>
                </div>
            <?php
            }
            if ($result[0]["mail"]) {
            ?>
                <div>
                    <img src="./img/icone/mail.png">
                    <a href="mailto:<?php echo htmlspecialchars($result[0]["mail"]); ?>"><?php echo htmlspecialchars($result[0]["mail"]); ?></a>
                </div>

            <?php
            }
            if ($result[0]["urlsite"]) {
            ?>
                <div>
                    <img src="./img/icone/globe.png">
                    <a href="<?php echo htmlspecialchars($result[0]["urlsite"]); ?>"><?php echo htmlspecialchars($result[0]["urlsite"]); ?></a>
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
            <?php
            if (!$avis) {
                echo '<p>Pas de note pour le moment</p>';
            } else {
                $etoilesPleines = floor($avis[0]['moynote']); // Nombre entier d'étoiles pleines
                $reste = $avis[0]['moynote'] - $etoilesPleines; // Reste pour l'étoile partielle
            ?>
                <div class="notation">
                    <div>
                        <?php
                        // Étoiles pleines
                        for ($i = 1; $i <= $etoilesPleines; $i++) {
                            echo '<div class="star pleine"></div>';
                        }
                        // Étoile partielle
                        if ($reste > 0) {
                            $pourcentageRempli = $reste * 100; // Pourcentage rempli
                            echo '<div class="star partielle" style="--pourcentage: ' . $pourcentageRempli . '%;"></div>';
                        }
                        // Étoiles vides
                        for ($i = $etoilesPleines + ($reste > 0 ? 1 : 0); $i < 5; $i++) {
                            echo '<div class="star vide"></div>';
                        }
                        ?>
                        <p><?php echo number_format($avis[0]['moynote'], 1); ?> / 5 (<?php echo $avis[0]['nbnote']; ?> avis)</p>
                    </div>
                    <div class="notedetaille">
                        <?php
                        // Adjectifs pour les notes
                        $listNoteAdjectif = ["Horrible", "Médiocre", "Moyen", "Très bon", "Excellent"];
                        for ($i = 5; $i >= 1; $i--) {
                            // Largeur simulée pour chaque barre en fonction de vos données
                            $pourcentageParNote = isset($avis[0]["note_$i"]) ? ($avis[0]["note_$i"] / $avis[0]['nbnote']) * 100 : 0;
                        ?>
                            <div class="ligneNotation">
                                <span><?= $listNoteAdjectif[$i-1]; ?></span>
                                <div class="barreDeNotationBlanche">
                                    <div class="barreDeNotationJaune" style="width: <?= $pourcentageParNote; ?>%;"></div>
                                </div>
                                <span>(<?= isset($avis[0]["note_$i"]) ? $avis[0]["note_$i"] : 0; ?>)</span>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            <?php
            }
            ?>
            <section>
                <h3>Description</h3>
                <p><?php echo htmlspecialchars($result[0]["description"]); ?></p>
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
                if ($result[0]["telephone"] && $tel["affiche"] == TRUE) {
                ?>
                    <div>
                        <img src="./img/icone/tel.png">
                        <a href="tel:<?php echo htmlspecialchars($result[0]["telephone"]); ?>"><?php echo htmlspecialchars($result[0]["telephone"]); ?></a>
                    </div>
                <?php
                }
                if ($result[0]["mail"]) {
                ?>
                    <div>
                        <img src="./img/icone/mail.png">
                        <a href="mailto:<?php echo htmlspecialchars($result[0]["mail"]); ?>"><?php echo htmlspecialchars($result[0]["mail"]); ?></a>
                    </div>

                <?php
                }
                if ($result["urlsite"]) {
                ?>
                    <div>
                        <img src="./img/icone/globe.png">
                        <a href="<?php echo htmlspecialchars($result[0]["urlsite"]); ?>"><?php echo htmlspecialchars($result[0]["urlsite"]); ?></a>
                    </div>

                <?php
                }
                ?>
            </div>
        </div>


        <?php
        if ($typeOffer == "parcs_attractions") {
        ?>
            <img src="<?php echo $result[0]["urlplan"] ?>">
        <?php
        }

        if ($typeUser === "pro_prive" || $typeUser === "pro_public") {
            require_once __DIR__ . "/components/avis/avisPro.php";
        } else {
        ?>
            <div class="avis">
                <nav>
                    <h3>Avis</h3>
                    <h3>Publiez un avis</h3>
                </nav>

            <?php
            echo "<div>";
            require_once __DIR__ . "/components/avis/avisMembre.php";
            echo "</div";
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


        function showMessage(event) {
            const message = document.getElementById('hoverMessage');
            message.style.display = 'block';
        }

        // Fonction pour masquer le message
        function hideMessage(event) {
            const message = document.getElementById('hoverMessage');
            message.style.display = 'none';
        }

        // Ajouter une entrée personnalisée dans l'historique
        history.pushState(null, '', window.location.href);

        // Intercepter l'action de retour
        window.onpopstate = function(event) {
            console.log('Redirection vers:', window.location.href);
            window.location.href = './search.php';
        };


    </script>
    <script src="js/setColor.js"></script>
</body>

</html>