<?php
require_once "config.php";
require_once __DIR__ . "/../.SECURE/cleAPI.php";
$idOffre = $_POST["idoffre"] ?? null;
$ouvert = $_GET["ouvert"] ?? null;
$aujourdhui = new DateTime();

// Vérifiez si idoffre est défini
if (!$idOffre) {
    header("location: index.php");
    exit();
}

$monOffre = new ArrayOffer($idOffre);
$ouverture = $monOffre->getArray()[$idOffre]["ouverture"];

$stmt = $conn->prepare("SELECT * FROM pact.offres WHERE idoffre = :idoffre");
$stmt->bindParam(':idoffre', $idOffre);
$stmt->execute();
$offre = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Rechercher l'offre dans les parcs d'attractions
$stmt = $conn->prepare("SELECT * FROM pact.offrescomplete WHERE idoffre = :idoffre");
$stmt->bindParam(':idoffre', $idOffre);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Fonction pour récupérer les horaires

/**
 * @return array{midi: array, soir: array, spectacle: array}
 */
function getSchedules()
{

    global $result;
    $schedules = [
        'midi' => [],
        'soir' => [],
        'spectacle' => []
    ];


    // Vérifier si les résultats existent
    if ($result[0]) {
        // Traitement des horaires midi
        if ($result[0]['listhorairemidi']) {
            // Remplacer les { et } uniquement dans les parties de l'objet qui ne sont pas des horaires
            $listhorairemidi = $result[0]['listhorairemidi'];

            // Remplacer les { par [ et les } par ] pour le reste
            $listhorairemidi = str_replace(
                ['{', '}', ':', ';'], 
                ['[', ']', '=>', ','], 
                $listhorairemidi
            );

            // Encapsuler dans des crochets pour créer un tableau
            $listhorairemidi = '[' . $listhorairemidi . ']';
            // Utiliser eval() pour transformer la chaîne en tableau PHP
            eval('$listhorairemidi = ' . $listhorairemidi . ';');
        } else {
            $listhorairemidi = [];
        }

        // Traitement des horaires soir
        if ($result[0]['listhorairesoir']) {
            $listhorairesoir = $result[0]['listhorairesoir'];

            // Remplacer les { par [ et les } par ] pour le reste
            $listhorairesoir = str_replace(
                ['{', '}', ':', ';'], 
                ['[', ']', '=>', ','], 
                $listhorairesoir
            );

            // Encapsuler dans des crochets pour créer un tableau
            $listhorairesoir = '[' . $listhorairesoir . ']';
            // Utiliser eval() pour transformer la chaîne en tableau PHP
            eval('$listhorairesoir = ' . $listhorairesoir . ';');
        } else {
            $listhorairesoir = [];
        }

        if($result[0]['listehoraireprecise']){
            $listhorairespectacle = $result[0]['listehoraireprecise'];

            $listhorairespectacle = str_replace(
                ['{', '}', ':', ';'], 
                ['[', ']', '=>', ','], 
                $listhorairespectacle
            );

            // Encapsuler dans des crochets pour créer un tableau
            $listhorairespectacle = '[' . $listhorairespectacle . ']';
            // Utiliser eval() pour transformer la chaîne en tableau PHP
            eval('$listhorairespectacle = ' . $listhorairespectacle . ';');
        } else {
            $listhorairespectacle = [];
        }

        // Ajouter les horaires décodés aux tableaux de résultats
        $schedules['midi'] = $listhorairemidi;
        $schedules['soir'] = $listhorairesoir;
        $schedules['spectacle'] = $listhorairespectacle;
    }

    return $schedules;
}

$schedules = getSchedules();

function formatDateEnFrancais(DateTime $date) {
    // Traduction des jours de la semaine
    $joursSemaine = [
        'Monday' => 'Lundi',
        'Tuesday' => 'Mardi',
        'Wednesday' => 'Mercredi',
        'Thursday' => 'Jeudi',
        'Friday' => 'Vendredi',
        'Saturday' => 'Samedi',
        'Sunday' => 'Dimanche'
    ];

    // Traduction des mois
    $moisAnnee = [
        'January' => 'Janvier',
        'February' => 'Février',
        'March' => 'Mars',
        'April' => 'Avril',
        'May' => 'Mai',
        'June' => 'Juin',
        'July' => 'Juillet',
        'August' => 'Août',
        'September' => 'Septembre',
        'October' => 'Octobre',
        'November' => 'Novembre',
        'December' => 'Décembre'
    ];

    // Extraire les composants de la date
    $jour = $joursSemaine[$date->format('l')];  // Jour en français
    $mois = $moisAnnee[$date->format('F')];     // Mois en français
    $jourMois = $date->format('d');             // Jour du mois
    $annee = $date->format('Y');                // Année

    // Retourner la date formatée
    return "$jour $jourMois $mois $annee";
}

if (!$result) {
?>
    <form id="manageOfferAuto" action="manageOffer.php" method="post">
        <input type="hidden" name="idOffre" value="<?php echo $idOffre ?>">
        <input type="hiddedn" name="page" value="2">
    </form>
    <script>
        document.getElementById("manageOfferAuto").submit();
    </script>
<?php

} else {
    $typeOffer = $result[0]['categorie'];
}


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
        <?php
        if ($typeUser == "pro_prive" || $typeUser == "pro_public") {
        ?>

            <fieldset class="info">
                <legend>Information de l'offre</legend>

                <?php
                if (($typeUser == "pro_public" || $typeUser == "pro_prive")) {
                ?>
                    <h3 class="Enligne"><?php echo $offre[0]['statut'] ?></h3>

                    <div class="buttonDetails">
                        <form method="post" action="changer_statut.php">
                            <!-- Envoyer l'ID de l'offre pour pouvoir changer son statut -->
                            <input type="hidden" name="offre_id" value="<?php echo $offre[0]['idoffre']; ?>">
                            <input type="hidden" name="nouveau_statut" value="<?php echo $offre[0]['statut'] === 'inactif' ? 'actif' : 'inactif'; ?>">
                            <button class="modifierBut" type="submit">
                                <?php echo $offre[0]['statut'] === 'inactif' ? 'Mettre en ligne' : 'Mettre hors ligne'; ?>
                            </button>
                        </form>
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
                                    <?php } ?>>
                                    <?php echo "Modifier offre"; ?>
                                </button>
                            </form>
                        </div>
                        <button id="openModalBtn" class="modifierBut">Gérer mes options</button>
                    <?php
                }

                    ?>
                    </div>
            </fieldset>
            <section id="myModal" class="modal">
              <section class="modal-content">
                <span class="close">&times;</span>
                          
                <!-- Titres des onglets -->
                <section class="titre">
                  <h2 class="tab active" data-tab="1">Gestion des options</h2>
                  <h2 class="tab" data-tab="2">Ajouter une option</h2>
                  <!-- Trait qui se déplace sous les onglets -->
                  <section class="traitBouge"></section>
                </section>
                <section class="contentPop active" id="content-1">
                    <?php 
                        $option = $conn->prepare("SELECT * FROM pact.option WHERE idoffre=? and (datefin>CURRENT_DATE OR datefin is null) and nomoption = 'ALaUne'");
                        $option->execute([$idOffre]);
                        $optionUne = $option->fetchAll(PDO::FETCH_ASSOC);
                        $option = $conn->prepare("SELECT * FROM pact.option WHERE idoffre=? and (datefin>CURRENT_DATE OR datefin is null) and nomoption = 'EnRelief'");
                        $option->execute([$idOffre]);
                        $optionRelief = $option->fetchAll(PDO::FETCH_ASSOC);
                        $mesOtion;
                        if ($optionRelief) {
                            $mesOtion[] = $optionRelief;
                        }
                        if ($optionUne) {
                            $mesOtion[] = $optionUne;
                        }
                        if ($mesOtion != []) {
                            ?>
                                <strong><p>Mes options : </p></strong>
                                <ul>
                                    <?php
                                    print_r($mesOtion);
                                        foreach ($mesOtion as $key => $value) {
                                            ?>
                                            <li>
                                                <section class="popUpOption">
                                                    <?php
                                                    print_r($value);
                                                    if ($value['datefin'] != null) {
                                                        $dateActuelle = NEW DateTime();
                                                        $dateFin = NEW DateTime($value['datefin']);
                                                        $dureeRestante = $dateActuelle->diff($dateFin);
                                                        ?><p><?php echo "Option en cours : " . $value['nomoption'] . " prends fin dans " . $dureeRestante->days . "jours." ?></p>
                                                        <button class="modifierBut">Arrêter</button>
                                                        <?php
                                                    } else {
                                                        ?><p><?php echo "Option pas commencer : " . $value['nomoption'] . " Commencera lors de la prochaine mise en ligne pour " . $value['duree_total']*7 . "jours." ?></p>
                                                        <button class="modifierBut">Résilier</button>
                                                        <?php
                                                    } 
                                                    ?>
                                                </section>
                                            </li>
                                            <?php
                                        }
                                    ?>
                                </ul>
                            <?php
                        } else {
                            ?>
                                <strong><p>Aucune option activé</p></strong>
                            <?php
                        }


                    ?>
                </section>
                <section class="contentPop" id="content-2">
                    <section class="AlaUne">
                        <strong>
                            <p class="taille3">A la Une</p>
                        </strong>
                        <section class="donnee">
                            <aside>
                                <form id="formOpt1" action="addOption.php" method="post">
                                    <input type="hidden" name="nomOption" value="ALaUne">
                                    <input type="hidden" name="idOffre" value="<?php echo $idOffre ?>">
                                    <input type="hidden" name="type" value="ajout">
                                    <label class="taille" for="nbWeek">Nombre de semaine à la Une</label>
                                    <input class="taille2" type="number" name="nbWeek" id="nbWeekALaUne" min="1" max="4" value="1">
                                </form>
                                <?php
                                if (!$optionUne) {
                                    ?>
                                        <p class="taille4">*l'option sera active lors de la prochaine mise en ligne</p>
                                        <?php                                
                                } else {
                                    ?>
                                        <p class="taille4">*L'option sera lancer à la fin de celle-ci</p>
                                    <?php
                                }
                                ?>
                            </aside>
                            <section class="sectionBtn">
                                <button id="button1" class="modifierBut">Ajouter</button>
                            </section>
                        </section>
                    </section>
                    <section class="EnRelief">
                        <strong>
                            <p class="taille3">En Relief</p>
                        </strong>
                        <section class="donnee">
                            <aside>
                                <form id="formOpt2" action="addOption.php" method="post">
                                    <input type="hidden" name="nomOption" value="ALaUne">
                                    <input type="hidden" name="idOffre" value="<?php echo $idOffre ?>">
                                    <input type="hidden" name="type" value="ajout">
                                    <label class="taille" for="nbWeek">Nombre de semaine en Relief</label>
                                    <input class="taille2" type="number" name="nbWeek" id="nbWeekALaUne" min="1" max="4" value="1">
                                </form>
                                <?php
                                if (!$optionRelief) {
                                    ?>
                                        <p class="taille4">*l'option sera active lors de la prochaine mise en ligne</p>
                                        <?php                                
                                } else {
                                    ?>
                                        <p class="taille4">*L'option sera lancer à la fin de celle-ci</p>
                                        <?php
                                }
                                ?>
                            </aside>
                            <section class="sectionBtn">
                                <button id="button2" class="modifierBut">Ajouter</button>
                            </section>
                        </section>
                    </section>
                </section>              
                <button class="modifierBut" onclick="confirmation()">Comfirmer</button>
              </section>
            </section>
            <?php if ($offre[0]['statut'] === 'actif') { ?>
                <section id="hoverMessage" class="hover-message"">Veuillez mettre votre offre hors ligne pour la modifier</section>
            <?php }
        }
        ?>
        
        <h2 id="titleOffer"><?php echo htmlspecialchars($result[0]["nom"]); ?></h2>
        <h3 id="typeOffer"><?php echo $typeOffer ?> à <?php echo $result[0]['ville'] ?></h3>
        <?php
        if (($typeUser == "pro_public" || $typeUser == "pro_prive")) {
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
                <a class="tag" href="search.php?search=<?= str_replace("_", "+", $tag["nomtag"]) ?>"><?php echo htmlspecialchars(str_replace("_", " ", ucfirst(strtolower($tag["nomtag"])))); ?></a>
            <?php }
        endforeach;

        if ($ouverture == "EstOuvert" && $typeOffer == "Spectacle") {
            ?>
            <a class="ouvert" href="search.php?search=ouvert">En Cours</a>
        <?php
        }else if($ouverture == "EstOuvert"){
        ?>
            <a class="ouvert" href="search.php?search=ouvert">Ouvert</a>
        <?php
        } else{
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


        if ($result[0]['ville'] && $result[0]['pays'] && $result[0]['codepostal']) {
        ?>
            <div>
                <img src="./img/icone/lieu.png">
                <a href="https://www.google.com/maps?q=<?php echo urlencode($result[0]["numerorue"] . " " . $result[0]["rue"] . ", " . $result[0]["codepostal"] . " " . $result[0]["ville"]); ?>" target="_blank" id="lieu"><?php echo htmlspecialchars($result[0]["numerorue"] . " " . $result[0]["rue"] . ", " . $result[0]["codepostal"] . " " . $result[0]["ville"]); ?></a>
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
    <!-- Carte Google Maps -->
    <div id="afficheLoc">
        <div id="carte"></div>
        <div id="contact-info">
            <?php
            if ($result[0]['ville'] && $result[0]['codepostal'] && $result[0]['pays']) {
            ?>
                <div>
                    <img src="./img/icone/lieu.png">
                    <a href="https://www.google.com/maps?q=<?php echo urlencode($result[0]["numerorue"] . " " . $result[0]["rue"] . ", " . $result[0]["codepostal"] . " " . $result[0]["ville"]); ?>" target="_blank" id="lieu"><?php echo htmlspecialchars($result[0]["numerorue"] . " " . $result[0]["rue"] . ", " . $result[0]["codepostal"] . " " . $result[0]["ville"]); ?></a>
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
    </div>


    <?php
    if ($typeOffer == "Parc Attraction") {
        if($result[0]['urlplan']){
        ?>
            <img src="<?php echo $result[0]["urlplan"] ?>">
        <?php
        }
        
        
    } else if($typeOffer == "Restaurant"){
        $stmt = $conn -> prepare("SELECT * from pact._menu where idoffre = $idOffre");
        $stmt -> execute();
        $menus = $stmt -> fetchAll(PDO::FETCH_ASSOC);
    ?>
        <p>Menu</p>
        <div class="swiper-container menu-container">
            <div class="swiper menu">
                <div class="swiper-wrapper">
                    <?php
                    foreach ($menus as $menu) {
                    ?>
                        <div class="swiper-slide">
                            <img src="<?php echo $menu['menu']; ?>" />
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>

        </div>
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

            <div>
            <?php
            if($avis){
                require_once __DIR__ . "/components/avis/avisMembre.php";
            }else{
            ?>
                <p>Aucun avis pour le moment, soyez le premier à donner le vôtre !</p> 
            <?php
            }
            
            ?>
            </div>
        <?php
    }
        ?>
        </div>
    </main>
    <?php
    require_once "./components/footer.php";
    ?>

<script>

    document.addEventListener('DOMContentLoaded', function () {
        const button1 = document.getElementById("button1");
        const button2 = document.getElementById("button2");
        const form1 = document.getElementById("formOpt1");
        const form2 = document.getElementById("formOpt2");

        if (button1 && form1) {
            // Ajouter un listener de clic au bouton
            button1.addEventListener("click", (event) => {
                event.preventDefault(); // Empêche l'action par défaut du bouton
                form1.submit(); // Soumet le formulaire correspondant
            });
        }

        if (button2 && form2) {
            // Ajouter un listener de clic au bouton
            button2.addEventListener("click", (event) => {
                event.preventDefault(); // Empêche l'action par défaut du bouton
                form2.submit(); // Soumet le formulaire correspondant
            });
        }


    const tabs = document.querySelectorAll('.tab');
    const contents = document.querySelectorAll('.contentPop');
    const trait = document.querySelector('.traitBouge'); // Trait qui se déplace

    // Fonction pour mettre à jour la position et la taille du trait sous les onglets
    function updateUnderline() {
        const activeTab = document.querySelector('.tab.active');
        const tabWidth = activeTab.offsetWidth;
        const tabOffset = activeTab.offsetLeft;
        trait.style.width = `40%`; // Ajuste la largeur du trait
        trait.style.transform = `translateX(${tabOffset}px)`; // Déplace le trait sous l'onglet actif
    }

    // Ajoute l'événement click sur chaque onglet
    tabs.forEach(tab => {
        tab.addEventListener('click', function () {
            const targetTab = this; // Onglet cliqué

            // Active l'onglet et désactive les autres
            tabs.forEach(t => t.classList.remove('active'));
            targetTab.classList.add('active');

            // Affiche le contenu associé et cache les autres
            const targetContent = document.getElementById(`content-${targetTab.dataset.tab}`);
            contents.forEach(content => {
                if (content === targetContent) {
                    content.classList.add('active');
                } else {
                    content.classList.remove('active');
                }
            });

            // Met à jour la position et la largeur du trait
            updateUnderline();
        });
    });

    // Initialiser le premier onglet comme actif
    updateUnderline(); // Met à jour la position du trait dès que la page est chargée
});



    const modal = document.getElementById("myModal");
    const openModalBtn = document.getElementById("openModalBtn");
    const closeModalBtn = document.querySelector(".close");
    const popupForm = document.getElementById("popupForm");
    const body = document.body;
    console.log("js1");
    // Fonction pour afficher le modal
    function openModal() {
    console.log("hop");
      modal.style.display = "block";
      body.classList.add("no-scroll");
    }
    console.log("js2");
    // Fonction pour fermer le modal
    function closeModal() {
      modal.style.display = "none";
      body.classList.remove("no-scroll");
    }
    console.log("js3");
    // Ouvrir le popup lorsque le bouton est cliqué
    openModalBtn.onclick = openModal;
    console.log("js4");
    // Fermer le popup lorsqu'on clique sur la croix
    closeModalBtn.onclick = closeModal;
    console.log("js5");
    // Fermer le popup lorsqu'on clique en dehors du contenu
    // window.onclick = function(event) {
    //   if (event.target === modal) {
    //     closeModal();
    //   }
    // }

    // Soumettre le formulaire
    function confirmation() {
      closeModal(); // Fermer la fenêtre modale après soumission
    }
    console.log("js6");
</script>
    <script>
        try {
            
        } catch (error) {

        }

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
        var swiper4 = new Swiper(".menu", {
            loop: true,
            slidesPerView: 3,
            spaceBetween: 10,
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