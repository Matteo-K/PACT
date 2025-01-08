<?php

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
