<?php

require_once 'config.php';

$offreId = $_POST['idOffre'];
print_r($_POST);
if ($_POST['type'] == 'ajout') {
    if ($_POST['nomOption']=='ALaUne') {
        $prix = $_POST['nbWeek']*20;
    } else {
        $prix = $_POST['nbWeek']*10;
    }

    $date = isset($_POST['dtcheck']) ? true : false;

    $stmt = $conn->prepare("SELECT statut FROM pact.offres WHERE idoffre=?");
    $stmt->execute([$offreId]);
    $EnLigne = $stmt->fetchAll();
    $stmt = $conn->prepare("SELECT * FROM pact.option WHERE idoffre = ? AND nomoption = ? and datefin > CURRENT_DATE");
    $stmt->execute([$offreId,'ALaUne']);
    $resultUne = $stmt->fetchAll();
    $stmt->execute([$offreId,'EnRelief']);
    $resultRelief = $stmt->fetchAll();
    
    if ($EnLigne[0]['statut'] == 'actif' && ($_POST['nomOption']=='ALaUne'? !$resultUne : !$resultRelief) && !$date) {
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
        if (count($ttOpt)<1) {
            if ($date) {
                $duree = $_POST['nbWeek'] * 7;
                $dt = NEW DateTime($_POST['customDate']);
                $dt->modify("+$duree days"); // Ajout de la durée
                $formattedDate = $dt->format('Y-m-d');
                $stmt = $conn->prepare("INSERT INTO pact.option (idOffre,dateLancement,dateFin,duree_total,prix_total,nomOption) VALUES (?,?,?,?,?,?)");
                $stmt->execute([$_POST['idOffre'],$_POST['customDate'],$formattedDate, $_POST['nbWeek'], $prix , $_POST['nomOption']]);
            }else {
                $stmt = $conn->prepare("INSERT INTO pact.option (idOffre,dateLancement,dateFin,duree_total,prix_total,nomOption) VALUES (?,NULL,NULL,?,?,?)");
                $stmt->execute([$_POST['idOffre'], $_POST['nbWeek'], $prix , $_POST['nomOption']]);
            }
        }
    }
}elseif ($_POST['type'] == 'resilier') {
    $idoption = $_POST['idoption'];
    $stmt = $conn->prepare("DELETE FROM pact._option_offre Where idoption = ?");
    $stmt->execute([$idoption]);
    $stmt = $conn->prepare("DELETE FROM pact._dateOption Where idoption = ?");
    $stmt->execute([$idoption]);
} elseif ($_POST['type'] == 'arreter') {
    $idoption = $_POST['idoption'];

    $datetime1 = new DateTime();
    $stmt = $conn->prepare("SELECT * FROM pact._dateOption WHERE idoption = ?");
    $stmt->execute([$idoption]);
    $heure = $stmt->fetchAll();
    $datetime2 = new DateTime($heure[0]['datelancement']);

    // Calculer la différence en jours
    $interval = $datetime1->diff($datetime2);
    $days = $interval->days; // Obtenir le nombre total de jours

    // Calculer les semaines et arrondir au supérieur
    $weeks = ceil($days / 7) == 0 ? 1 : ceil($days / 7);

    $tarif = $_POST['nom']=='ALaUne'?20:10;
    $prix = $weeks * $tarif;

    $stmt = $conn->prepare("UPDATE pact._dateOption SET duree = ?, prix = ?, datefin = CURRENT_DATE WHERE idoption = ?");
    $stmt->execute([$weeks,$prix,$idoption]);
}

$stmt = $conn->prepare("SELECT * FROM pact.option");
$stmt->execute();


echo <<<HTML
<form id="redirectForm" method="POST" action="detailsOffer.php">
    <input type="hidden" name="idoffre" value="{$offreId}">
    <input type="hidden" name="popup">
</form>
<script>
   document.getElementById('redirectForm').submit();
</script>
HTML;

exit;
?>