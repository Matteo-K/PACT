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
        <section id="trifiltre" class="asdTriFiltre">
            <div>
                <figure id="btnFiltre">
                    <figcaption>Filtrer</figcaption>
                    <img src="img/icone/burger-bar.png" alt="filtre">
                </figure>
                <figure id="btnTri">
                    <figcaption>Trier</figcaption>
                    <img src="img/icone/burger-bar.png" alt="tri">
                </figure>
            </div>
            <aside id="tri">
                <h2>Trier</h2>
                <div class="blcTriFiltre">
                    <div>
                        <input type="radio" name="tri" id="miseEnAvant" checked>
                        <label for="miseEnAvant">Mise en avant</label>
                    </div>
                    <div>
                        <input type="radio" name="tri" id="noteCroissant">
                        <label for="noteCroissant">Note croissant</label>
                        <input type="radio" name="tri" id="noteDecroissant">
                        <label for="noteDecroissant">Note décroissant</label>
                    </div>
                    <div>
                        <input type="radio" name="tri" id="prixCroissant">
                        <label for="prixCroissant">Prix croissant</label>
                        <input type="radio" name="tri" id="prixDecroissant">
                        <label for="prixDecroissant">Prix décroissant</label>
                    </div>
                    <div>
                        <input type="radio" name="tri" id="dateRecent">
                        <label for="dateRecent">Plus récent</label>
                        <input type="radio" name="tri" id="dateAncien">
                        <label for="dateAncien">Plus ancien</label>
                    </div>
                </div>
            </aside>
            <aside id="filtre" class="asdTriFiltre">
                <h2>Filtres de recherche</h2>
                <div class="blcTriFiltre">
                    <div id="note">
                        <h3>Par note</h3>
                        <div>
                            <label for="5star" class="blocStar">
                                <div class="star"></div>
                                <div class="star"></div>
                                <div class="star"></div>
                                <div class="star"></div>
                                <div class="star"></div>
                            </label>
                            <input type="checkbox" name="5star" id="5star" checked>
                        </div>
                        <div>
                            <label for="4star" class="blocStar">
                                <div class="star"></div>
                                <div class="star"></div>
                                <div class="star"></div>
                                <div class="star"></div>
                            </label>
                            <input type="checkbox" name="4star" id="4star" checked>
                        </div>
                        <div>    
                            <label for="3star" class="blocStar">
                                <div class="star"></div>
                                <div class="star"></div>
                                <div class="star"></div>
                            </label>
                            <input type="checkbox" name="3star" id="3star" checked>
                        </div>
                        <div>
                            <label for="2star" class="blocStar">
                                <div class="star"></div>
                                <div class="star"></div>
                            </label>
                            <input type="checkbox" name="2star" id="2star" checked>
                        </div>
                        <div>
                            <label for="1star" class="blocStar">
                                <div class="star"></div>
                            </label>
                            <input type="checkbox" name="1star" id="1star" checked>
                        </div>
                    </div>
                    <div id="prix">
                        <h3>Par prix</h3>
                        <div>
                            <label for="prixMin">De</label>
                            <select name="prixMin" id="prixMin">
                                <option value="0" selected>0€</option>
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
                            <label for="prixMax">à</label>
                            <select name="prixMax" id="prixMax">
                            <option value="0">0€</option>
                                <option value="25">25€</option>
                                <option value="50">50€</option>
                                <option value="75">75€</option>
                                <option value="100" selected>100€</option>
                                <option value="125">125€</option>
                                <option value="150">150€</option>
                                <option value="175">175€</option>
                                <option value="200">200€ et +</option>
                            </select>
                        </div>
                    </div>
                    <div id="statut">
                        <h3>Par Statut</h3>
                        <div>
                            <label for="ouvert">Ouvert</label>
                            <input type="checkbox" name="statut" id="ouvert" checked>
                        </div>
                        <div>
                            <label for="ferme">Fermé</label>
                            <input type="checkbox" name="statut" id="ferme" checked>
                        </div>
                    </div>
                    <div id="categorie">
                        <h3>Par catégorie</h3>
                        <ul>
                            <li>
                                <label for="Restauration">Restauration</label>
                                <input type="checkbox" name="categorie" id="Restauration" checked>
                            </li>
                            <li>
                                <label for="Activité">Activité</label>
                                <input type="checkbox" name="categorie" id="Activité" checked>
                            </li>
                            <li>
                                <label for="Parc">Parc d’attractions</label>
                                <input type="checkbox" name="categorie" id="Parc" checked>
                            </li>
                            <li>
                                <label for="Visite">Visite</label>
                                <input type="checkbox" name="categorie" id="Visite" checked>
                            </li>
                            <li>
                                <label for="Spectacle">Spectacle</label>
                                <input type="checkbox" name="categorie" id="Spectacle" checked>
                            </li>
                        </ul>
                    </div>
                    <div id="date">
                        <h3>Par date</h3>
                        <div>
                            <label for="dateDepart">Départ&nbsp;:&nbsp;</label>
                            <input type="date" name="dateDepart" id="dateDepart" value="<?php echo date("Y-m-j"); ?>" min="<?php echo date("Y-m-j"); ?>">
                            <input type="time" name="heureDebut" id="heureDebut" >
                        </div>
                        <div>
                            <label for="dateDepart">Fin&nbsp;:&nbsp;</label>
                            <input type="date" name="dateFin" id="dateFin" value="<?php echo date('Y-m-d', strtotime('+1 month')); ?>" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                            <input type="time" name="heureFin" id="heureFin" value="">
                        </div>
                    </div>
                </div>
            </aside>
        </section>
        <?php 

        $stmt = $conn->prepare("SELECT * FROM pact.offres");
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
                    $ville=($offre['ville'])?$offre['ville']:"Pas de localisation entrée";;
                    $gammeText = ($offre['gammedeprix']) ? " ⋅ " . $offre['gammedeprix'] : "";
                    $nomTag=($offre['categorie']!="Autre")?$offre['categorie']:"Pas de catégorie";
                    $tag = $offre['all_tags']?explode(',',trim($offre['all_tags'],'{}')):"";
                    if (($offre['listhorairemidi'])!="") {
                        $horaireMidi=explode(';',$offre['listhorairemidi']);                        
                        // Tableau final
                        $resultsMidi = [];
                        
                        // Parcours et transformation des données
                        foreach ($horaireMidi as $item) {
                            // Décodage du JSON interne
                            $decodedItem = json_decode($item, true);
                            
                            // Ajout des clés supplémentaires
                            $resultsMidi[] = [
                                'jour' => $decodedItem['jour'],
                                'idoffre' => $idOffre,
                                'heureouverture' => $decodedItem['heureOuverture'],
                                'heurefermeture' => $decodedItem['heureFermeture']
                            ];
                        }                    
                    }else{
                        $resultsMidi = [];
                    }
                    if (($offre['listhorairesoir'])!="") {
                        $horaireSoir=explode(';',$offre['listhorairesoir']);                        
                        // Tableau final
                        $resultsSoir = [];
                        
                        // Parcours et transformation des données
                        foreach ($horaireSoir as $item) {
                            // Décodage du JSON interne
                            $decodedItem = json_decode($item, true);
                            
                            // Ajout des clés supplémentaires
                            $resultsSoir[] = [
                                'jour' => $decodedItem['jour'],
                                'idoffre' => $idOffre,
                                'heureouverture' => $decodedItem['heureOuverture'],
                                'heurefermeture' => $decodedItem['heureFermeture']
                            ];
                        } 
                    }else {
                        $resultsSoir = [];
                    }                    
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
                    if (($typeUser == "pro_public" || $typeUser == "pro_prive")) {
                        $idutilisateur=$_SESSION["idUser"];
                        if ($offre['idu']==$idutilisateur) {
                            require "components/cardOffer.php"; 
                        }
                    }else {
                        if ($offre['statut']=='actif') {
                            require "components/cardOffer.php";
                        }
                    }
                    
                } ?>
            <?php } else { ?>
                <p>Aucune offre trouvée </p>
            <?php } ?>
        </section>
    </main>
    <?php require_once "components/footer.php"; ?>
</body>
<script>
    const now = new Date();
    let hours = now.getHours().toString().padStart(2, '0');
    let minutes = now.getMinutes().toString().padStart(2, '0');
    let timeString = `${hours}:${minutes}`;
    document.getElementById('heureFin').value = timeString;
    document.getElementById('heureDebut').value = timeString;

    const btnFiltre = document.querySelector("#btnFiltre");
    const btnTri = document.querySelector("#btnTri");

    btnFiltre.addEventListener("click", () => {

    });


    function openPop-up(element) {
        element.classList.add("openFiltreTri");
    }
    
    function closePop-up(element) {
        element.classList.remove("openFiltreTri");
    }
</script>
</html>