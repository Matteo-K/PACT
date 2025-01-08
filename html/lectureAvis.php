<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

/*

$data = json_decode(file_get_contents('php://input'));
$id = json_decode($data[0]) ?? null;

// Vérification si 'id' est bien défini
if ($id != null) {

    $stmt = $conn->prepare("UPDATE pact._avis SET lu = true WHERE idc = ?");
    $stmt = $conn->prepare("INSERT INTO pact._tag (nomtag) VALUES (?)");
    $stmt->execute($id);
    $result = $stmt->fetch();

}
else{
    http_response_code(400); // Aucun contenu, succès
    exit;
}

*/


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $input = json_decode(file_get_contents('php://input'), true);
    

    if (isset($input['id'])) {
        $id = htmlspecialchars($input['id']); // Sécurisation de l'ID

        try {
            $stmt = $conn->prepare("UPDATE pact._avis SET lu = true WHERE idc = ?");
            $stmt->execute([$id]); // Passe l'ID sous forme de tableau

            // Prépare une réponse JSON
            $response = ['message' => 'Mise à jour réussie.', 'success' => true];
            header('Content-Type: application/json');
            echo json_encode($response);
        } catch (Exception $e) {
            http_response_code(500); // Erreur serveur
            echo json_encode(['message' => 'Erreur serveur : ' . $e->getMessage(), 'success' => false]);
        }
    } else {
        http_response_code(400); // Mauvaise requête
        echo json_encode(['message' => 'Aucun ID reçu.', 'success' => false]);
    }
    exit;
} else {
    http_response_code(405); // Méthode non autorisée
    echo json_encode(['message' => 'Méthode non autorisée.']);
    exit;
}

