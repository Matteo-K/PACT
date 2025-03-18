<?php

require_once "config.php";

$donnees = json_decode(file_get_contents('php://input'), true);
$idAvis = $donnees['idC'];
$idOffre = $donnees['idOffre'];

if ($idAvis != null) {

    // Récupération de la durée et de l'unité d'interdiction
    $stmt = $conn->prepare("SELECT dureeblacklistage, uniteblacklist FROM pact._parametre");
    $stmt->execute();
    $row = $stmt->fetch();
    $interval = $row['dureeblacklistage'];
    $unite = $row['uniteblacklist'];

    echo ($interval . "\n");
    echo ($idOffre . "\n");
    echo ($idAvis . "\n");

    // Vérification et conversion de l'unité pour PostgreSQL
    switch ($unite) {
        case 'minutes':
            $intervalSQL = "$interval minutes";
            break;
        case 'heures':
            $intervalSQL = "$interval hours";
            break;
        case 'jours':
            $intervalSQL = "$interval days";
            break;
        default:
            $intervalSQL = "$interval days";
            break;
    }

    // Préparation et exécution de l'INSERT avec PostgreSQL
    $stmt = $conn->prepare("INSERT INTO pact._blacklist (idc, idoffre, dateblacklist, datefinblacklist) 
                            VALUES (?, ?, current_timestamp, current_timestamp + INTERVAL ?)");
    $stmt->execute([$idAvis, $idOffre, $intervalSQL]);

    http_response_code(200);
} else {
    http_response_code(400);
    exit;
}

?>
