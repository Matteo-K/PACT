<?php
require_once "./config.php";

header('Content-Type: application/json'); // Réponse JSON

// Récupérer les données JSON envoyées par fetch
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['membre'])) {
    echo json_encode(['status' => 'error', 'message' => 'Données invalides']);
    exit;
}

$isMember = $data['membre'];

function generateApiKey() {
    // Générer 128 bits aléatoires (16 octets)
    $randomBytes = random_bytes(16);
    return bin2hex($randomBytes); // Convertir en hexadécimal
}

// Vérifiez si l'utilisateur est connecté
if ($isLoggedIn) {
    $newApiKey = generateApiKey();

    try {

        if($membre){
            $stmt = $conn->prepare("UPDATE pact._utilisateur SET apikey = :apikey WHERE idu = :idu");
        } else{
            $stmt = $conn->prepare("UPDATE pact._pro SET apikey = :apikey WHERE idu = :idu");
        }
        // Mise à jour de la clé API dans la base
        $stmt->bindParam(':apikey', $newApiKey);
        $stmt->bindParam(':idu', $idUser);
        $stmt->execute();

        // Réponse JSON de succès
        echo json_encode(['status' => 'success', 'apikey' => $newApiKey]);
        exit;
    } catch (PDOException $e) {
        // Gestion des erreurs
        echo json_encode(['status' => 'error', 'message' => 'Erreur SQL : ' . $e->getMessage()]);
        exit;
    }
} else {
    // Cas où l'utilisateur n'est pas connecté
    echo json_encode(['status' => 'error', 'message' => 'Utilisateur non authentifié']);
    exit;
}

?>
