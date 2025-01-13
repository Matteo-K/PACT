<?php
require_once 'config.php';

header('Content-Type: application/json');

// Récupération de l'action depuis la requête POST
$data = json_decode(file_get_contents('php://input'), true);
$action = isset($data['action']) ? $data['action'] : '';
$id = isset($data['id']) ? (int)$data['id'] : 0;

// Vérifier si l'action et l'ID sont valides
if (!$id || !in_array($action, ['like', 'dislike', 'unlike', 'undislike'])) {
    echo json_encode(['success' => false,'action'=>$action, 'id'=> $id,'message' => 'Action ou ID invalide']);
    exit();
}

try {
    // Préparer la requête pour récupérer le nombre de likes et de dislikes
    $stmt = $conn->prepare("SELECT nblike, nbdislike FROM pact._commentaire WHERE idc = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        // Récupérer les valeurs actuelles
        $nbLike = $result['nblike'];
        $nbDislike = $result['nbdislike'];

        // Mettre à jour les compteurs en fonction de l'action
        switch ($action) {
            case 'like':
                $nbLike++;
                break;
            case 'dislike':
                $nbDislike++;
                break;
            case 'unlike':
                $nbLike = max($nbLike - 1, 0); // Pour éviter que le like devienne négatif
                break;
            case 'undislike':
                $nbDislike = max($nbDislike - 1, 0); // Pour éviter que le dislike devienne négatif
                break;
        }

        // Mettre à jour la base de données avec les nouvelles valeurs
        $stmt = $conn->prepare("UPDATE pact._commentaire SET nblike = :nblike, nbdislike = :nbdislike WHERE idc = :id");
        $stmt->bindParam(':nblike', $nbLike, PDO::PARAM_INT);
        $stmt->bindParam(':nbdislike', $nbDislike, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Retourner les nouvelles valeurs au format JSON
        echo json_encode([
            'success' => true,
            'message' => 'Ajout avec succès',
            'nblike' => $nbLike,
            'nbdislike' => $nbDislike
        ]);
    } else {
        // Si le commentaire n'existe pas
        echo json_encode(['success' => false, 'message' => 'Commentaire introuvable']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur de mise à jour: ' . $e->getMessage()]);
}
?>
