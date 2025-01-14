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

// Consulté récemment
if (isset($_SESSION["typeUser"]) && $_SESSION["typeUser"] == 'membre'){
    $stmt = $conn->prepare("SELECT * from pact._consulter where idu = ? and idoffre = ?");
    $stmt->execute([$_SESSION['idUser'], $idOffre]);
    $consultRecent = $stmt->fetch(PDO::FETCH_ASSOC);
    // Si Une consultation récente existe on la modifie
    if ($consultRecent) {
        $stmt = $conn->prepare("UPDATE pact._consulter set dateconsultation = CURRENT_TIMESTAMP where idu = ? and idoffre = ?");
        $stmt->execute([$_SESSION['idUser'], $idOffre]);
        $consultRecent = $stmt->fetch(PDO::FETCH_ASSOC);

    // Sinon on la créer
    } else {
        $stmt = $conn->prepare("INSERT INTO pact._consulter (idu, idoffre, dateconsultation) values (?, ?, CURRENT_TIMESTAMP)");
        $stmt->execute([$_SESSION['idUser'], $idOffre]);
        $consultRecent = $stmt->fetch(PDO::FETCH_ASSOC);
    }
} elseif (!isset($_SESSION["typeUser"])) {
    $_SESSION["recent"][] = $idOffre;
    $_SESSION["recent"] = array_slice($_SESSION["recent"], -10);
}

$stmt = $conn->prepare("SELECT * FROM pact.offres WHERE idoffre = :idoffre");
$stmt->bindParam(':idoffre', $idOffre);
$stmt->execute();
$offre = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Rechercher l'offre dans les parcs d'attractions
$stmt = $conn->prepare("SELECT * FROM pact.offrescomplete WHERE idoffre = :idoffre");
$stmt->bindParam(':idoffre', $idOffre);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$result) {
?>
    <form id="manageOfferAuto" action="manageOffer.php" method="post">
        <input type="hidden" name="idOffre" value="<?php echo $idOffre ?>">
        <input type="hidden" name="page" value="2">
    </form>
    <script>
        document.getElementById("manageOfferAuto").submit();
    </script>
<?php

} else {
    $typeOffer = $result[0]['categorie'];
}

$monOffre = new ArrayOffer($idOffre);
$ouverture = $monOffre->getArray()[$idOffre]["ouverture"];

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
    if ($result) {
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

        if ($result[0]['listehoraireprecise']) {
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

function formatDateEnFrancais(DateTime $date)
{
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

?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        <?php if (isset($_POST['popup'])): ?>
            <?php if (!empty($_POST['error'])): ?> // Vérifie que 'error' est défini et non vide
                alert("Erreur : la plage de date chevauche la plage de date d'une autre option");
            <?php endif; ?>
            openModal(); // Appelle la fonction openModal si la condition PHP est vraie
        <?php endif; ?>
    });
</script>

<?php


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
    r.idc_reponse,
    r.denomination AS reponse_denomination,
    r.contenureponse,
    r.nblikepro,
    r.nbdislikepro as likeReponse,
    r.reponsedate as dislikeReponse,
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

            <section class="info">
                <section class="tema">
                    <p class="infoP">Informations de l'offre</p>

                    <?php
                    if (($typeUser == "pro_public" || $typeUser == "pro_prive")) {
                    ?>
                        <section>
                            <p class="Enligne infoP StatutAffiche <?php echo $offre[0]['statut'] == 'actif' ? "" : "horslgnOffre" ?>"><?php echo $offre[0]['statut'] == 'actif' ? "En Ligne" : "Hors Ligne" ?></p>
                        </section>
                </section>

                <div class="buttonDetails">
                    <form class="taille6" method="post" action="changer_statut.php">
                        <!-- Envoyer l'ID de l'offre pour pouvoir changer son statut -->
                        <input type="hidden" name="offre_id" value="<?php echo $offre[0]['idoffre']; ?>">
                        <input type="hidden" name="nouveau_statut" value="<?php echo $offre[0]['statut'] === 'inactif' ? 'actif' : 'inactif'; ?>">
                        <button class="modifierBut" type="submit">
                            <?php echo $offre[0]['statut'] === 'inactif' ? 'Mettre en ligne' : 'Mettre hors ligne'; ?>
                        </button>
                    </form>
                    <div class="form-container">
                        <form class="taille6" method="post" action="manageOffer.php">
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
                    <section class="taille6">
                        <button id="openModalBtn" class="modifierBut">Gérer mes options</button>
                    </section>
                <?php
                    }

                ?>
                </div>
            </section>
            <section id="myModal" class="modal">
                <section class="modal-content">
                    <span class="close">&times;</span>

                    <!-- Titres des onglets -->
                    <section class="titre">
                        <p class="tab active" data-tab="1">Gestion des options</p>
                        <p class="tab" data-tab="2">Ajouter une option</p>
                        <!-- Trait qui se déplace sous les onglets -->
                        <section class="traitBouge"></section>
                    </section>
                    <section class="contentPop active" id="content-1">
                        <?php
                        $option = $conn->prepare("SELECT * FROM pact.option WHERE idoffre=? and (datefin>CURRENT_DATE OR datefin is null) and nomoption = 'ALaUne' ORDER BY datefin ASC");
                        $option->execute([$idOffre]);
                        $optionUne = $option->fetchAll(PDO::FETCH_ASSOC);
                        $option = $conn->prepare("SELECT * FROM pact.option WHERE idoffre=? and (datefin>CURRENT_DATE OR datefin is null) and nomoption = 'EnRelief' ORDER BY datefin ASC");
                        $option->execute([$idOffre]);
                        $optionRelief = $option->fetchAll(PDO::FETCH_ASSOC);
                        $mesOtion = [];
                        if ($optionRelief) {
                            foreach ($optionRelief as $key => $value) {
                                $mesOtion[] = $value;
                            }
                        }
                        if ($optionUne) {
                            foreach ($optionUne as $key => $value) {
                                $mesOtion[] = $value;
                            }
                        }
                        if ($mesOtion != []) {
                        ?>
                            <strong>
                                <p class="taille3">Mes options : </p>
                            </strong>
                            <ul>
                                <?php
                                foreach ($mesOtion as $key => $value) {
                                ?>
                                    <li>
                                        <section class="popUpOption">
                                            <?php
                                            if ($value['datefin'] != null) {
                                                $nom = $value['nomoption'] == 'ALaUne' ? "A la une" : "En relief";
                                                $dateActuelle = new DateTime();
                                                $dateDeb = new DateTime($value['datelancement']);
                                                if ($dateActuelle < $dateDeb) {
                                                    $dureeAvDeb = $dateActuelle->diff($dateDeb);
                                            ?><p><?php echo "Option en attente : " . $nom . " commence dans " . $dureeAvDeb->days . " jours pour " . $value['duree_total'] * 7 . " jours." ?></p>
                                                    <form class="confirmation-form" action="addOption.php" method="post">
                                                        <input type="hidden" name="type" value="resilier">
                                                        <input type="hidden" name="idOffre" value="<?php echo $idOffre ?>">
                                                        <input type="hidden" name="idoption" value="<?php echo $value['idoption'] ?>">
                                                        <button class="modifierBut">Résilier</button>
                                                    </form>
                                                <?php
                                                } else {
                                                    $dateFin = new DateTime($value['datefin']);
                                                    $dureeRestante = $dateActuelle->diff($dateFin);
                                                ?><p><?php echo "Option en cours : " . $nom . " prends fin dans " . $dureeRestante->days . " jours." ?></p>
                                                    <form class="confirmation-form-arr" action="addOption.php" method="post">
                                                        <input type="hidden" name="type" value="arreter">
                                                        <input type="hidden" name="idOffre" value="<?php echo $idOffre ?>">
                                                        <input type="hidden" name="nom" value="<?php echo $value['nomoption'] ?>">
                                                        <input type="hidden" name="idoption" value="<?php echo $value['idoption'] ?>">
                                                        <button class="modifierBut">Arrêter</button>
                                                    </form>
                                                <?php
                                                }
                                            } else {
                                                $nom = $value['nomoption'] == 'ALaUne' ? "A la une" : "En relief";
                                                ?><p><?php echo "Option en attente : " . $nom . " Commencera lors de la prochaine mise en ligne pour " . $value['duree_total'] * 7 . " jours." ?></p>
                                                <form class="confirmation-form" id="formOpt3" action="addOption.php" method="post">
                                                    <input type="hidden" name="type" value="resilier">
                                                    <input type="hidden" name="idOffre" value="<?php echo $idOffre ?>">
                                                    <input type="hidden" name="idoption" value="<?php echo $value['idoption'] ?>">
                                                    <button type="submit" class="modifierBut">Résilier</button>
                                                </form>
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
                            <strong>
                                <p class="taille3">Aucune option activé</p>
                            </strong>
                        <?php
                        }


                        ?>
                    </section>
                    <section class="contentPop" id="content-2">
                        <section class="AlaUne">
                            <section class="donnee">
                                <aside>
                                    <strong>
                                        <p class="taille3">A la Une</p>
                                        <p id="totalPrice" class="taille7">Prix total : 20€</p>
                                    </strong>
                                    <form class="formopt" id="formOpt1" action="addOption.php" method="post">
                                        <input type="hidden" name="nomOption" value="ALaUne">
                                        <input type="hidden" name="idOffre" value="<?php echo $idOffre ?>">
                                        <input type="hidden" name="type" value="ajout">
                                        <label class="taille" for="nbWeek">Nombre de semaine à la Une
                                            <input class="taille2" type="number" name="nbWeek" id="nbWeekALaUne" min="1" max="4" value="1">
                                        </label>
                                        <!-- Date picker (caché par défaut) -->
                                        <input class="datePicker" min="<?php echo date('Y-m-d') ?>" value="<?php echo date('Y-m-d') ?>" type="date" name="customDate" id="customDate1">
                                    </form>
                                    <p class="taille4 toggleMessage">*L'option sera lancée à la date de lancement choisie</p>
                                </aside>
                                <section class="sectionBtn">
                                    <button id="button1" class="modifierBut <?php echo count($optionUne) >= 2 ? 'disabled' : ''; ?>"
                                        <?php if (count($optionUne) >= 2) {
                                        ?>
                                        disabled
                                        onmouseover="showMessageAdd(event)"
                                        onmouseout="hideMessageAdd(event)"
                                        onclick="return false;"

                                        <?php
                                        } ?>>
                                        Ajouter
                                    </button>
                                </section>
                            </section>
                        </section>

                        <section class="EnRelief">
                            <section class="donnee">
                                <aside>
                                    <strong>
                                        <p class="taille3">En Relief</p>
                                        <p id="totalPrice2" class="taille7">Prix total : 10€</p>
                                    </strong>
                                    <form class="formopt" id="formOpt2" action="addOption.php" method="post">
                                        <input type="hidden" name="nomOption" value="EnRelief">
                                        <input type="hidden" name="idOffre" value="<?php echo $idOffre ?>">
                                        <input type="hidden" name="type" value="ajout">
                                        <label class="taille" for="nbWeek">Nombre de semaine en Relief
                                            <input class="taille2" type="number" name="nbWeek" id="nbWeekEnRelief" min="1" max="4" value="1">
                                        </label>
                                        <!-- Date picker (caché par défaut) -->
                                        <input class="datePicker" min="<?php echo date('Y-m-d') ?>" value="<?php echo date('Y-m-d') ?>" type="date" name="customDate" id="customDate2">
                                    </form>
                                    <p class="taille4 toggleMessage">*L'option sera lancée à la date de lancement choisie</p>
                                </aside>
                                <section class="sectionBtn">
                                    <button id="button2" class="modifierBut <?php echo count($optionRelief) >= 2 ? 'disabled' : ''; ?>"
                                        <?php if (count($optionRelief) >= 2) {
                                        ?>
                                        disabled
                                        onclick="return false;"
                                        onmouseover="showMessageAdd(event)"
                                        onmouseout="hideMessageAdd(event)"
                                        <?php
                                        } ?>>
                                        Ajouter
                                    </button>
                                </section>
                            </section>
                        </section>
                        <section id="hoverMessageAdd" class="hover-message">Vous avez trop de d'option en attente (1 option en attente et 1 en cour maximun par option)</section>
                    </section>
                    <section class="taillebtn">
                        <button class="modifierBut " onclick="confirmation()">Quitter</button>
                    </section>
                </section>
            </section>
            <section class="traitDtOf"></section>
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

                        if ($result[0]['categorie'] == "Restaurant") {
                            array_push($tags, ['nomtag' => $result[0]['gammedeprix']]);
                        }
                        foreach ($tags as $tag):
                            if ($tag["nomtag"] != NULL) {
                        ?>
                                <a class="tag" href="index.php?search=<?= str_replace("_", "+", $tag["nomtag"]) ?>#searchIndex"><?php echo htmlspecialchars(str_replace("_", " ", ucfirst(strtolower($tag["nomtag"])))); ?></a>
                            <?php }
                        endforeach;

                        if ($ouverture == "EstOuvert" && $typeOffer == "Spectacle") {
                            ?>
                            <a class="ouvert" href="index.php?search=ouvert#searchIndex">En Cours</a>
                        <?php
                        } else if ($ouverture == "EstOuvert") {
                        ?>
                            <a class="ouvert" href="index.php?search=ouvert#searchIndex">Ouvert</a>
                        <?php
                        } else {
                        ?>
                            <a class="ferme" href="index.php?search=ferme#searchIndex">Fermé</a>
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

                    <div class="swiper-container detailSwiper">
                        <div class="swiper mySwiper">
                            <div class="swiper-wrapper">
                                <?php
                                foreach ($photos as $picture) {
                                ?>
                                    <div class="swiper-slide imageSwiper">
                                        <img src="<?php echo $picture['url']; ?>" />
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>

                        <div class="swiper-button-next kylian"></div>
                        <div class="swiper-button-prev kylian"></div>
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



                    <section id="infoComp">
                        <h2>Informations Complémentaires</h2>
                        <?php
                        if ($typeOffer == "Visite") {
                            $stmt = $conn->prepare("SELECT * from pact.visites where idoffre = $idOffre");
                            $stmt->execute();
                            $visite = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                            <div>
                                <p>Durée : <?= convertionMinuteHeure($visite[0]['duree']) ?></p>
                                <p>Visite guidée : <?= isset($visite[0]["guide"]) ? "Oui" : "Non" ?></p>
                                <?php
                                if ($visite[0]["guide"]) {
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
                        } else if ($typeOffer == "Spectacle") {
                            $stmt = $conn->prepare("SELECT * from pact.spectacles where idoffre = $idOffre");
                            $stmt->execute();
                            $spectacle = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                            <div>
                                <p>Durée : <?= convertionMinuteHeure($spectacle[0]['duree']) ?></p>
                                <p>Nombre de places : <?= $spectacle[0]['nbplace'] ?></p>
                            </div>
                        <?php
                        } else if ($typeOffer == "Activité" || $typeOffer == "Parc Attraction") {
                            if ($typeOffer == "Activité") {
                                $stmt = $conn->prepare("SELECT * from pact.activites where idoffre = $idOffre");
                            } else {
                                $stmt = $conn->prepare("SELECT * from pact.parcs_attractions where idoffre = $idOffre");
                            }
                            $stmt->execute();
                            $theme = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                            <div>
                                <p>Âge minimum : <?= $theme[0]['agemin'] ?> ans</p>
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
                                if ($result[0]['categorie'] == 'Spectacle' || $result[0]['categorie'] == 'Activité') {
                                    $horaireSpectacle = [];
                                    if ($schedules['spectacle']) {
                                        usort($schedules['spectacle'], function ($a, $b) {
                                            $dateA = new DateTime($a['dateRepresentation']);
                                            $dateB = new DateTime($b['dateRepresentation']);
                                            return $dateA <=> $dateB; // Trier du plus récent au plus ancien
                                        });

                                        foreach ($schedules['spectacle'] as $spec) {
                                            $dateSpectacle = new DateTime($spec['dateRepresentation']);
                                ?>
                                            <tr>
                                                <td class="jourSemaine"><?= ucwords(formatDateEnFrancais($dateSpectacle)) ?></td>
                                                <td>
                                                    <?php
                                                    echo "à " . str_replace("=>", ":", $spec['heureOuverture']);
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php

                                        }
                                    }
                                } else {
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
                                                    $horairesAffichage[] = htmlspecialchars(str_replace("=>", ":", $horaireMidi['heureOuverture'])) . " à " . htmlspecialchars(str_replace("=>", ":", $horaireMidi['heureFermeture']));
                                                }
                                                if (!empty($horaireSoir)) {
                                                    $horaireSoir = current($horaireSoir); // Prendre le premier élément du tableau filtré
                                                    $horairesAffichage[] = htmlspecialchars(str_replace("=>", ":", $horaireSoir['heureOuverture'])) . " à " . htmlspecialchars(str_replace("=>", ":", $horaireSoir['heureFermeture']));
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
                        if ($result[0]['urlplan']) {
                    ?>
                            <div class="planParc">
                                <h2>Plan du parc :</h2>
                                <div>
                                    <img src="<?php echo $result[0]["urlplan"] ?>">
                                </div>
                            </div>
                        <?php
                        }
                    } else if ($typeOffer == "Restaurant") {
                        $stmt = $conn->prepare("SELECT * from pact._menu where idoffre = $idOffre");
                        $stmt->execute();
                        $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if ($menus) {
                        ?>

                            <div class="divMenu">
                                <h2>Menu :</h2>
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
                            </div>
                        <?php

                        }
                    }
                    if ($typeUser === "pro_prive" || $typeUser === "pro_public") {
                        require_once __DIR__ . "/components/avis/avisPro.php";
                    } else {
                        ?> <div class="avis">
                            <nav id="tab-container">
                                <h3 id="tab-avis">Avis</h3>
                                <h3 id="tab-publiez">Publiez un avis</h3>
                            </nav>

                            <div id="avis-section">
                                <!-- Contenu chargé dynamiquement -->
                                <div id="avis-component" style="display: flex;">
                                    <?php require_once __DIR__ . "/components/avis/avisMembre.php"; ?>
                                </div>
                                <div id="publiez-component" style="display: none;">
                                    <?php
                                    if ($isLoggedIn) {
                                        $stmt = $conn->prepare("SELECT * FROM pact.avis a JOIN pact._membre m ON a.pseudo = m.pseudo WHERE idoffre = ? AND idu = ?");
                                        $stmt->execute([$idOffre, $idUser]);
                                        $existingReview = $stmt->fetch();

                                        if ($existingReview) {
                                            // L'utilisateur a déjà laissé un avis pour cette offre
                                            echo '<p>Vous avez déjà laissé un avis pour cette offre. Veuillez supprimer le précedent avant de pouvoir en ecrire un autre</p>';
                                        } else {
                                            require_once __DIR__ . "/components/avis/ecrireAvis.php";
                                        }
                                    } else {
                                    ?>
                                        <p>Vous devez être connecté pour écrire un avis. <a href="login.php">Connectez-vous ici</a></p>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>

                            <!-- Pop-up de signalement d'un avis -->
                            <section class="modal">
                                <section class="modal-content">
                                    <span class="close">&times;</span>
                                    <h2>Signalement d'un avis</h2>
                                    <form action="signaleAvis.php">
                                        <input type="radio" name="signalement" id="inapproprie">
                                        <label for="">Contenu inapproprié (injures, menaces, contenu explicite...)</label>
                                        <input type="radio" name="signalement" id="frauduleux">
                                        <label for="">Avis frauduleux ou trompeur (faux avis, publicité déguisée...) </label>
                                        <input type="radio" name="signalement" id="spam">
                                        <label for="">Spam ou contenu hors-sujet (multipostage, indésirable...)</label>
                                        <input type="radio" name="signalement" id="violation">
                                        <label for="">Violation des règles de la plateforme (données personnelles...)</label>
                                        <textarea name="motifSignalement" id="motifSignalement" maxlength="499" placeholder="Si vous le souhaitez, détaillez la raison de ce signalement"></textarea>
                                        <input type="submit" value="confirmeSignelement" id="confirmBtn">
                                    </form>
                                </section>
                            </section>


                        </div>
                    <?php
                    }
                    ?>
    </main>
    <?php
    require_once "./components/footer.php";
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const nbWeekInput = document.getElementById('nbWeekALaUne');
            const totalPriceElement = document.getElementById('totalPrice');
            const pricePerWeek = 20; // Prix par semaine

            function updatePrice() {
                const nbWeeks = parseInt(nbWeekInput.value) || 0; // Récupère la valeur ou 0 si vide
                const totalPrice = nbWeeks * pricePerWeek;
                totalPriceElement.textContent = `Prix total : ${totalPrice}€`;
            }

            // Mise à jour initiale
            updatePrice();

            // Ajout d'un écouteur d'événement sur le champ de saisie
            nbWeekInput.addEventListener('input', updatePrice);

            const nbWeekInput2 = document.getElementById('nbWeekEnRelief');
            const totalPriceElement2 = document.getElementById('totalPrice2');
            const pricePerWeek2 = 10; // Prix par semaine

            function updatePrice2() {
                const nbWeeks2 = parseInt(nbWeekInput2.value) || 0; // Récupère la valeur ou 0 si vide
                const totalPrice2 = nbWeeks2 * pricePerWeek2;
                totalPriceElement2.textContent = `Prix total : ${totalPrice2}€`;
            }

            // Mise à jour initiale
            updatePrice2();

            // Ajout d'un écouteur d'événement sur le champ de saisie
            nbWeekInput2.addEventListener('input', updatePrice2);

            const forms = document.querySelectorAll('.confirmation-form');
            forms.forEach(form => {
                form.addEventListener('submit', (event) => {
                    const confirmation = confirm("Êtes-vous sûr de vouloir resilier cette option ?\nVous ne serez pas facturé pour cette option");
                    if (!confirmation) {
                        event.preventDefault(); // Empêche la soumission si l'utilisateur annule
                    }
                });
            });

            const forms2 = document.querySelectorAll('.confirmation-form-arr');
            forms2.forEach(form => {
                form.addEventListener('submit', (event) => {
                    const confirmation = confirm("Êtes-vous sûr de vouloir arrêter cette option ?\nVous serez facturé pour toutes les semaines");
                    if (!confirmation) {
                        event.preventDefault(); // Empêche la soumission si l'utilisateur annule
                    }
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const button1 = document.getElementById("button1");
            const button2 = document.getElementById("button2");
            const form1 = document.getElementById("formOpt1");
            const form2 = document.getElementById("formOpt2");

            if (button1 && form1) {
                // Ajouter un listener de clic au bouton
                button1.addEventListener("click", (event) => {
                    const confirmation = confirm("Êtes-vous sûr de vouloir ajouter cette option ?\nVous serez facturé pour toutes les options en cours, sauf si arrêté le jour du lancement");
                    if (confirmation) {
                        event.preventDefault(); // Empêche l'action par défaut du bouton
                        form1.submit(); // Soumet le formulaire correspondant
                    }
                });
            }

            if (button2 && form2) {
                // Ajouter un listener de clic au bouton
                button2.addEventListener("click", (event) => {
                    const confirmation = confirm("Êtes-vous sûr de vouloir ajouter cette option ?\nVous serez facturé pour toutes les options en cours, sauf si arrêté le jour du lancement");
                    if (confirmation) {
                        event.preventDefault(); // Empêche l'action par défaut du bouton
                        form2.submit(); // Soumet le formulaire correspondant
                    }
                });
            }

            // const toggles = document.querySelectorAll(".datePickerToggle");

            // toggles.forEach(toggle => {
            //     toggle.addEventListener("change", function() {
            //         const form = this.closest("form");
            //         const datePicker = form.querySelector(".datePicker");
            //         const toggleMessage = form.closest("aside").querySelector(".toggleMessage");

            //         if (this.checked) {
            //             datePicker.style.display = "block"; // Affiche le date picker
            //             if (toggleMessage) {
            //                 toggleMessage.style.display = "none"; // Cache le message
            //             }
            //         } else {
            //             datePicker.style.display = "none"; // Cache le date picker
            //             if (toggleMessage) {
            //                 toggleMessage.style.display = "block"; // Affiche à nouveau le message
            //             }
            //         }
            //     });
            // });

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
                tab.addEventListener('click', function() {
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
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDYU5lrDiXzchFgSAijLbonudgJaCfXrRE&callback=initMap" async defer></script>


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
        var swiper3 = new Swiper(".mySwiperAvis", {
            loop: true,
            autoplay: {
                delay: 5000,
            },
            slidesPerView: 1,
            spaceBetween: 10,
            pagination: {
                el: ".swiper-pagination",
                dynamicBullets: true,
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

        function showMessageAdd(event) {
            const message = document.getElementById('hoverMessageAdd');
            message.style.display = 'block';
        }

        // Fonction pour masquer le message
        function hideMessageAdd(event) {
            const message = document.getElementById('hoverMessageAdd');
            message.style.display = 'none';
        }


        try {
            document.getElementById('tab-avis').addEventListener('click', function() {
                document.getElementById('tab-avis').classList.add('selected');
                document.getElementById('tab-publiez').classList.remove('selected');
            });

            document.getElementById('tab-publiez').addEventListener('click', function() {
                document.getElementById('tab-publiez').classList.add('selected');
                document.getElementById('tab-avis').classList.remove('selected');
            });

            /** Charger les composants */
            document.addEventListener("DOMContentLoaded", () => {
                const tabAvis = document.getElementById("tab-avis");
                const tabPubliez = document.getElementById("tab-publiez");
                const avisComponent = document.getElementById("avis-component");
                const publiezComponent = document.getElementById("publiez-component");

                // Activer l'onglet "Avis"
                tabAvis.addEventListener("click", () => {
                    tabAvis.classList.add("active");
                    tabPubliez.classList.remove("active");

                    // Afficher le composant des avis
                    avisComponent.style.display = "flex";
                    publiezComponent.style.display = "none";
                });

                // Activer l'onglet "Publiez un avis"
                tabPubliez.addEventListener("click", () => {
                    tabPubliez.classList.add("active");
                    tabAvis.classList.remove("active");

                    // Afficher le composant pour écrire un avis
                    publiezComponent.style.display = "flex";
                    avisComponent.style.display = "none";
                });
            });

            /** fin script chargement composant */
        } catch {}


        //Script de gestion du pop-up de signalement
        const ouvrePopup = document.getElementById('signalerAvis');
        const popup = document.querySelector('.avis > .modal');
        const btnFermer = document.getElementsByClassName('.avis > .modal .close');
        const btnConfirmer = document.getElementById('confirmBtn');

        // Afficher le pop-up
        ouvrePopup.addEventListener('click', () => {
            popup.style.display = 'block';
        });

        // Masquer le pop-up lorsque l'on clique sur le bouton de fermeture
        btnFermer.addEventListener('click', () => {
            popup.style.display = 'none';
        });

        // Masquer le pop-up après confirmation
        btnConfirmer.addEventListener('click', () => {
            alert('Signalement enregistré');
            popup.style.display = 'none';
        });

        // Masquer le pop-up si on clique en dehors de son contenu
        window.addEventListener('click', (event) => {
            if (event.target === popup) {
                popup.style.display = 'none';
            }
        });
    </script>
    <script src="js/setColor.js"></script>
</body>

</html>