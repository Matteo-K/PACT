<?php

require_once 'config.php';
print_r($_POST);


if ($_POST['type'] == 'ajout') {
    $duree = $_POST['nbWeekEnRelief']*10;
    $stmt = $conn->prepare("INSERT INTO pact.option (idOffre,dateLancement,dateFin,duree_total,prix_total,nomOption) VALUES (?,NULL,NULL,?,?,?)");
    $stmt->execute([$_POST['idOffre'], $_POST['nbWeekALaUne'], $duree , $value['nomOption']]);
}
?>