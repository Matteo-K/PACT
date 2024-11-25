<?php 
require_once "config.php";

$recherche = isset($_GET["search"]) ? $_GET["search"]: "";
$page = isset($_GET["page"]) ? $_GET["page"] :  1;
$nbElement = 15;
$countOffer = 0;

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

$arrayOffer = [];

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
        <?php 
        require_once "components/asideTriFiltre.php";

        $stmt = $conn->prepare("SELECT * FROM pact.offres");
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $arrayOffer = $results;
        $countOffer = count($results);
        $results = array_slice($results, ($page-1)*$nbElement, $nbElement);
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
                    $statut = isset($offre['statut']) ? $offre['statut'] : "" ;
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
                            require "components/cardOfferPro.php"; 
                        }
                    } else {
                        if ($offre['statut']=='actif') {
                            
                            require "components/cardOffer.php";
                        }
                    }
                    
                } ?>
            <?php } else { ?>
                <p>Aucune offre trouvée </p>
            <?php } ?>
        </section>
        <section id="pagination">
            <?php $lien = "search.php?" . ($recherche != "" ? $recherche : ""); ?>
            <ul>
                <li>
                    <?php if ($page > 5) { ?>
                        <a href="<?php echo $lien . "&page=" . ($page - 5); ?>">
                            <?php echo $page - 5; ?>
                        </a>
                    <?php } ?>
                </li>
                <li>
                    <?php if ($page > 2) { ?>
                        <a href="<?php echo $lien . "&page=" . ($page - 2); ?>">
                            <?php echo $page - 2; ?>
                        </a>
                    <?php } ?>
                </li>
                <li>
                    <?php if ($page > 1) { ?>
                        <a href="<?php echo $lien . "&page=" . ($page - 1); ?>">
                            <?php echo $page - 1; ?>
                        </a>
                    <?php } ?>
                </li>
                <li id="pageActuel">
                    <a href="<?php echo $lien . "&page=" . $page; ?>">
                        <?php echo $page; ?>
                    </a>
                </li>
                <li>
                    <?php if (($page) * $nbElement <= $countOffer) { ?>
                        <a href="<?php echo $lien . "&page=" . ($page + 1); ?>">
                            <?php echo $page + 1; ?>
                        </a>
                    <?php } ?>
                </li>
                <li>
                    <?php if (($page + 1) * $nbElement <= $countOffer) { ?>
                        <a href="<?php echo $lien . "&page=" . ($page + 2); ?>">
                            <?php echo $page + 2; ?>
                        </a>
                    <?php } ?>
                </li>
                <li>
                    <?php if (($page + 4) * $nbElement <= $countOffer) { ?>
                        <a href="<?php echo $lien . "&page=" . ($page + 5); ?>">
                            <?php echo $page + 5; ?>
                        </a>
                    <?php } ?>
                </li>
            </ul>
        </section>

    </main>
    <?php require_once "components/footer.php"; ?>
</body>
<script>
    document.addEventListener("DOMContentLoaded", () => {

        // Liste des offres pour la manipuler
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
        const fermeTri = document.querySelector("#fermeTri");
        const fermeFiltre = document.querySelector("#fermeFiltre");
        const body = document.body;

        /**
         * Ouvre et ferme le aside au format mobile
         * Empêche le scroll
         */
        function toggleAside(aside) {
            aside.classList.toggle('openFiltreTri');
            
            if (asideTri.classList.contains('openFiltreTri') || asideFiltre.classList.contains('openFiltreTri')) {
                body.classList.add('no-scroll');
            } else {
                body.classList.remove('no-scroll');
            }
        }

        fermeTri.addEventListener("click", () => toggleAside(asideTri));
        fermeFiltre.addEventListener("click", () => toggleAside(asideFiltre));
        btnTri.addEventListener("click", () => toggleAside(asideTri));
        btnFiltre.addEventListener("click", () => toggleAside(asideFiltre));

});
</script>
<script src="js/sortAndFilter.js"></script>
</html>