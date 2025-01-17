<?php
require_once "./config.php";

function generateApiKey() {
    // Générer 128 bits aléatoires (16 octets)
    $randomBytes = random_bytes(16);
    
    // Convertir en hexadécimal
    $apiKey = bin2hex($randomBytes);
    
    return $apiKey;
}

if($isLoggedIn){
    $newApiKey = generateApiKey();

    try{
        $stmt = $conn -> prepare("UPDATE pact.utilisateur set apikey = $newApiKey WHERE idu = $idUser");
        $stmt -> execute();
        json_encode(["status" => "success", "apikey" => $newApiKey]);

        exit;
    } catch(PDOException $e){
        echo json_encode(["status" => "error", "message" => "Erreur lors de la mise à jour de la clé API : " . $e->getMessage()]);
        exit;
    }
}

header("location: /");
exit;
?>
