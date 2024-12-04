<?php
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

// Vérifier si l'ID d'offre est bien passé et validé
if (isset($idOffre) && is_numeric($idOffre)) {
    // Récupérer les horaires
    $schedules = getSchedules($conn, $idOffre);

    // Rechercher l'offre dans les parcs d'attractions
    $stmt = $conn->prepare("SELECT * FROM pact.parcs_attractions WHERE idoffre = :idoffre");
    $stmt->bindParam(':idoffre', $idOffre, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        // Recherche dans les restaurants, activités, spectacles, et visites
        $types = ['restaurants', 'activites', 'spectacles', 'visites'];
        foreach ($types as $type) {
            $stmt = $conn->prepare("SELECT * FROM pact.$type WHERE idoffre = :idoffre");
            $stmt->bindParam(':idoffre', $idOffre, PDO::PARAM_INT);
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

    // Récupérer les détails de localisation
    $stmt = $conn->prepare("SELECT * FROM pact.localisations_offres WHERE idoffre = :idoffre");
    $stmt->bindParam(':idoffre', $idOffre, PDO::PARAM_INT);
    $stmt->execute();
    $lieu = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch photos for the offer
    $stmt = $conn->prepare("SELECT * FROM pact._illustre WHERE idoffre = :idoffre ORDER BY url ASC");
    $stmt->bindParam(':idoffre', $idOffre, PDO::PARAM_INT);
    $stmt->execute();
    $photos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Gestion d'erreur si l'ID d'offre n'est pas valide
    echo "ID d'offre invalide.";
    exit;
}
?>

<form id="previewOffer" action="enregOffer.php" method="post">
    <section id="sectionPreview">
        <h2 id="titleOffer"><?php echo htmlspecialchars($result["nom"], ENT_QUOTES, 'UTF-8'); ?></h2>
        
        <div id="tagPreview">
            <?php 
            // Fetch tags associated with the offer
            $stmt = $conn->prepare("
                SELECT t.nomTag 
                FROM pact._offre o
                LEFT JOIN pact._tag_parc tp ON o.idOffre = tp.idOffre
                LEFT JOIN pact._tag_spec ts ON o.idOffre = ts.idOffre
                LEFT JOIN pact._tag_Act ta ON o.idOffre = ta.idOffre
                LEFT JOIN pact._tag_restaurant tr ON o.idOffre = tr.idOffre
                LEFT JOIN pact._tag_visite tv ON o.idOffre = tv.idOffre
                LEFT JOIN pact._tag t ON t.nomTag = COALESCE(tp.nomTag, ts.nomTag, ta.nomTag, tr.nomTag, tv.nomTag)
                WHERE o.idOffre = :idoffre
                ORDER BY o.idOffre");
            $stmt->bindParam(':idoffre', $idOffre, PDO::PARAM_INT);
            $stmt->execute();
            $tags = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($tags as $tag): 
                if ($tag["nomTag"]) {
                    ?>
                    <a class="tag" href="search.php"><?php echo htmlspecialchars(ucfirst(strtolower($tag["nomTag"])), ENT_QUOTES, 'UTF-8'); ?></a>
                    <?php 
                }
            endforeach; 
            ?>
        </div>

        <div id="iconeLienPreview">
            <?php
            $stmt = $conn->prepare("SELECT * FROM pact._offre WHERE idoffre = :idoffre");
            $stmt->bindParam(':idoffre', $idOffre, PDO::PARAM_INT);
            $stmt->execute();
            $tel = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['ville'] && $result['pays'] && $result['codepostal']) {
                ?>
                <div>
                    <img src="./img/icone/lieu.png" alt="Location icon">
                    <a href="https://www.google.com/maps?q=<?php echo urlencode($result["numerorue"] . " " . $result["rue"] . ", " . $result["codepostal"] . " " . $result["ville"]); ?>" target="_blank" id="lieu"><?php echo htmlspecialchars($result["numerorue"] . " " . $result["rue"] . ", " . $result["codepostal"] . " " . $result["ville"], ENT_QUOTES, 'UTF-8'); ?></a>
                </div>
                <?php
            }
            if ($result["telephone"] && $tel["affiche"] == TRUE) {
                ?>
                <div>
                    <img src="./img/icone/tel.png" alt="Phone icon">
                    <a href="tel:<?php echo htmlspecialchars($result["telephone"], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($result["telephone"], ENT_QUOTES, 'UTF-8'); ?></a>
                </div>
                <?php
            }
            if ($result["mail"]) {
                ?>
                <div>
                    <img src="./img/icone/mail.png" alt="Mail icon">
                    <a href="mailto:<?php echo htmlspecialchars($result["mail"], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($result["mail"], ENT_QUOTES, 'UTF-8'); ?></a>
                </div>
                <?php
            }
            if ($result["urlsite"]) {
                ?>
                <div>
                    <img src="./img/icone/globe.png" alt="Website icon">
                    <a href="<?php echo htmlspecialchars($result["urlsite"], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($result["urlsite"], ENT_QUOTES, 'UTF-8'); ?></a>
                </div>
                <?php
            }
            ?>
        </div>

        <div class="swiper-container">
            <div class="swiper mySwiperPreview">
                <div class="swiper-wrapper">
                    <?php
                    foreach ($photos as $picture) {
                    ?>
                        <div class="swiper-slide">
                            <img src="<?php echo htmlspecialchars($picture['url'], ENT_QUOTES, 'UTF-8'); ?>" alt="Image">
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
                        <img src="<?php echo htmlspecialchars($picture['url'], ENT_QUOTES, 'UTF-8'); ?>" alt="Thumbnail">
                    </div>
                <?php
                }
                ?>
            </div>
        </div>

        <p>Pas de note pour this moment</p>

        <section id="desciptionPreview">
            <h4>Description</h4>
            <?php
            if ($result["description"]) {
            ?>
                <p><?php echo htmlspecialchars($result["description"], ENT_QUOTES, 'UTF-8'); ?></p>
            <?php
            } else {
            ?>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quasi, illo!</p>
            <?php
            }
            ?>
        </section>

        <section id="InfoCompPreview">
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
                    foreach ($joursSemaine as $jour):
                    ?>
                    <tr>
                        <td class="jourSemaine"><?php echo htmlspecialchars($jour, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td>
                            <?php
                            $horaireMidi = array_filter($schedules['midi'], fn($h) => $h['jour'] === $jour);
                            $horaireSoir = array_filter($schedules['soir'], fn($h) => $h['jour'] === $jour);

                            // Collect hours
                            $horairesAffichage = [];
                            if (!empty($horaireMidi)) {
                                $horairesAffichage[] = htmlspecialchars(current($horaireMidi)['heureouverture'], ENT_QUOTES, 'UTF-8') . " à " . htmlspecialchars(current($horaireMidi)['heurefermeture'], ENT_QUOTES, 'UTF-8');
                            } 
                            if (!empty($horaireSoir)) {
                                $horairesAffichage[] = htmlspecialchars(current($horaireSoir)['heureouverture'], ENT_QUOTES, 'UTF-8') . " à " . htmlspecialchars(current($horaireSoir)['heurefermeture'], ENT_QUOTES, 'UTF-8');
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

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDYU5lrDiXzchFgSAijLbonudgJaCfXrRE&callback=initMap" async defer></script>

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
</form>
