<?php
require_once "./config.php";

header('Content-Type: application/json'); // Réponse JSON

// Vérifier si l'utilisateur est connecté
if (!$isLoggedIn || !$idUser) {
    echo json_encode(['status' => 'error', 'message' => 'Utilisateur non authentifié']);
    exit;
}

// Récupérer les données JSON envoyées par fetch
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['membre'])) {
    echo json_encode(['status' => 'error', 'message' => 'Données invalides']);
    exit;
}

$membre = $data['membre'];

// Fonction pour générer une clé API aléatoire
function generateApiKey() {
    // Générer 128 bits aléatoires (16 octets)
    $randomBytes = random_bytes(16);
    return bin2hex($randomBytes); // Convertir en hexadécimal
}

$newApiKey = generateApiKey();

try {
    // Préparer la requête SQL en fonction de l'état "membre"
    if ($membre) {
        $stmt = $conn->prepare("UPDATE pact._utilisateur SET apikey = :apikey WHERE idu = :idu");
    } else {
        $stmt = $conn->prepare("UPDATE pact._pro SET apikey = :apikey WHERE idu = :idu");
    }

    // Liaison des paramètres
    $stmt->bindParam(':apikey', $newApiKey, PDO::PARAM_STR);
    $stmt->bindParam(':idu', $idUser, PDO::PARAM_INT);

    // Exécuter la requête
    $stmt->execute();

    // Réponse JSON de succès
    echo json_encode(['status' => 'success', 'apikey' => $newApiKey]);
    exit;
} catch (PDOException $e) {
    // Gestion des erreurs
    echo json_encode(['status' => 'error', 'message' => 'Erreur SQL : ' . $e->getMessage()]);
    exit;
}
?>
