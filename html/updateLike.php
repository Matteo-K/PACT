<?php
// Connexion à la base de données
include 'connect_params.php'; // Assurez-vous que ce fichier contient les bonnes informations de connexion à la base de données

// Récupérer les données envoyées via JSON
$data = json_decode(file_get_contents('php://input'), true);

// Vérifier que les données sont bien reçues
if (isset($data['action']) && isset($data['id'])) {
    $action = $data['action'];
    $id = $data['id'];

    // Initialiser les variables pour les likes et dislikes
    $nblike = 0;
    $nbdislike = 0;

    // Mettre à jour les compteurs en fonction de l'action
    switch ($action) {
        case 'like':
            $query = "UPDATE pact._commentaire SET nblike = nblikes + 1 WHERE id = :id";
            break;
        case 'unlike':
            $query = "UPDATE pact._commentaire SET nblike = nblike - 1 WHERE id = :id";
            break;
        case 'dislike':
            $query = "UPDATE pact._commentaire SET nbdislike = nbdislike + 1 WHERE id = :id";
            break;
        case 'undislike':
            $query = "UPDATE pact._commentaire SET nbdislike = nbdislike - 1 WHERE id = :id";
            break;
        default:
            // Si l'action est invalide, on renvoie une erreur
            echo json_encode(['success' => false, 'message' => 'Action invalide']);
            exit;
    }

    // Préparer la requête SQL
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    // Exécuter la requête
    if ($stmt->execute()) {
        // Récupérer les nouvelles valeurs des likes et dislikes
        $stmt = $conn->prepare("SELECT nblike, nbdislike FROM pact._commentaire WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // Renvoyer les données mises à jour
            echo json_encode([
                'success' => true,
                'nblike' => $result['likes'],
                'nbdislike' => $result['dislikes']
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Évaluation introuvable']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Données manquantes']);
}
?>
