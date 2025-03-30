<?php
require_once "../config.php";

// Récupérer et décoder les données JSON envoyées
$data = json_decode(file_get_contents("php://input"), true);
$idOffre = $data['idoffre'] ?? null;
$action = $data['action'] ?? null;

if ($idOffre === null) {
    echo json_encode(["error" => "ID offre manquant"]);
    exit;
}

if ($action === "nbTicket") {
    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM pact._blacklist WHERE idOffre = ? AND datefinblacklist >= CURRENT_TIMESTAMP");
    $stmt->execute([$idOffre]);
    $result = $stmt->fetch();

    $nbTicket = max(0, 3 - (int)$result['count']); // Empêche les valeurs négatives

    echo json_encode(["count" => $nbTicket]);
} elseif ($action === "duree") {
    $stmt = $conn->prepare("SELECT datefinblacklist FROM pact._blacklist WHERE idOffre = ? AND datefinblacklist >= CURRENT_TIMESTAMP");
    $stmt->execute([$idOffre]);
    $dates = $stmt->fetchAll(PDO::FETCH_COLUMN); // Récupère uniquement la colonne `datefinblacklist`

    echo json_encode(["dates" => $dates]);
} else {
    echo json_encode(["error" => "Action invalide"]);
}
?>
