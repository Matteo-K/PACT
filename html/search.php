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

?>

<!DOCTYPE html>
<html lang="en">
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
        <aside class="sectionFiltre">
            <h2>Filtre</h2>
            <h2>Tri</h2>
        </aside>
        <section class="searchoffre">
        <?php if ($results){ ?>
            <?php foreach ($results as $offre){ 
                $idOffre = $offre['idoffre'];
                $nomOffre = $offre['nom'];
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
                $ville = $loca->fetchAll(PDO::FETCH_ASSOC);

                // Requête pour la gamme de prix
                $prix = $conn->prepare("SELECT * FROM pact.restaurants WHERE idOffre = $idOffre");
                $prix->execute();
                $gamme = $prix->fetchAll(PDO::FETCH_ASSOC);
                $gammeText = ($gamme) ? " ⋅ " . $gamme[0]['gammedeprix'] : "";

                if ($offre['statut'] == 'actif') { ?>
                    <div class="carteOffre">
                        <div class="infoOffre">
                            <p class="titre"><?php echo $nomOffre; ?></p>
                            <p class="villesearch"><?php echo $ville[0]['ville'] . $gammeText; ?></p>

                            <div class="noteStatut">
                                <p><?php echo $noteAvg; ?></p>
                                <p id="couleur-<?php echo $idOffre; ?>" class="searchStatutO">
                                    <?php echo ($restaurantOuvert == "EstOuvert") ? "Ouvert" : "Fermé"; ?>
                                </p>
                                <script>
                                    let st_<?php echo $idOffre; ?> = document.getElementById("couleur-<?php echo $idOffre; ?>");
                                    if ("<?php echo $restaurantOuvert; ?>" === "EstOuvert") {
                                        st_<?php echo $idOffre; ?>.classList.add("searchStatutO");
                                    } else {
                                        st_<?php echo $idOffre; ?>.classList.add("searchStatutF");
                                    }
                                </script>
                            </div>
                        </div>
                        <a href="/detailsOffer.php?idoffre=<?php echo $idOffre; ?>&ouvert=<?php echo $restaurantOuvert; ?>">
                            <img src="<?php echo $urlImg[0]['url']; ?>" alt="photo principal de l'offre">
                        </a>
                    </div>
                <?php }
            } ?>
        <?php } else { ?>
            <p>Aucune offre trouvée </p>
        <?php } ?>
    </section>

    </main>
    <?php require_once "components/footer.php"; ?>
</body>
</html>
