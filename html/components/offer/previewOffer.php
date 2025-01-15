<?php
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

function convertionMinuteHeure($tempsEnMinute)
{
    $heures = floor($tempsEnMinute / 60);
    $minutes = $tempsEnMinute % 60;

    if ($minutes == 0) {
        return $heures . "h";
    } else {
        return $heures . "h " . $minutes . "min";
    }
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
} else {
    $typeOffer = "parcs_attractions";
}

// Fetch photos for the offer
$stmt = $conn->prepare("SELECT * FROM pact._illustre WHERE idoffre = :idoffre ORDER BY url ASC");
$stmt->bindParam(':idoffre', $idOffre);
$stmt->execute();
$photos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->prepare("SELECT a.*,
    AVG(a.note) OVER() AS moynote,
    COUNT(a.note) OVER() AS nbnote,
    SUM(CASE WHEN a.note = 1 THEN 1 ELSE 0 END) OVER() AS note_1,
    SUM(CASE WHEN a.note = 2 THEN 1 ELSE 0 END) OVER() AS note_2,
    SUM(CASE WHEN a.note = 3 THEN 1 ELSE 0 END) OVER() AS note_3,
    SUM(CASE WHEN a.note = 4 THEN 1 ELSE 0 END) OVER() AS note_4,
    SUM(CASE WHEN a.note = 5 THEN 1 ELSE 0 END) OVER() AS note_5,
    SUM(CASE WHEN a.lu = FALSE then 1 else 0 end) over() as avisnonlus,
	SUM(CASE WHEN r.idc_reponse is null then 1 else 0 end) over() as avisnonrepondus,
    m.url AS membre_url,
    m.idu,
    r.idc_reponse,
    r.denomination AS reponse_denomination,
    r.contenureponse,
    r.nblikepro as likereponse,
    r.nbdislikepro as dislikereponse,
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

<?php
$ar = new ArrayOffer($idOffre);
$data = $ar->getArray();
?>

<form id="previewOffer" action="enregOffer.php" method="post">
    <section id="sectionPreview">
        <h2 id="titleOffer">
            <?php
            echo $data[$idOffre]["nomOffre"];
            ?>
        </h2>


        <div id="tagPreview">
            <?php
            // Fetch tags associated with the offer   
            foreach ($data[$idOffre]["tags"] as $tag) {
            ?>
                <a class="tag"><?php echo htmlspecialchars(ucfirst(strtolower($tag))); ?></a>
            <?php
            }
            ?>

        </div>

        <div id="iconeLienPreview">
            <?php
            $stmt = $conn->prepare("SELECT * FROM pact._offre WHERE idoffre ='$idOffre'");
            $stmt->execute();
            $tel = $stmt->fetch(PDO::FETCH_ASSOC);


            ?>
            <div>
                <img src="./img/icone/lieu.png">
                <a href="https://www.google.com/maps?q=<?php echo !empty($data[$idOffre]["ville"]) ? urlencode($data[$idOffre]["numeroRue"] . " " . $data[$idOffre]["rue"] . ", " . $data[$idOffre]["codePostal"] . " " . $data[$idOffre]["ville"]) : ""; ?>" target="_blank" id="lieu"><?php echo !empty($data[$idOffre]["ville"]) ? htmlspecialchars($data[$idOffre]["numeroRue"] . " " . $data[$idOffre]["rue"] . ", " . $data[$idOffre]["codePostal"] . " " . $data[$idOffre]["ville"]) : "adresse, code postal  ville"; ?></a>
            </div>
            <div>
                <img src="./img/icone/tel.png">
                <a href="tel:<?php echo htmlspecialchars($data[$idOffre]["telephone"]); ?>"><?php echo !empty($data[$idOffre]["telephone"]) ? htmlspecialchars($data[$idOffre]["telephone"]) : "téléphone"; ?></a>
            </div>
            <div>
                <img src="./img/icone/mail.png">
                <a href="mailto:<?php echo htmlspecialchars($data[$idOffre]["mail"]); ?>"><?php echo !empty($data[$idOffre]["mail"]) ? htmlspecialchars($data[$idOffre]["mail"]) : "adresse@mail.domaine"; ?></a>
            </div>
            <!-- url du site -->
            <div>
                <img src="./img/icone/globe.png">
                <a href="<?php echo htmlspecialchars(""); ?>"><?php echo isset($data[$idOffre]["urlSite"]) ? htmlspecialchars("") : "https://lien/site/web/"; ?></a>
            </div>

        </div>

        <div class="swiper-container">
            <div class="swiper mySwiperPreview">
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

        <div thumbsSlider="" class="swiper myThumbSliderPreview">
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
                echo '<p class="notation">Pas de note pour le moment</p>';
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
                                <span><?= $listNoteAdjectif[$i - 1]; ?></span>
                                <div class="barreDeNotationBlanche">
                                    <div class="barreDeNotationJaune" style="width: <?= $pourcentageParNote; ?>%;"></div>
                                </div>
                                <span>(<?= isset($avis[0]["note_$i"]) ? $avis[0]["note_$i"] : 0; ?> avis)</span>
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

        <section id="InfoCompPreview">
            <h2>Informations Complémentaires</h2>
            <?php if ($data[$idOffre]["categorie"] == "Visite") { ?>
                <div>
                    <p>Durée : <?= convertionMinuteHeure($data[$idOffre]["duree"]) ?></p>
                    <p>Visite guidée : <?= isset($data[$idOffre]["estGuide"]) ? "Oui" : "Non" ?></p>
                    <?php
                    if ($data[$idOffre]["estGuide"]) {
                        $stmt = $conn->prepare("SELECT * FROM pact._visite_langue where idoffre=$idOffre");
                        $stmt->execute();
                        $langues = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        if ($langues) {
                    ?>
                            <p>Langues :
                                <?php
                                foreach ($langues as $key => $langue) {
                                    echo $langue["langue"] ?>
                                <?php
                                    if (count($langues) != $key + 1) {
                                        echo ", ";
                                    }
                                }
                                ?>
                            </p>
                    <?php
                        }
                    }
                    ?>
                </div>
            <?php
            } else if ($data[$idOffre]["categorie"] == "Spectacle") {
                $stmt = $conn->prepare("SELECT * from pact.spectacles where idoffre = $idOffre");
                $stmt->execute();
                $spectacle = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>
                <div>
                    <p>Durée : <?= convertionMinuteHeure($data[$idOffre]["duree"]) ?></p>
                    <p>Nombre de places : <?= $data[$idOffre]["nbPlace"] ?></p>
                </div>
            <?php
            } else if ($data[$idOffre]["categorie"] == "Activité" || $data[$idOffre]["categorie"] == "Parc Attraction") {
                if ($data[$idOffre]["categorie"] == "Activité") {
                    $stmt = $conn->prepare("SELECT * from pact.activites where idoffre = $idOffre");
                } else {
                    $stmt = $conn->prepare("SELECT * from pact.parcs_attractions where idoffre = $idOffre");
                }
                $stmt->execute();
                $theme = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>
                <div>
                    <p>Âge minimum : <?= $data[$idOffre]["ageMinimal"] ?> ans</p>
                </div>
            <?php
            }
            ?>
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
        <div id="mapPreview" class="carte"></div>

    </section>

    script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDYU5lrDiXzchFgSAijLbonudgJaCfXrRE&callback=initMap" async defer></script>


    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <!-- Initialize Swiper -->
    <script>
        var swiper = new Swiper(".myThumbSliderPreview", {
            loop: true,
            spaceBetween: 10,
            slidesPerView: 4,
            freeMode: true,
            watchSlidesProgress: true,
        });
        var swiper2 = new Swiper(".mySwiperPreview", {
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