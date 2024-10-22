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
$currentDay = strtolower(date('l')); // ex: "monday", "tuesday", etc.
$currentTime = new DateTime(date('H:i')); // ex: 14:30

// Filtrer les horaires de l'offre en fonction de l'idOffre et du jour actuel
$horaires = array_merge($resultsSoir, $resultsMidi); // Fusionner les résultats midi et soir

$restaurantOuvert = false; // Par défaut, on considère le restaurant fermé

foreach ($horaires as $horaire) {
    if ($horaire['idoffre'] == $idOffre && strtolower($horaire['jour']) == $currentDay) {
        // Convertir les horaires d'ouverture et de fermeture en DateTime
        $heureOuverture = DateTime::createFromFormat('H\hi', str_replace('h', ':', $horaire['heure_ouverture']));
        $heureFermeture = DateTime::createFromFormat('H\hi', str_replace('h', ':', $horaire['heure_fermeture']));

        // Vérifier si l'heure actuelle est comprise entre l'heure d'ouverture et de fermeture
        if ($currentTime >= $heureOuverture && $currentTime <= $heureFermeture) {
            $restaurantOuvert = true;
            break; // Si on trouve que le restaurant est ouvert, on arrête la boucle
        }
    }
}



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
    <?php require_once "components/header.php" ?>
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
                        $nomOffre=htmlspecialchars($offre['nom']);
                        $noteAvg="Non noté";
                    }
                    if ($restaurantOuvert) {
                        echo "Le restaurant est ouvert.";
                    } else {
                        echo "Le restaurant est fermé.";
                    }
                    ?>
                </ul>
            <?php } else{ ?>
                <p>Aucune offre trouvée.</p>
            <?php } ?>
        </section>
    </main>
</body>
</html>
