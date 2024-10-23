<?php 
    // Démarrer la session
    session_start();

    require_once "db.php"; // fichier de connexion à la BDD

    // Préparer et exécuter la requête SQL
    $stmt = $conn->prepare("SELECT * FROM pact._offre ORDER BY dateCrea DESC");
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM pact._horairesoir");
    $stmt->execute();
    $resultsSoir = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM pact._horairemidi");
    $stmt->execute();
    $resultsMidi = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

// Filtrer les horaires de l'offre en fonction de l'idOffre et du jour actuel
$horaires = array_merge($resultsSoir, $resultsMidi); // Fusionner les résultats midi et soir

$restaurantOuvert = false; // Par défaut, on considère le restaurant fermé

foreach ($horaires as $horaire) {
    print_r($horaire);
    echo $horaire['idoffre']." et ".$horaire['jour']." et ".$currentDay;
    if ($horaire['idoffre'] == 3 && $horaire['jour'] == $currentDay) {
        // Convertir les horaires d'ouverture et de fermeture en DateTime
        $tab=$horaire;
        $heureOuverture = DateTime::createFromFormat('H:i',$horaire['heureouverture']);
        $heureFermeture = DateTime::createFromFormat('H:i',$horaire['heurefermeture']);
        // Vérifier si l'heure actuelle est comprise entre l'heure d'ouverture et de fermeture
        if ($currentTime >= $heureOuverture && $currentTime <= $heureFermeture) {
            $restaurantOuvert = true;
            break; // Si on trouve que le restaurant est ouvert, on arrête la boucle
        }
    }
}

$img = $conn->prepare("SELECT * FROM pact._illustre");
$img->execute();
$urlImg = $img->fetchAll(PDO::FETCH_ASSOC);



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche d'offre</title>
    <link rel="stylesheet" href="style.css">
    <script src="./js/setColor.js"></script>
</head>
<body id="search">
    <main>
        <aside>
            <h2>Tri des offres</h2>
            <h2>Filtre</h2>
        </aside>
        <section>
            <h2>Liste des offres</h2>
            <?php if ($results){ ?>
                <ul>
                    <?php foreach ($results as $offre){
                        $nomOffre=$offre['nom'];
                        $noteAvg="Non noté";
                    }
                    print_r($tab);
                    if ($restaurantOuvert) {
                        echo "Le restaurant est ouvert.";
                    } else {
                        echo "Le restaurant est fermé.";
                    }

                    foreach ($urlImg as $array) {
                        print_r($array);
                        echo $array['url'];
                        ?><a href="<?php echo $array['url'] ?>"></a><?php
                    }
                    ?>
                </ul>
            <?php } else{ ?>
                <p>Aucune offre trouvée </p>
            <?php } ?>
        </section>
    </main>
</body>
</html>
