<?php
// Inclure les paramètres de connexion à la base de données
require_once 'config.php'; // Assurez-vous que $conn est initialisé avec PDO

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données envoyées
    $itemId = isset($_POST['id']) ? (int) $_POST['id'] : null;
    $action = isset($_POST['action']) ? $_POST['action'] : null;

    // Valider les données reçues
    if (!$itemId || $itemId <= 0 || !$action) {
        echo "pablo";
        echo json_encode(['success' => false, 'message' => 'Données invalides.']);
        exit;
    }

    // Enumération des actions valides
    $validActions = ['like', 'unlike', 'dislike', 'undislike'];
    if (!in_array($action, $validActions)) {
        echo "pablo";
        echo json_encode(['success' => false, 'message' => 'Action non valide.']);
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

        // Déterminer la requête de mise à jour en fonction de l'action
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
        }

        // Exécuter la requête de mise à jour
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->execute(['id' => $itemId]);

        // Récupérer les nouveaux compteurs
        $updatedQuery = $conn->prepare('SELECT likes, dislikes FROM pact._commentaire WHERE id = :id');
        $updatedQuery->execute(['id' => $itemId]);
        $updatedData = $updatedQuery->fetch(PDO::FETCH_ASSOC);

        // Valider la transaction
        $conn->commit();

        // Réponse JSON avec les nouveaux compteurs
        echo "pablo";
        echo json_encode([
            'success' => true,
            'message' => 'Action effectuée avec succès.',
            'action' => $action,
            'item_id' => $itemId,
            'likes' => $updatedData['likes'],
            'dislikes' => $updatedData['dislikes'],
        ]);
    } catch (Exception $e) {
        // Annuler la transaction en cas d'erreur
        $conn->rollBack();

        // Enregistrer l'erreur et retourner une réponse générique
        error_log($e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Une erreur s\'est produite.']);
    }
} else {
    echo "pablo";
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
}
