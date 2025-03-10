<?php
// Désactiver la mise en cache
header("Content-Type: image/png");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");

function loadEnv($filePath) {
    if (!file_exists($filePath)) {
        throw new Exception("Le fichier .env n'existe pas !");
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lines as $line) {
        // Ignorer les commentaires
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        // Séparer clé et valeur
        list($key, $value) = explode('=', $line, 2);

        // Supprimer les espaces et guillemets autour de la valeur
        $key = trim($key);
        $value = trim($value);
        $value = trim($value, '"');

        // Définir la variable dans $_ENV et $_SERVER
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}

loadEnv("/.env");

// 🔹 Remplace VOTRE_CLE_API par ta vraie clé API
$apiKey = $_ENV['API_KEY'];

// 🔹 Récupérer les coordonnées {z}/{x}/{y} depuis la requête
if (!isset($_GET['z'], $_GET['x'], $_GET['y'])) {
    http_response_code(400);
    echo "Paramètres manquants";
    exit;
}

$z = intval($_GET['z']);
$x = intval($_GET['x']);
$y = intval($_GET['y']);

// 🔹 Construire l'URL vers Thunderforest
$url = "https://tile.thunderforest.com/atlas/$z/$x/$y.png?apikey=$apiKey";

// 🔹 Récupérer l'image et la renvoyer
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code == 200) {
    echo $response;
} else {
    http_response_code($http_code);
    echo "Erreur de chargement des tuiles";
}
?>
