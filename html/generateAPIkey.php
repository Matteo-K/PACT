<?php
function generateApiKey() {
    // Générer 128 bits aléatoires (16 octets)
    $randomBytes = random_bytes(16);
    
    // Convertir en hexadécimal
    $apiKey = bin2hex($randomBytes);
    
    return $apiKey;
}

// Générer une nouvelle clé API
$newApiKey = generateApiKey();

echo "Nouvelle clé API générée : " . $newApiKey;
?>
