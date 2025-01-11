<?php
header('Content-Type: application/json');

// Inclure le script de connexion à la base de données
require_once 'connect_params.php'; // Assurez-vous que ce fichier contient votre connexion $conn

// Vérifier que la requête est POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer le corps de la requête
    $data = json_decode(file_get_contents('php://input'), true);

    // Vérifier que les données nécessaires sont présentes
    if (!isset($data['action']) || !isset($data['avisId'])) {
        echo json_encode(['success' => false, 'message' => 'Paramètres manquants']);
        exit;
    }

    $action = $data['action'];
    $avisId = (int)$data['avisId']; // Sécurisation en cas d'entrée incorrecte

    // Vérification de l'action et exécution de la requête correspondante
    try {
        switch ($action) {
            case 'like':
                $stmt = $conn->prepare("UPDATE avis SET nblike = nblike + 1 WHERE id = :avisId");
                break;
            case 'unlike':
                $stmt = $conn->prepare("UPDATE avis SET nblike = nblike - 1 WHERE id = :avisId AND nblike > 0");
                break;
            case 'dislike':
                $stmt = $conn->prepare("UPDATE avis SET nbdislike = nbdislike + 1 WHERE id = :avisId");
                break;
            case 'undislike':
                $stmt = $conn->prepare("UPDATE avis SET nbdislike = nbdislike - 1 WHERE id = :avisId AND nbdislike > 0");
                break;
            default:
                echo json_encode(['success' => false, 'message' => 'Action non reconnue']);
                exit;
        }

        // Exécuter la requête
        $stmt->bindParam(':avisId', $avisId, PDO::PARAM_INT);
        $stmt->execute();

        // Récupérer les nouveaux compteurs pour cet avis
        $stmt = $conn->prepare("SELECT nblike, nbdislike FROM avis WHERE id = :avisId");
        $stmt->bindParam(':avisId', $avisId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Retourner les nouveaux compteurs
        echo json_encode([
            'success' => true,
            'nblike' => $result['nblike'] ?? 0,
            'nbdislike' => $result['nbdislike'] ?? 0,
        ]);
    } catch (Exception $e) {
        // Gérer les erreurs
        echo json_encode(['success' => false, 'message' => 'Erreur serveur: ' . $e->getMessage()]);
    }
} else {
    // Requête non autorisée
    echo json_encode(['success' => false, 'message' => 'Requête invalide']);
}
?>
