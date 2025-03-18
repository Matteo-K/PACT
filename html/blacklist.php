<?php

require_once "config.php";

$donnees = json_decode(file_get_contents('php://input'), true);
$idAvis = $donnees['idC'];
$idOffre = $donnees['idOffre'];

if ($idAvis != null) {

    $stmt = $conn->prepare("SELECT dureeblacklistage, uniteblacklist FROM pact._parametre");
    $stmt->execute();
    $row = $stmt->fetch();
    $interval = $row['dureeblacklistage'];
    $unite = $row['uniteblacklist'];
    echo ($interval . "\n");
    echo ($idOffre . "\n");
    echo ($idAvis . "\n");

    switch ($unite) {
        case 'minutes':
            $intervalSQL = "INTERVAL ? MINUTE";
            break;
        case 'heures':
            $intervalSQL = "INTERVAL ? HOUR";
            break;
        case 'jours':
            $intervalSQL = "INTERVAL ? DAY";
            break;
        default:
            $intervalSQL = "INTERVAL ? DAY";
            break;
    }
    $stmt = $conn->prepare("INSERT INTO pact._blacklist(idc, idoffre, dateblacklist, datefinblacklist) 
                            VALUES (?, ?, current_timestamp, DATE_ADD(current_timestamp, $intervalSQL))");
    $stmt->execute([$idAvis, $idOffre, $interval]);

    http_response_code(200);
} else {
    http_response_code(400);
    exit;
}

?>
