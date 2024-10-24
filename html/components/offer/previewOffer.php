<?php
    // selectionner les informations de l'offre en cours de création
    $stmt = $conn->prepare("SELECT * FROM pact._offre WHERE idOffre = :idOffre");
    $stmt->bindParam(':idOffre', $idOffre, PDO::PARAM_INT);
    $stmt->execute();
    $offer = $stmt->fetch(PDO::FETCH_ASSOC);


    // Fonction pour récupérer les horaires
    function getSchedules($conn, $idOffre) {
        $schedules = [
            'midi' => [],
            'soir' => []
        ];

        // Récupérer les horaires du midi et du soir
        $stmtMidi = $conn->prepare("SELECT * FROM pact._horaireMidi WHERE idOffre = :idOffre");
        $stmtMidi->bindParam(':idOffre', $idOffre, PDO::PARAM_INT);
        $stmtMidi->execute();
        $schedules['midi'] = $stmtMidi->fetchAll(PDO::FETCH_ASSOC);

        $stmtSoir = $conn->prepare("SELECT * FROM pact._horaireSoir WHERE idOffre = :idOffre");
        $stmtSoir->bindParam(':idOffre', $idOffre, PDO::PARAM_INT);
        $stmtSoir->execute();
        $schedules['soir'] = $stmtSoir->fetchAll(PDO::FETCH_ASSOC);

        return $schedules;
    }

    // selectionner les tags de l'offre en cours de création
    // $stmt = $conn->prepare("SELECT * FROM pact._tag WHERE idOffre = :idOffre");
    // $stmt->bindParam(':idOffre', $idOffre, PDO::PARAM_INT);
    // $stmt->execute();
    // $offer = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$offer) {
        echo "Aucune offre trouvée pour cet ID.";
        exit();
    }

    echo "<pre>";
        print_r($offer);
    echo "</pre>";

?>

<form id="previewOffer" action="enregOffer.php" method="post">
    <h1><?php echo htmlspecialchars($offer["nom"]); ?></h1>
