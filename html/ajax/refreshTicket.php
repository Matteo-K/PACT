<?php
require_once "../config.php";

// Récupérer et décoder les données JSON envoyées
$data = json_decode(file_get_contents("php://input"), true);
$idOffre = $data['idoffre'] ?? null;

if ($idOffre === null) {
    echo json_encode(["error" => "ID offre manquant"]);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM pact._blacklist WHERE idOffre = ? ");
$stmt->execute([$idOffre]);
$result = $stmt->fetchAll();

echo new DateTime();
print_r($result);

$stmt = $conn->prepare("SELECT COUNT(*) AS count FROM pact._blacklist WHERE idOffre = ? AND datefinblacklist >= CURRENT_TIMESTAMP");
$stmt->execute([$idOffre]);
$result = $stmt->fetch();

echo json_encode(["count" => $result['count']]);
?>
