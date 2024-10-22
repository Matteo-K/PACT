<?php 
    // Démarrer la session
    session_start();

    require_once "db.php"; // fichier de connexion à la BDD

    $stmt = $conn->prepare("SELECT * FROM pact._offre ORDER BY dateCrea DESC");
    $stmt->execute();
    print_r($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche d'offre</title>
    <link rel="stylesheet" href="style.css">
</head>
<body id="search">
    <?php require_once "components/header.php" ?>
    <main>
        <aside>
            <h2>Tri des offres</h2>
            <h2>Filtre</h2>
        </aside>
        <section>

        </section>
    </main>
</body>
</html>