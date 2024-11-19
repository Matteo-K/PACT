<?php
header('Content-Type: application/json');

$cleAPI = 'votre_clé_api'; // Clé API stockée en sécurité côté serveur
$endpoint = 'https://maps.googleapis.com/maps/api/directions/json';

// Récupérer les paramètres passés depuis le client
$params = $_GET;
$params['key'] = $apiKey; // Ajouter la clé API côté serveur

// Construire l'URL
$url = $endpoint . '?' . http_build_query($params);

// Appeler l'API Google Maps
$response = file_get_contents($url);

// Retourner la réponse au client
echo $response;
