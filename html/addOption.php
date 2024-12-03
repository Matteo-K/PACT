<?php

require_once 'config.php';
print_r($_POST);


if ($_POST['type'] == 'ajout') {
    if ($_POST['nomOption']=='ALaUne') {
        $duree = $_POST['nbWeek']*20;
    } else {
        $duree = $_POST['nbWeek']*10;
    }
    $stmt = $conn->prepare("INSERT INTO pact.option (idOffre,dateLancement,dateFin,duree_total,prix_total,nomOption) VALUES (?,NULL,NULL,?,?,?)");
    $stmt->execute([$_POST['idOffre'], $_POST['nbWeek'], $duree , $_POST['nomOption']]);
}

$stmt = $conn->prepare("SELECT * FROM pact.option");
$stmt->execute();
print_r($stmt->fetchAll());
?>