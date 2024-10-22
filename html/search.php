<?php 
    // Démarrer la session
    session_start();

    require_once "db.php"; // fichier de connexion à la BDD

    // Préparer et exécuter la requête SQL
    $stmt = $conn->prepare("SELECT * FROM pact._offre ORDER BY dateCrea DESC");
    $stmt->execute();

    // Récupérer tous les résultats sous forme de tableau associatif
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            <h2>Liste des offres</h2>
            <?php if ($results): ?>
                <ul>
                    <?php foreach ($results as $offre): ?>
                        <li>
                            <h3><?= htmlspecialchars($offre['nom']); ?></h3>
                            <p><?= htmlspecialchars($offre['description']); ?></p>
                            <p><strong>Date de création :</strong> <?= htmlspecialchars($offre['datecrea']); ?></p>
                            <!-- Ajoute d'autres champs si nécessaire -->
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Aucune offre trouvée.</p>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
