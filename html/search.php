<?php 
require_once "config.php";


    // Récupérer l'heure actuelle et le jour actuel
setlocale(LC_TIME, 'fr_FR.UTF-8');

date_default_timezone_set('Europe/Paris');

// Récupérer le jour actuel en français avec la classe DateTime
$currentDay = (new DateTime())->format('l'); // Récupère le jour en anglais

// Tableau pour convertir les jours de la semaine de l'anglais au français
$daysOfWeek = [
    'Monday'    => 'Lundi',
    'Tuesday'   => 'Mardi',
    'Wednesday' => 'Mercredi',
    'Thursday'  => 'Jeudi',
    'Friday'    => 'Vendredi',
    'Saturday'  => 'Samedi',
    'Sunday'    => 'Dimanche'
];

// Convertir le jour actuel en français
$currentDay = $daysOfWeek[$currentDay];
$currentTime = new DateTime(date('H:i')); // ex: 14:30

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche d'offre</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="img/logo.png" type="image/x-icon">
</head>
<body id="search">
    <script src="js/setColor.js"></script>
    <?php require_once "components/header.php"; ?>
    <main class="search">
        <section id="tri&filtre" class="asdTriFiltre">
            <aside id="tri">
                <h2>Trier</h2>
                <div class="blcTriFiltre">
                    <div>
                        <label for="">Mise en avant</label>
                        <input type="radio" name="" id="">
                    </div>
                    <div>
                        <h3>Par note&nbsp;:&nbsp;</h3>
                        <label for="">Ordre croissant</label>
                        <input type="radio" name="" id="">
                        <label for="">Ordre décroissant</label>
                        <input type="radio" name="" id="">
                    </div>
                    <div>
                        <h3>Par prix&nbsp;:&nbsp;</h3>
                        <label for="">Ordre croissant</label>
                        <input type="radio" name="" id="">
                        <label for="">Ordre décroissant</label>
                        <input type="radio" name="" id="">
                    </div>
                    <div>
                        <h3>Par date&nbsp;:&nbsp;</h3>
                        <label for="">Plus récent</label>
                        <input type="radio" name="" id="">
                        <label for="">Plus ancien</label>
                        <input type="radio" name="" id="">
                    </div>
                </div>
            </aside>
            <aside id="filtre" class="asdTriFiltre">
                <h2>Filtres de recherche</h2>
                <div class="blcTriFiltre">
                    <div>
                        <h3>Par note</h3>
                        <label for="">
                            <div class="star"></div>
                            <div class="star"></div>
                            ou +
                        </label>
                        <input type="radio" name="" id="">
                        <label for="">
                            <div class="star"></div>
                            <div class="star"></div>
                            <div class="star"></div>
                            ou +
                        </label>
                        <input type="radio" name="" id="">
                        <label for="">
                            <div class="star"></div>
                            <div class="star"></div>
                            <div class="star"></div>
                            <div class="star"></div>
                            ou +
                        </label>
                        <input type="radio" name="" id="">
                        <label for="">
                            <div class="star"></div>
                            <div class="star"></div>
                            <div class="star"></div>
                            <div class="star"></div>
                            <div class="star"></div>
                        </label>
                        <input type="radio" name="" id="">
                    </div>
                    <div>
                        <h3>Par prix</h3>
                        <div>
                            <label for="">De</label>
                            <select name="" id="">
                                <option value="0" default>0€</option>
                                <option value="25">25€</option>
                                <option value="50">50€</option>
                                <option value="75">75€</option>
                                <option value="100">100€</option>
                                <option value="125">125€</option>
                                <option value="150">150€</option>
                                <option value="175">175€</option>
                                <option value="200">200€ et +</option>
                            </select>
                        </div>
                        <div>
                            <label for="">à</label>
                            <select name="" id="">
                            <option value="0">0€</option>
                                <option value="25">25€</option>
                                <option value="50">50€</option>
                                <option value="75">75€</option>
                                <option value="100" default>100€</option>
                                <option value="125">125€</option>
                                <option value="150">150€</option>
                                <option value="175">175€</option>
                                <option value="200">200€ et +</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <h3>Par Statut</h3>
                        <div>
                            <label for="">Ouvert</label>
                            <input type="radio" name="" id="">
                        </div>
                        <div>
                            <label for="">Fermé</label>
                            <input type="radio" name="" id="">
                        </div>
                    </div>
                    <div>
                        <h3>Par catégorie</h3>
                    </div>
                    <div>
                        <h3>Par date</h3>
                        <div>
                            <label for="dateDepart">Départ&nbsp;:&nbsp;</label>
                            <input type="date" name="dateDepart" id="dateDepart" value="" min="">
                            <input type="time" name="" id="">
                        </div>
                        <div>
                            <label for="dateDepart">Fin&nbsp;:&nbsp;</label>
                            <input type="date" name="dateFin" id="dateFin" value="" min="">
                            <input type="time" name="" id="">
                        </div>
                    </div>
                </div>
            </aside>
        </section>
        <?php 

        $stmt = $conn->prepare("SELECT * FROM pact.offres");
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        print_r($results[0]);

        if (($typeUser == "pro_public" || $typeUser == "pro_prive")) {
            $idutilisateur=$_SESSION["idUser"];
            
            
            ?>
            <section class="searchoffre">
            <?php if ($results){ ?>
                <?php foreach ($results as $offre){ 
                    $idOffre = $offre['idoffre'];
                    $iduser= $offre['idu'];
                    $nomOffre = $offre['nom'];
                    $resume= $offre['resume'];
                    $noteAvg = "Non noté";
    
                    // Requête pour récupérer l'image de l'offre
                    $img = $conn->prepare("SELECT * FROM pact._illustre WHERE idoffre = $idOffre ORDER BY url ASC");
                    $img->execute();
                    $urlImg = $img->fetchAll(PDO::FETCH_ASSOC);
    
                    // Requête pour récupérer les horaires du soir
                    $stmt = $conn->prepare("SELECT * FROM pact._horairesoir WHERE idoffre = $idOffre");
                    $stmt->execute();
                    $resultsSoir = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
                    // Requête pour récupérer les horaires du midi
                    $stmt = $conn->prepare("SELECT * FROM pact._horairemidi WHERE idoffre = $idOffre");
                    $stmt->execute();
                    $resultsMidi = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
                    // Fusionner les horaires midi et soir
                    $horaires = array_merge($resultsSoir, $resultsMidi);
                    $restaurantOuvert = "EstFermé"; // Par défaut, le restaurant est fermé
    
                    // Vérification de l'ouverture en fonction de l'heure actuelle et des horaires
                    foreach ($horaires as $horaire) {
                        if ($horaire['jour'] == $currentDay) {
                            $heureOuverture = DateTime::createFromFormat('H:i', $horaire['heureouverture']);
                            $heureFermeture = DateTime::createFromFormat('H:i', $horaire['heurefermeture']);
                            if ($currentTime >= $heureOuverture && $currentTime <= $heureFermeture) {
                                $restaurantOuvert = "EstOuvert";
                                break;
                            }
                        }
                    }
    
                    // Requête pour la localisation
                    $loca = $conn->prepare("SELECT * FROM pact._localisation WHERE idOffre = $idOffre");
                    $loca->execute();
                    $villes = $loca->fetchAll(PDO::FETCH_ASSOC);
                    $ville = ($villes)?$villes[0]['ville']:"Pas de localisation entrée";
    
                    // Requête pour la gamme de prix
                    $prix = $conn->prepare("SELECT * FROM pact.restaurants WHERE idOffre = $idOffre");
                    $prix->execute();
                    $gamme = $prix->fetchAll(PDO::FETCH_ASSOC);
                    $gammeText = ($gamme) ? " ⋅ " . $gamme[0]['gammedeprix'] : "";
    
                    // recuperation des tag pour chaque offre
    
                    $tagR = $conn->prepare("SELECT * FROM pact._tag_restaurant WHERE idoffre=$idOffre");
                    $tagR->execute();
                    $idTagR = $tagR->fetchAll(PDO::FETCH_ASSOC);
    
                    $tagV = $conn->prepare("SELECT * FROM pact._tag_visite WHERE idoffre=$idOffre");
                    $tagV->execute();
                    $idTagV = $tagV->fetchAll(PDO::FETCH_ASSOC);
    
                    $tagP = $conn->prepare("SELECT * FROM pact._tag_parc WHERE idoffre=$idOffre");
                    $tagP->execute();
                    $idTagP = $tagP->fetchAll(PDO::FETCH_ASSOC);
    
                    $tagA = $conn->prepare("SELECT * FROM pact._tag_act WHERE idoffre=$idOffre");
                    $tagA->execute();
                    $idTagA = $tagA->fetchAll(PDO::FETCH_ASSOC);
                    
                    $tagS = $conn->prepare("SELECT * FROM pact._tag_spec WHERE idoffre=$idOffre");
                    $tagS->execute();
                    $idTagS = $tagS->fetchAll(PDO::FETCH_ASSOC);
    
                    if ($idTagR) {
                        $tag=$idTagR;
                        $nomTag="Restaurant";
                    }elseif ($idTagV) {
                        $tag=$idTagV;
                        $nomTag="Visite";
                    }elseif ($idTagP) {
                        $tag=$idTagP;
                        $nomTag="Parc d'Attraction";
                    }elseif ($idTagA) {
                        $tag=$idTagA;
                        $nomTag="Activite";
                    }elseif ($idTagS) {
                        $tag=$idTagS;
                        $nomTag="Spectacle";
                    } else {
                        $tag=NULL;
                        $nomTag="Pas de categorie";
                    }

                     ?>
                    <a class="searchA" href="/detailsOffer.php?idoffre=<?php echo $idOffre; ?>&ouvert=<?php echo $restaurantOuvert; ?>">
                        <div class="carteOffre">
                                <?php 
                                $alt = isset($urlImg[0]['url']) && $urlImg[0]['url'] ? "photo_principal_de_l'offre" : "Pas_de_photo_attribué_à_l'offre";
                                ?>
                                <img class="searchImage" src="<?php echo $urlImg[0]['url']; ?>" alt=<?php echo $alt; ?>>
                            <div class="infoOffre">
                                <p class="searchTitre"><?php echo $nomOffre!=NULL?$nomOffre :"Pas de nom d'offre"; ?></p>

                                <strong><p class="villesearch"><?php echo $ville . $gammeText . " ⋅ " . $nomTag; ?></p></strong>

                                <div class="searchCategorie">
                                    <?php
                                    if ($tag!=NULL) {
                                        foreach ($tag as $value) {
                                            ?><span class="searchTag"><?php echo $value['nomtag']." " ?></span><?php
                                        }
                                    }
                                    ?>
                                </div>

                                <p class="searchResume"><?php echo $resume!=NULL?$resume:"Pas de resume saisie";?></p>

                                <section class="searchNote">
                                    <p><?php echo $noteAvg; ?></p>
                                
                                    <p id="couleur-<?php echo $idOffre; ?>" class="searchStatutO">
                                        <?php echo ($restaurantOuvert == "EstOuvert") ? "Ouvert" : "Fermé"; ?>
                                    </p>
                                </section>


                                <script>
                                    let st_<?php echo $idOffre; ?> = document.getElementById("couleur-<?php echo $idOffre; ?>");
                                    if ("<?php echo $restaurantOuvert; ?>" === "EstOuvert") {
                                        st_<?php echo $idOffre; ?>.classList.add("searchStatutO");
                                    } else {
                                        st_<?php echo $idOffre; ?>.classList.add("searchStatutF");
                                    }
                                </script>
                            </div>
                            <div class="searchAvis">
                                <p class="avisSearch">Les avis les plus récent :</p>
                                <p>Pas d'avis</p>
                            </div>
                        </div>
                    </a>
                <?php 
                } ?>
            <?php } else { ?>
                <p>Aucune offre trouvée </p>
            <?php } ?>
            </section>
        <?php
        }else {
            ?>
            <section class="searchoffre">
            <?php if ($results){ ?>
                <?php foreach ($results as $offre){ 
                    $idOffre = $offre['idoffre'];
                    $iduser= $offre['idu'];
                    $nomOffre = $offre['nom'];
                    $resume= $offre['resume'];
                    $noteAvg = "Non noté";
                    $urlImg=(explode(',',trim($offre['listimage'],'{}')))[0];
                    $horaireMidi=explode(',',trim($offre['listhorairemidi'],'{}'));
                    $horaireSoir=explode(',',trim($offre['listhorairesoir'],'{}'));
                    print_r(trim($offre['listhorairesoir'],'{}'));
                    echo "  xw wx  ";
                    print_r(trim($offre['listhorairemidi'],'{}'));

                    // Requête pour récupérer les horaires du soir
                    $stmt = $conn->prepare("SELECT * FROM pact._horairesoir WHERE idoffre = $idOffre");
                    $stmt->execute();
                    $resultsSoir = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
                    // Requête pour récupérer les horaires du midi
                    $stmt = $conn->prepare("SELECT * FROM pact._horairemidi WHERE idoffre = $idOffre");
                    $stmt->execute();
                    $resultsMidi = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
                    // Fusionner les horaires midi et soir
                    $horaires = array_merge($resultsSoir, $resultsMidi);
                    $restaurantOuvert = "EstFermé"; // Par défaut, le restaurant est fermé
    
                    // Vérification de l'ouverture en fonction de l'heure actuelle et des horaires
                    foreach ($horaires as $horaire) {
                        if ($horaire['jour'] == $currentDay) {
                            $heureOuverture = DateTime::createFromFormat('H:i', $horaire['heureouverture']);
                            $heureFermeture = DateTime::createFromFormat('H:i', $horaire['heurefermeture']);
                            if ($currentTime >= $heureOuverture && $currentTime <= $heureFermeture) {
                                $restaurantOuvert = "EstOuvert";
                                break;
                            }
                        }
                    }
    
                    // Requête pour la localisation
                    $loca = $conn->prepare("SELECT * FROM pact._localisation WHERE idOffre = $idOffre");
                    $loca->execute();
                    $ville = $loca->fetchAll(PDO::FETCH_ASSOC);
    
                    // Requête pour la gamme de prix
                    $prix = $conn->prepare("SELECT * FROM pact.restaurants WHERE idOffre = $idOffre");
                    $prix->execute();
                    $gamme = $prix->fetchAll(PDO::FETCH_ASSOC);
                    $gammeText = ($gamme) ? " ⋅ " . $gamme[0]['gammedeprix'] : "";
    
                    // recuperation des tag pour chaque offre
    
                    $tagR = $conn->prepare("SELECT * FROM pact._tag_restaurant WHERE idoffre=$idOffre");
                    $tagR->execute();
                    $idTagR = $tagR->fetchAll(PDO::FETCH_ASSOC);
    
                    $tagV = $conn->prepare("SELECT * FROM pact._tag_visite WHERE idoffre=$idOffre");
                    $tagV->execute();
                    $idTagV = $tagV->fetchAll(PDO::FETCH_ASSOC);
    
                    $tagP = $conn->prepare("SELECT * FROM pact._tag_parc WHERE idoffre=$idOffre");
                    $tagP->execute();
                    $idTagP = $tagP->fetchAll(PDO::FETCH_ASSOC);
    
                    $tagA = $conn->prepare("SELECT * FROM pact._tag_act WHERE idoffre=$idOffre");
                    $tagA->execute();
                    $idTagA = $tagA->fetchAll(PDO::FETCH_ASSOC);
                    
                    $tagS = $conn->prepare("SELECT * FROM pact._tag_spec WHERE idoffre=$idOffre");
                    $tagS->execute();
                    $idTagS = $tagS->fetchAll(PDO::FETCH_ASSOC);
    
                    if ($idTagR) {
                        $tag=$idTagR;
                        $nomTag="Restaurant";
                    }elseif ($idTagV) {
                        $tag=$idTagV;
                        $nomTag="Visite";
                    }elseif ($idTagP) {
                        $tag=$idTagP;
                        $nomTag="Parc d'Attraction";
                    }elseif ($idTagA) {
                        $tag=$idTagA;
                        $nomTag="Activite";
                    }else {
                        $tag=$idTagS;
                        $nomTag="Spectacle";
                    }
                    
                    if ($offre['statut'] == 'actif') { ?>
                        <a class="searchA" href="/detailsOffer.php?idoffre=<?php echo $idOffre; ?>&ouvert=<?php echo $restaurantOuvert; ?>">
                            <div class="carteOffre">
                                <img class="searchImage" src="<?php echo $urlImg; ?>" alt="photo principal de l'offre">
                                <div class="infoOffre">

                                    <p class="searchTitre"><?php echo $nomOffre; ?></p>

                                    <strong><p class="villesearch"><?php echo $ville[0]['ville'] . $gammeText . " ⋅ " .$nomTag; ?></p></strong>

                                    <div class="searchCategorie">
                                        <?php
                                        foreach ($tag as $value) {
                                            ?><span class="searchTag"><?php echo $value['nomtag']." " ?></span><?php
                                        }
                                        ?>
                                    </div>
                                    
                                    <p class="searchResume"><?php echo $resume;?></p>
                                    
                                    <section class="searchNote">
                                        <p class="avgNote"><?php echo $noteAvg; ?></p>
                                    
                                        <p id="couleur-<?php echo $idOffre; ?>" class="searchStatutO">
                                            <?php echo ($restaurantOuvert == "EstOuvert") ? "Ouvert" : "Fermé"; ?>
                                        </p>
                                    </section>
                                    
                                    
                                    <script>
                                        let st_<?php echo $idOffre; ?> = document.getElementById("couleur-<?php echo $idOffre; ?>");
                                        if ("<?php echo $restaurantOuvert; ?>" === "EstOuvert") {
                                            st_<?php echo $idOffre; ?>.classList.add("searchStatutO");
                                        } else {
                                            st_<?php echo $idOffre; ?>.classList.add("searchStatutF");
                                        }
                                    </script>
                                </div>
                                <div class="searchAvis">
                                    <p class="avisSearch">Les avis les plus récent :</p>
                                    <p>Pas d'avis</p>
                                </div>
                            </div>
                        </a>
                    <?php }
                } ?>
            <?php } else { ?>
                <p>Aucune offre trouvée </p>
            <?php } ?>
            </section>  
        <?php      
        } 
        ?>
        

    </main>
    <?php require_once "components/footer.php"; ?>
</body>
</html>