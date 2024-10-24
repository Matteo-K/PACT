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
                    <?php endforeach; 
                    if($ouvert == "EstOuvert"){
                    ?>
                        <a class="tag ouvert" href="search.php">Ouvert</a>
                    <?php
                    } else if($ouvert == "EstFermé"){
                    ?>
                        <a class="tag ferme" href="search.php">Fermé</a>
                    <?php
                    }
                    ?>
                </div>

                <div>
                    <?php
                    $stmt = $conn -> prepare("SELECT * FROM pact._offre WHERE idoffre ='$idOffre'");
                    $stmt -> execute();
                    $tel = $stmt -> fetch(PDO::FETCH_ASSOC);

                    
                    if($lieu){
                    ?>
                        <img src="./img/icone/lieu.png">
                        <p id="lieu"><?php echo htmlspecialchars($lieu["numerorue"] . " " . $lieu["rue"] . ", " . $lieu["codepostal"] . " " . $lieu["ville"]); ?></p>
                    <?php
                        }
                    if($result["telephone"] && $tel["affiche"] == TRUE){
                    ?>
                        <img src="./img/icone/tel.png">
                        <a href="tel:<?php echo htmlspecialchars($result["telephone"]); ?>"><?php echo htmlspecialchars($result["telephone"]); ?></a>
                    <?php
                    }
                    if($result["mail"]){
                        ?>
                        <img src="./img/icone/mail.png">
                        <a href="mailto:<?php echo htmlspecialchars($result["mail"]); ?>"><?php echo htmlspecialchars($result["mail"]); ?></a>
                        <?php
                    }
                    if($result["urlsite"]){
                        ?>
                        <img src="./img/icone/globe.png">
                        <a href="<?php echo htmlspecialchars($result["urlsite"]); ?>"><?php echo htmlspecialchars($result["urlsite"]); ?></a>
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
                
                <p>Pas de note pour this moment</p>
                <section>
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
                <div id="map" class="carte"></div>
                <div>
                    <?php
                    if($lieu){
                    ?>
                        <img src="./img/icone/lieu.png">
                        <p id="lieu"><?php echo htmlspecialchars($lieu["numerorue"] . " " . $lieu["rue"] . ", " . $lieu["codepostal"] . " " . $lieu["ville"]); ?></p>
                    <?php
                        }
                    if($result["telephone"] && $tel["affiche"] == TRUE){
                    ?>
                        <img src="./img/icone/tel.png">
                        <a href="tel:<?php echo htmlspecialchars($result["telephone"]); ?>"><?php echo htmlspecialchars($result["telephone"]); ?></a>
                    <?php
                    }
                    if($result["mail"]){
                        ?>
                        <img src="./img/icone/mail.png">
                        <a href="mailto:<?php echo htmlspecialchars($result["mail"]); ?>"><?php echo htmlspecialchars($result["mail"]); ?></a>
                        <?php
                    }
                    if($result["urlsite"]){
                        ?>
                        <img src="./img/icone/globe.png">
                        <a href="<?php echo htmlspecialchars($result["urlsite"]); ?>"><?php echo htmlspecialchars($result["urlsite"]); ?></a>
                        <?php
                    }
                    ?>   
                </div>

                <?php
                    if($typeOffer == "parcs_attractions" ){
                ?>
                        <img src="<?php echo $result["urlplan"]?>">
                <?php
                    }
                ?>
    </section>
