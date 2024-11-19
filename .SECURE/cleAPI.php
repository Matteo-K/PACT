<?php
require_once __DIR__ . "/../html/vendor";
    // Définir le chemin du fichier .env
$envPath = __DIR__ ;

// Charger le fichier .env
$dotenv = Dotenv\Dotenv::createImmutable($envPath);
$dotenv->load();

// Utiliser la clé API
$googleMapsApiKey = $_ENV['GOOGLE_MAPS_API_KEY'];
?>