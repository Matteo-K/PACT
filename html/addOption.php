<?php

require_once 'config.php';

$offreId = $_POST['idOffre'];

if ($_POST['type'] == 'ajout') {
    if ($_POST['nomOption']=='ALaUne') {
        $prix = $_POST['nbWeek']*20;
    } else {
        $prix = $_POST['nbWeek']*10;
    }

    $stmt = $conn->prepare("SELECT statut FROM pact.offres WHERE idoffre=?");
    $stmt->execute([$offreId]);
    $EnLigne = $stmt->fetchAll();
    $stmt = $conn->prepare("SELECT * FROM pact.option WHERE idoffre = ? AND nomoption = ? and datefin >= CURRENT_DATE");
    $stmt->execute([$offreId,'ALaUne']);
    $resultUne = $stmt->fetchAll();
    $stmt->execute([$offreId,'EnRelief']);
    $resultRelief = $stmt->fetchAll();
    
    if ($EnLigne[0]['statut'] == 'actif' && ($_POST['nomOption']=='ALaUne'? !$resultUne : !$resultRelief)) {
        $duree = $_POST['nbWeek'] * 7;
        $date = new DateTime();
        $date->modify("+$duree days"); // Ajout de la durée
        $formattedDate = $date->format('Y-m-d'); // Conversion de l'objet DateTime en format SQL compatible
                
        $idoption = $offreId;
                        
        $stmt = $conn->prepare("INSERT INTO pact.option (idOffre,dateLancement,dateFin,duree_total,prix_total,nomOption) VALUES (?,CURRENT_DATE,?,?,?,?)");
        $stmt->execute([$_POST['idOffre'], $formattedDate , $_POST['nbWeek'], $prix , $_POST['nomOption']]);
    }else {
        $stmt = $conn->prepare("SELECT * FROM pact.option WHERE idoffre = ? AND nomoption = ? AND datefin IS NULL");
        $stmt->execute([$offreId,$_POST['nomOption']]);
        $ttOpt = $stmt->fetchAll();
        print_r($ttOpt);
        if (count($ttOpt)<1) {
            $stmt = $conn->prepare("INSERT INTO pact.option (idOffre,dateLancement,dateFin,duree_total,prix_total,nomOption) VALUES (?,NULL,NULL,?,?,?)");
            $stmt->execute([$_POST['idOffre'], $_POST['nbWeek'], $prix , $_POST['nomOption']]);
        }
    }
}

$stmt = $conn->prepare("SELECT * FROM pact.option");
$stmt->execute();


echo <<<HTML
<form id="redirectForm" method="POST" action="detailsOffer.php">
    <input type="hidden" name="idoffre" value="{$offreId}">
</form>
<script>
    document.getElementById('redirectForm').submit();
</script>
HTML;

exit;
?>