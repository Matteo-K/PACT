<?php

require_once "../config.php";

$stmt = $conn->prepare("SELECT * FROM pact._historiquestatut where dureeenligne is null");
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$date = NEW DateTime();
$dateString = $date->format('Y-m-d');

foreach ($results as $key => $value) {
    $dateLancement = NEW DateTime($value['datelancement']);
    $duree = $date->diff($dateLancement);
    $dureeString = (string) $duree->days;
    $idOffre = $value['idoffre'];

    $stmt = $conn->prepare("UPDATE pact._historiquestatut SET dureeenligne = $dureeString where dureeenligne is null and idoffre = $idOffre");
    $stmt->execute();

    $ins = $conn->prepare("INSERT INTO pact._historiquestatut(idoffre,datelancement,dureeenligne) VALUES ($idOffre,$date,NULL)");
    $ins->execute();
}

$stmt = $conn->prepare("SELECT idoffre FROM pact._historiquestatut WHERE datelancement >= NOW() - INTERVAL '1 month' group by idoffre");
$stmt->execute();
$id = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($id as $key => $value) {
    $idOffre2 = $value['idoffre'];

    $ins = $conn->prepare("INSERT INTO pact._facturation(dateFactue,idOffre)VALUES ($date,$idOffre2)");
    $ins->execute();
}
?>