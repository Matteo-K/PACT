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
                <h2>Filtrer</h2>
                <div class="blcTriFiltre">
                    <div id="note">
                        <h3>Par note</h3>
                        <div>
                            <div>
                                <label for="star1" class="blocStar">
                                    <input type="checkbox" name="star1" id="star1" checked>
                                    <span class="checkmark"></span>
                                    <div class="star"></div>
                                </label>
                            </div>
                            <div>
                                <label for="star2" class="blocStar">
                                    <input type="checkbox" name="star2" id="star2" checked>
                                    <span class="checkmark"></span>
                                    <div class="star"></div>
                                    <div class="star"></div>
                                </label>
                            </div>
                            <div>    
                                <label for="star3" class="blocStar">
                                    <input type="checkbox" name="star3" id="star3" checked>
                                    <span class="checkmark"></span>
                                    <div class="star"></div>
                                    <div class="star"></div>
                                    <div class="star"></div>
                                </label>
                            </div>
                            <div>
                                <label for="star4" class="blocStar">
                                    <input type="checkbox" name="star4" id="star4" checked>
                                    <span class="checkmark"></span>
                                    <div class="star"></div>
                                    <div class="star"></div>
                                    <div class="star"></div>
                                    <div class="star"></div>
                                </label>
                            </div>
                            <div>
                                <label for="star5" class="blocStar">
                                    <input type="checkbox" name="star5" id="star5" checked>
                                    <span class="checkmark"></span>
                                    <div class="star"></div>
                                    <div class="star"></div>
                                    <div class="star"></div>
                                    <div class="star"></div>
                                    <div class="star"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div id="blcPrixStatut">
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
                                <label for="ouvert">
                                    Ouvert
                                    <input type="checkbox" name="statut" id="ouvert" checked>
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div>
                                <label for="ferme">
                                    Fermé
                                    <input type="checkbox" name="statut" id="ferme" checked>
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div id="categorie">
                        <h3>Par catégorie</h3>
                        <ul>
                            <li>
                                <label for="Visite">
                                    <input type="checkbox" name="categorie" id="Visite" checked>
                                    <span class="checkmark"></span>
                                    Visite
                                </label>
                            </li>
                            <li>
                                <label for="Activite">
                                    <input type="checkbox" name="categorie" id="Activite" checked>
                                    <span class="checkmark"></span>
                                    Activité
                                </label>
                            </li>
                            <li>
                                <label for="Spectacle">
                                    <input type="checkbox" name="categorie" id="Spectacle" checked>
                                    <span class="checkmark"></span>
                                    Spectacle
                                </label>
                            </li>
                            <li>
                                <label for="Restauration">
                                    <input type="checkbox" name="categorie" id="Restauration" checked>
                                    <span class="checkmark"></span>
                                    Restauration
                                </label>
                            </li>
                            <li>
                                <label for="Parc">
                                    <input type="checkbox" name="categorie" id="Parc" checked>
                                    <span class="checkmark"></span>
                                    Parc d’attractions
                                </label>
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
                            <input type="time" name="heureFin" id="heureFin">
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
    document.addEventListener("DOMContentLoaded", () => {

        // Liste des offres pour la manipuler
        <?php $arrayOffer = ["Pomme", "Banane", "Fraise", "Poire", "Abricot", "Autre fruit"]; ?>
        let arrayOffer = <?php echo json_encode($arrayOffer); ?>;
        
        // Acualise l'heure actuelle
        const now = new Date();
        let hours = now.getHours().toString().padStart(2, '0');
        let minutes = now.getMinutes().toString().padStart(2, '0');
        let timeString = `${hours}:${minutes}`;
        document.querySelector('#heureFin').value = timeString;
        document.querySelector('#heureDebut').value = timeString;

        
        document.querySelector('#prixMin').addEventListener('change', inverseValuesPrix);
        document.querySelector('#prixMax').addEventListener('change', inverseValuesPrix);

        /**
         * Switch les valeurs des prix maximum et minimum si prix maximum < prix minimum
         */
        function inverseValuesPrix () {
            const selectMin = document.querySelector('#prixMin');
            const selectMax = document.querySelector('#prixMax');
            const valueMin = parseInt(selectMin.value);
            const valueMax = parseInt(selectMax.value);
            
            if (valueMin > valueMax) {
                selectMin.value = valueMax;
                selectMax.value = valueMin;
            }
        }

        // Ouvre et ferme le pop-up tri et filtre pour la partie mobile
        const btnFiltre = document.querySelector("#btnFiltre");
        const btnTri = document.querySelector("#btnTri");
        const asideTri = document.querySelector("#tri");
        const asideFiltre = document.querySelector("#filtre");
        
        btnTri.addEventListener("click", () => {
            asideTri.classList.toggle("openFiltreTri");
        });
        
        btnFiltre.addEventListener("click", () => {
            asideFiltre.classList.toggle("openFiltreTri");
        });
    });
</script>
<script src="js/sortAndFilter.js"></script>
</html>