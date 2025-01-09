<?php

require_once "config.php";  


$donnees = json_decode(file_get_contents('php://input'), true);
$id = $donnees['id']; // Sécurisation de l'ID

// Vérification si 'id' est bien défini
if ($id != null) {
    
    $stmt = $conn->prepare("UPDATE pact._avis SET lu = true WHERE idc = ?");
    $stmt->execute([$id]); // Passe l'ID sous forme de tableau
}
else{
    http_response_code(400); // Aucun contenu, succès
    exit;
}
