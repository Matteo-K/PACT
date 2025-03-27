<?php

header("Content-Type: application/json");
error_reporting(E_ALL); ini_set("display_errors", 1);
require_once "config.php";



$donnees = json_decode(file_get_contents('php://input'), true);
$idAvis = $donnees['idC'];
$idOffre = $donnees['idOffre'];

$stmt = $conn->prepare("SELECT * FROM pact._avis WHERE idc = ?");
$stmt->execute([$idAvis]);


if (!($stmt->fetch())['blacklist']) {

    // Récupération de la durée et de l'unité d'interdiction
    $stmt = $conn->prepare("SELECT dureeblacklistage, uniteblacklist FROM pact._parametre");
    $stmt->execute();
    $row = $stmt->fetch();
    $interval = $row['dureeblacklistage'];
    $unite = $row['uniteblacklist'];

    // Vérification et conversion de l'unité pour PostgreSQL
    switch ($unite) {
        case 'minutes':
            $intervalSQL = "{$interval} minutes";
            break;
        case 'heures':
            $intervalSQL = "{$interval} hours";
            break;
        case 'jours':
            $intervalSQL = "{$interval} days";
            break;
        default:
            $intervalSQL = "{$interval} days";
            break;
    }

    // Préparation de la requête PostgreSQL avec CONCAT pour INTERVAL
    $stmt = $conn->prepare("INSERT INTO pact._blacklist (idc, idoffre, dateblacklist, datefinblacklist) 
                            VALUES (?, ?, current_timestamp, current_timestamp + INTERVAL '" . $intervalSQL . "')");

    $stmt->execute([$idAvis, $idOffre]);

    $stmt = $conn->prepare("UPDATE pact._avis set blacklist = true WHERE idc = ?");
    $stmt->execute([$idAvis]);

    http_response_code(200);
} else {
    http_response_code(400);
    exit;
}

?>
