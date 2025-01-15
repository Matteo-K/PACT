
<?php

require_once "config.php";

$donnees = json_decode(file_get_contents('php://input'), true);
$idAvis = $donnees['idC'];
$idUser = $donnees['idU'];
$raison = $donnees['motif'];
$complement = $donnees['complement'];

if ($idAvis != null) {
    $stmt = $conn->prepare("INSERT INTO pact._signalementC(idu, idc, dtsignalement, raison, complement) VALUES (?, ?, current_timestamp, ?, ?)");
    $stmt->execute([$idUser, $idAvis, $raison, $complement]); // Passe l'ID sous forme de tableau
    http_response_code(200); //succès
}
else{
    http_response_code(400); //echec
    exit;
}

?>