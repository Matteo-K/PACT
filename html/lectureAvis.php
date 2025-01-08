<?php

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'] ?? null;

// Vérification si 'id' est bien défini
if ($id != null) {

    $stmt = $conn->prepare("UPDATE pact._avis SET lu=true WHERE idc = ?");
    $stmt->execute($input[$id]);
    $result = $stmt->fetch();

}
else{
    http_response_code(400); // Aucun contenu, succès
    exit;
}
