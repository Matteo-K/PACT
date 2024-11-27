<?php

require_once "../config.php";

// Sélection des entrées où dureeenligne est null
$stmt = $conn->prepare("SELECT * FROM pact._historiquestatut WHERE dureeenligne IS NULL");
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$date = new DateTime();
$dateString = $date->format('Y-m-d');

foreach ($results as $value) {
    $dateLancement = new DateTime($value['datelancement']);
    $duree = $date->diff($dateLancement);
    $dureeString = $duree->days;
    $idOffre = $value['idoffre'];

    // Mise à jour des enregistrements
    $stmt = $conn->prepare("UPDATE pact._historiquestatut 
        SET dureeenligne = :duree 
        WHERE dureeenligne IS NULL AND idoffre = :idOffre");
    $stmt->execute([
        ':duree' => $dureeString,
        ':idOffre' => $idOffre,
    ]);

    // Insertion de nouvelles données
    $ins = $conn->prepare("INSERT INTO pact._historiquestatut (idoffre, datelancement, dureeenligne) 
        VALUES (:idOffre, :dateLancement, NULL)");
    $ins->execute([
        ':idOffre' => $idOffre,
        ':dateLancement' => $dateString,
    ]);
}

// Sélection des offres dans le dernier mois
$stmt = $conn->prepare("SELECT idoffre 
    FROM pact._historiquestatut 
    WHERE datelancement >= NOW() - INTERVAL '1 month' 
    GROUP BY idoffre");
$stmt->execute();
$id = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($id as $value) {
    $idOffre2 = $value['idoffre'];

    // Insertion dans la table de facturation
    $ins = $conn->prepare("INSERT INTO pact._facturation (dateFactue, idOffre) 
        VALUES (:dateFacture, :idOffre)");
    $ins->execute([
        ':dateFacture' => $dateString,
        ':idOffre' => $idOffre2,
    ]);
}
?>
