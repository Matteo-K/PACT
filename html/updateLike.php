<?php
require_once 'connect_bdd.php';

header('Content-Type: application/json');

// Récupération de l'action et de l'id du commentaire depuis la requête POST
$data = json_decode(file_get_contents('php://input'), true);
$action = isset($data['action']) ? $data['action'] : '';
$idCommentaire = isset($data['idavis']) ? (int) $data['idavis'] : 0;

// Vérifier si l'action est valide
if (!in_array($action, ['like', 'dislike', 'unlike', 'undislike']) || $idCommentaire <= 0) {
    echo json_encode(['success' => false, 'message' => 'Action invalide ou ID du commentaire incorrect']);
    exit();
}

try {
    // Début de la transaction pour garantir l'intégrité des données
    $conn->beginTransaction();

    // Préparer la requête pour récupérer le nombre de likes et de dislikes
    $stmt = $conn->prepare("SELECT nblike, nbdislike FROM pact._commentaire WHERE idc = :idc");
    $stmt->bindParam(':idc', $idCommentaire, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        // Récupérer les valeurs actuelles
        $nbLike = $result['nblike'];
        $nbDislike = $result['nbdislike'];

        // Mettre à jour les compteurs en fonction de l'action
        if ($action === 'like') {
            $nbLike++;
        } elseif ($action === 'dislike') {
            $nbDislike++;
        } elseif($action === 'unlike'){
            $nbLike--;
        } elseif($action === 'undislike'){
            $nbDislike--;
        }

        // Mettre à jour la base de données avec les nouvelles valeurs
        $stmt = $conn->prepare("UPDATE pact._commentaire SET nblike = :nblike, nbdislike = :nbdislike WHERE idc = :idc");
        $stmt->bindParam(':nblike', $nbLike, PDO::PARAM_INT);
        $stmt->bindParam(':nbdislike', $nbDislike, PDO::PARAM_INT);
        $stmt->bindParam(':idc', $idCommentaire, PDO::PARAM_INT);
        $stmt->execute();

        // Validation de la transaction
        $conn->commit();

        // Retourner les nouvelles valeurs au format JSON
        echo json_encode([
            'success' => true,
            'nblike' => $nbLike,
            'nbdislike' => $nbDislike
        ]);
    } else {
        // Si le commentaire n'existe pas
        echo json_encode(['success' => false, 'message' => 'Commentaire introuvable']);
    }
} catch (PDOException $e) {
    // Annuler la transaction en cas d'erreur
    $conn->rollBack();
    echo json_encode(['success' => false, 'message' => 'Erreur de mise à jour: ' . $e->getMessage()]);
}
?>
