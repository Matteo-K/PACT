<?php

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
    // Récupération de la donnée JSON envoyée
    $input = json_decode(file_get_contents('php://input'), true);

    // Vérification si 'id' est bien défini
    if (isset($input['id'])) {
        $id = htmlspecialchars($input['id']); // Sécurisation de l'ID

        // Simuler un traitement (exemple : recherche en base de données)
        $stmt = $conn->prepare("UPDATE pact._avis SET lu = true WHERE idc = ?");
        $stmt->execute($input['id']);
        $result = $stmt->fetch();

        // Retourner une réponse JSON
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    } else {
        // Erreur si l'ID n'est pas défini
        http_response_code(400); // Code HTTP : Mauvaise requête
        echo json_encode(['message' => 'Aucun ID reçu.', 'success' => false]);
        exit;
    }
} else {
    // Méthode HTTP non supportée
    http_response_code(405); // Méthode non autorisée
    echo json_encode(['message' => 'Méthode non autorisée.']);
    exit;
}

