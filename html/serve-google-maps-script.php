<?php
// Veillez à ce que ce fichier ne soit accessible que par le serveur et non par l'utilisateur
header('Content-Type: application/javascript');

// Chargez le fichier JavaScript depuis le dossier sécurisé
$jsFilePath = __DIR__ . '/../.SECURE/google-maps-loader.js';

// Vérifiez si le fichier existe
if (file_exists($jsFilePath)) {
    // Lisez et renvoyez le contenu du fichier JS
    echo file_get_contents($jsFilePath);
} else {
    // Si le fichier n'est pas trouvé, affichez une erreur (en production, vous pourriez rediriger ou afficher un message d'erreur plus détaillé)
    echo 'Erreur: fichier JS introuvable.';
}
