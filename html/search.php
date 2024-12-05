<?php 
require_once "config.php";

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

        $offres = new ArrayOffer();
        ?>
        <section class="searchoffre">
        </section>
        <section id="pagination">
            <ul id="pagination-liste">
            </ul>
        </section>
    </main>
    <?php require_once "components/footer.php"; ?>
    <!-- Data -->
    <div id="offers-data" data-offers='<?php echo htmlspecialchars(json_encode($offres->getArray($offres->recherche($idUser, $typeUser, $search)))); ?>'></div>
    <div id="user-data" data-user='<?php echo $typeUser ?>'></div>
    <script src="js/sortAndFilter.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            
            // Acualise l'heure actuelle
            // const now = new Date();
            // let hours = now.getHours().toString().padStart(2, '0');
            // let minutes = now.getMinutes().toString().padStart(2, '0');

            let timeDebut = "08:00";
            let timeFin = "23:00";

            document.querySelector('#heureDebut').value = timeDebut;
            document.querySelector('#heureFin').value = timeFin;
            
            
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
</body>
</html>