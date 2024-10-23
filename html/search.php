<?php 
    // Démarrer la session
    session_start();

    require_once "db.php"; // fichier de connexion à la BDD

    // Préparer et exécuter la requête SQL
    $stmt = $conn->prepare("SELECT * FROM pact._offre ORDER BY dateCrea DESC");
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

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






?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche d'offre</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="img/logo.png" type="image/x-icon">
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
                    <?php 
                    print_r($results);
                        foreach ($results as $offre){
                        $idOffre=$offre['idoffre'];
                        $nomOffre=$offre['nom'];
                        $noteAvg="Non noté";
                        $img = $conn->prepare("SELECT * FROM pact._illustre WHERE idoffre=$idOffre ORDER BY url ASC");
                        $img->execute();
                        $urlImg = $img->fetchAll(PDO::FETCH_ASSOC);

                        $stmt = $conn->prepare("SELECT * FROM pact._horairesoir WHERE idoffre=$idOffre");
                        $stmt->execute();
                        $resultsSoir = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        $stmt = $conn->prepare("SELECT * FROM pact._horairemidi WHERE idoffre=$idOffre");
                        $stmt->execute();
                        $resultsMidi = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                        $horaires = array_merge($resultsSoir, $resultsMidi); // Fusionner les résultats midi et soir
                        $restaurantOuvert = false; // Par défaut, on considère le restaurant fermé

                        foreach ($horaires as $horaire) {
                            if ($horaire['jour'] == $currentDay) {
                                // Convertir les horaires d'ouverture et de fermeture en DateTime
                                $heureOuverture = DateTime::createFromFormat('H:i',$horaire['heureouverture']);
                                $heureFermeture = DateTime::createFromFormat('H:i',$horaire['heurefermeture']);
                                // Vérifier si l'heure actuelle est comprise entre l'heure d'ouverture et de fermeture
                                if ($currentTime >= $heureOuverture && $currentTime <= $heureFermeture) {
                                    $restaurantOuvert = true;
                                    break; // Si on trouve que le restaurant est ouvert, on arrête la boucle
                                }
                            }
                        }
                        
                        $loca = $conn->prepare("SELECT * FROM pact._localisation WHERE idOffre=$idOffre");
                        $loca->execute();
                        $ville = $loca->fetchAll(PDO::FETCH_ASSOC);

                        if ($offre['statut']=='actif') {
                            ?>
                        <div>
                            <h4><?php echo $nomOffre; ?></h4>
                            <p><?php echo $noteAvg ?></p>
                            <p><?php echo $ville[0]['ville'] ?></p>
                            <p><?php if ($restaurantOuvert) {
                                        echo "Le restaurant est ouvert.";
                                     } else {
                                        echo "Le restaurant est fermé.";
                            }?></p>
                            <a href="/detailsOffer.php?idoffre=<?php echo $idOffre ;?>&ouvert=<?php echo $restaurantOuvert; ?>"><img src="<?php echo $urlImg[0]['url']; ?>" alt="photo principal de l'offre">
                            </a>
                        </div>
                        <?php
                        }
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
