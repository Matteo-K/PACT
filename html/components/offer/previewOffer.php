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

function convertionMinuteHeure($tempsEnMinute) {
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
} else{
    $typeOffer = "parcs_attractions";
}

// Fetch photos for the offer
$stmt = $conn->prepare("SELECT * FROM pact._illustre WHERE idoffre = :idoffre ORDER BY url ASC");
$stmt->bindParam(':idoffre', $idOffre);
$stmt->execute();
$photos = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                        <a class="tag" href="index.php?search=<?php echo htmlspecialchars(ucfirst(strtolower($tag))) ?>#searchIndex"><?php echo htmlspecialchars(ucfirst(strtolower($tag))); ?></a>
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
                
                <p>Pas de note pour le moment.</p>
                <section id="desciptionPreview">
                    <h4>Description</h4>
                            <p><?php echo htmlspecialchars($data[$idOffre]["description"]); ?></p>
                </section>

                <section id="InfoCompPreview">
                    <h2>Informations Complémentaires</h2>
                    <?php if($data[$idOffre]["categorie"] == "Visite"){ ?>
                        <div>
                            <p>Durée : <?= convertionMinuteHeure($data[$idOffre]["duree"])?></p>
                            <p>Visite guidée : <?= isset($data[$idOffre]["estGuide"])? "Oui" : "Non"?></p>
                            <?php
                            if($data[$idOffre]["estGuide"]){
                                $stmt = $conn -> prepare("SELECT * FROM pact._visite_langue where idoffre=$idOffre");
                                $stmt -> execute();
                                $langues = $stmt -> fetchAll(PDO::FETCH_ASSOC);
                                if($langues){
                                    ?>
                                    <p>Langues : 
                                <?php
                                    foreach($langues as $key => $langue){
                                        echo $langue["langue"]?>   
                                <?php
                                        if(count($langues) != $key +1){
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
                    } else if($data[$idOffre]["categorie"] == "Spectacle"){
                        $stmt = $conn -> prepare("SELECT * from pact.spectacles where idoffre = $idOffre");
                        $stmt -> execute();
                        $spectacle = $stmt -> fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        <div>
                            <p>Durée : <?= convertionMinuteHeure($data[$idOffre]["duree"])?></p>
                            <p>Nombre de places : <?= $data[$idOffre]["nbPlace"] ?></p>
                        </div>
                        <?php
                    } else if($data[$idOffre]["categorie"] == "Activité" || $data[$idOffre]["categorie"] == "Parc Attraction"){
                        if($data[$idOffre]["categorie"] == "Activité"){
                            $stmt = $conn -> prepare("SELECT * from pact.activites where idoffre = $idOffre");
                        }
                        else{
                            $stmt = $conn -> prepare("SELECT * from pact.parcs_attractions where idoffre = $idOffre");
                        }
                        $stmt -> execute();
                        $theme = $stmt -> fetchAll(PDO::FETCH_ASSOC);
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