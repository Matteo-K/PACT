<?php
// Inclure les paramètres de connexion à la base de données
require_once 'config.php'; // Assurez-vous que $conn est initialisé avec PDO

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier et récupérer les données envoyées
    $itemId = isset($_POST['id']) ? (int) $_POST['id'] : null;
    $action = isset($_POST['action']) ? $_POST['action'] : null;

    if (!$itemId || !$action) {
        echo json_encode(['success' => false, 'message' => 'Données invalides.']);
        exit;
    }

    try {
        // Initialiser une transaction
        $conn->beginTransaction();

        // Vérifier si l'élément existe
        $query = $conn->prepare('SELECT * FROM pact._commentaire WHERE id = :id');
        $query->execute(['id' => $itemId]);
        $evaluation = $query->fetch(PDO::FETCH_ASSOC);

        if (!$evaluation) {
            echo json_encode(['success' => false, 'message' => 'Évaluation introuvable.']);
            exit;
        }

        // Mettre à jour le compteur en fonction de l'action
        switch ($action) {
            case 'like':
                $updateQuery = 'UPDATE pact._commentaire SET likes = likes + 1 WHERE id = :id';
                break;
            case 'unlike':
                $updateQuery = 'UPDATE pact._commentaire SET likes = GREATEST(likes - 1, 0) WHERE id = :id';
                break;
            case 'dislike':
                $updateQuery = 'UPDATE pact._commentaire SET dislikes = dislikes + 1 WHERE id = :id';
                break;
            case 'undislike':
                $updateQuery = 'UPDATE pact._commentaire SET dislikes = GREATEST(dislikes - 1, 0) WHERE id = :id';
                break;
            default:
                echo json_encode(['success' => false, 'message' => 'Action non valide.']);
                exit;
        }

        // Exécuter la mise à jour
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->execute(['id' => $itemId]);

        // Valider la transaction
        $conn->commit();

        // Envoyer une réponse JSON
        echo json_encode([
            'success' => true,
            'message' => 'Action effectuée avec succès.',
            'action' => $action,
            'item_id' => $itemId,
        ]);
    } catch (Exception $e) {
        // Annuler la transaction en cas d'erreur
        $conn->rollBack();

        // Envoyer une réponse JSON en cas d'erreur
        echo json_encode(['success' => false, 'message' => 'Erreur : ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
}
?>
