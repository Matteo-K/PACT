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


<form id="previewOffer" action="enregOffer.php" method="post">
    <section id="sectionPreview">
    <h2 id="titleOffer">
    <?php 
        if ($result && isset($result)) {
            echo htmlspecialchars($result["nom"] ?? "No name available");
        } else {
            echo "Pas d'offre trouvée ?";
        }
    ?>
    </h2>

                
                <div id="tagPreview">
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
                    
                    foreach ($tags as $tag): 
                    if($tag["nomtag"]) {
                    ?>
                        <a class="tag" href="search.php"><?php echo htmlspecialchars(ucfirst(strtolower($tag["nomtag"]))); ?></a>
                    <?php 
                        } endforeach; 
                    ?>
                   
                </div>

                <div id="iconeLienPreview">
                <?php
                $stmt = $conn->prepare("SELECT * FROM pact._offre WHERE idoffre ='$idOffre'");
                $stmt->execute();
                $tel = $stmt->fetch(PDO::FETCH_ASSOC);


                if ($result['ville'] && $result['pays'] && $result['codepostal']) {
                ?>
                    <div>
                        <img src="./img/icone/lieu.png">
                        <a href="https://www.google.com/maps?q=<?php echo urlencode($result["numerorue"] . " " . $result["rue"] . ", " . $result["codepostal"] . " " . $result[0]["ville"]); ?>" target="_blank" id="lieu"><?php echo htmlspecialchars($result[0]["numerorue"] . " " . $result[0]["rue"] . ", " . $result[0]["codepostal"] . " " . $result[0]["ville"]); ?></a>
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
                if ($result[0]["urlsite"]) {
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
                
                <p>Pas de note pour le moment</p>
                <section id="desciptionPreview">
                    <h4>Description</h4>
                    <?php
                        if($result["description"]) {
                    ?>
                            <p><?php echo htmlspecialchars($result["description"]); ?></p>
                    <?php

                        } 
                        
                        else {
                    ?>
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quasi, illo!</p>
                    <?php
                        }

                    ?>
                </section>

                <section id="infoComp">
        <h2>Informations Complémentaires</h2>
        <?php
        if($typeOffer == "Visite"){
            $stmt = $conn -> prepare("SELECT * from pact.visites where idoffre = $idOffre");
            $stmt -> execute();
            $visite = $stmt -> fetchAll(PDO::FETCH_ASSOC);
        ?>
            <div>
                <p>Durée : <?= convertionMinuteHeure($visite[0]['duree'])?></p>
                <p>Visite guidée : <?= isset($visite[0]["guide"])? "Oui" : "Non"?></p>
                <?php
                if($visite[0]["guide"]){
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
        } else if($typeOffer == "Spectacle"){
            $stmt = $conn -> prepare("SELECT * from pact.spectacles where idoffre = $idOffre");
            $stmt -> execute();
            $spectacle = $stmt -> fetchAll(PDO::FETCH_ASSOC);
            ?>
            <div>
                <p>Durée : <?= convertionMinuteHeure($spectacle[0]['duree'])?></p>
                <p>Nombre de places : <?= $spectacle[0]['nbplace']?></p>
            </div>
            <?php
        } else if($typeOffer == "Activité" || $typeOffer == "Parc Attraction"){
            if($typeOffer == "Activité"){
                $stmt = $conn -> prepare("SELECT * from pact.activites where idoffre = $idOffre");
            } 
            else{
                $stmt = $conn -> prepare("SELECT * from pact.parcs_attractions where idoffre = $idOffre");
            }
            $stmt -> execute();
            $theme = $stmt -> fetchAll(PDO::FETCH_ASSOC);
            ?>
            <div>
                <p>Âge minimum : <?= $theme[0]['agemin']?> ans</p>
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

        // Récupérer les horaires à partir de la fonction getSchedules
        $schedules = getSchedules();
        // Afficher les horaires pour chaque jour de la semaine
        if($result[0]['categorie'] == 'Spectacle' || $result[0]['categorie'] == 'Activité') {
            $horaireSpectacle = [];
            if($schedules['spectacle']){
                usort($schedules['spectacle'], function($a, $b) {
                    $dateA = new DateTime($a['dateRepresentation']);
                    $dateB = new DateTime($b['dateRepresentation']);
                    return $dateA <=> $dateB; // Trier du plus récent au plus ancien
                });

                foreach($schedules['spectacle'] as $spec){
                    $dateSpectacle = new DateTime($spec['dateRepresentation']);
                    ?>
                    <tr>
                        <td class="jourSemaine"><?= ucwords(formatDateEnFrancais($dateSpectacle))?></td>
                        <td>
                            <?php
                                echo "à " . str_replace("=>",":",$spec['heureOuverture']);
                            ?>
                        </td>
                    </tr>
                    <?php

                }
            }
        }else{
            foreach ($joursSemaine as $jour): ?>
                <tr>
                    <td class="jourSemaine"><?php echo htmlspecialchars($jour); ?></td>
                    <td>
                        <?php

                            // Filtrer les horaires pour chaque jour spécifique
                            $horaireMidi = [];
                            $horaireSoir = [];
        
                            if ($schedules['midi']) {
                                $horaireMidi = array_filter($schedules['midi'], fn($h) => $h['jour'] === $jour);
                            }
                            if ($schedules['soir']) {
                                $horaireSoir = array_filter($schedules['soir'], fn($h) => $h['jour'] === $jour);
                            }
        
                            // Collecter les horaires à afficher
                            $horairesAffichage = [];
                            if (!empty($horaireMidi)) {
                                $horaireMidi = current($horaireMidi); // Prendre le premier élément du tableau filtré
                                $horairesAffichage[] = htmlspecialchars(str_replace("=>", ":",$horaireMidi['heureOuverture'])) . " à " . htmlspecialchars(str_replace("=>", ":",$horaireMidi['heureFermeture']));
                            }
                            if (!empty($horaireSoir)) {
                                $horaireSoir = current($horaireSoir); // Prendre le premier élément du tableau filtré
                                $horairesAffichage[] = htmlspecialchars(str_replace("=>", ":",$horaireSoir['heureOuverture'])) . " à " . htmlspecialchars(str_replace("=>", ":",$horaireSoir['heureFermeture']));
                            }
                            if (empty($horaireMidi) && empty($horaireSoir)) {
                                $horairesAffichage[] = "Fermé";
                            }
        
                            // Afficher les horaires ou "Fermé"
                            echo implode(' et ', $horairesAffichage); 
                        ?>
                    </td>
                </tr>
            <?php 
                endforeach;   
            }
            ?>
    </tbody>
</table>


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