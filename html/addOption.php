<?php

require_once 'config.php';
$pasok=false;
$offreId = $_POST['idOffre'];
if ($_POST['type'] == 'ajout') {
    $stmt = $conn->prepare("SELECT prixoffre FROM pact._option WHERE nomoption=?");
    if ($_POST['nomOption']=='ALaUne') {
        $stmt->execute(['ALaUne']);
        $prixb = ($stmt->fetchAll())[0]['prixoffre'];
        $prix = $_POST['nbWeek']*$prixb;
    } else {
        $stmt->execute(['EnRelief']);
        $prixb = ($stmt->fetchAll())[0]['prixoffre'];
        $prix = $_POST['nbWeek']*$prixb;
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

    $stmt = $conn->prepare("SELECT * FROM pact.option WHERE idoffre = ? AND nomoption = ? AND datefin>CURRENT_DATE");
    $stmt->execute([$offreId,$_POST['nomOption']]);
    $ttOpt = $stmt->fetchAll();
    if (!$ttOpt) {
        $duree = $_POST['nbWeek'] * 7;
        $dt = NEW DateTime($_POST['customDate']);
        $dt->modify("+$duree days"); // Ajout de la durée
        $formattedDate = $dt->format('Y-m-d');
        $stmt = $conn->prepare("INSERT INTO pact.option (idOffre,dateLancement,dateFin,duree_total,prix_total,nomOption) VALUES (?,?,?,?,?,?)");
        $stmt->execute([$_POST['idOffre'],$_POST['customDate'],$formattedDate, $_POST['nbWeek'], $prix , $_POST['nomOption']]);
    }else {
        $ttOpt = $ttOpt[0];
        $dtL = new DateTime($ttOpt['datelancement']); // Date de lancement de la première plage
        $dtF = new DateTime($ttOpt['datefin']); // Date de fin de la première plage
        $dtL2 = new DateTime($_POST['customDate']); // Date de lancement de la deuxième plage
        $duree = $_POST['nbWeek'] * 7; // Durée en jours
        $dtF2 = clone $dtL2; // Cloner la date de lancement pour calculer la fin
        $dtF2->modify("+$duree days"); // Ajouter la durée pour obtenir la date de fin
        if ($dtF2 < $dtL || $dtL2 > $dtF) {
            $duree = $_POST['nbWeek'] * 7;
            $dt = NEW DateTime($_POST['customDate']);
            $dt->modify("+$duree days"); // Ajout de la durée
            $formattedDate = $dt->format('Y-m-d');
            $stmt = $conn->prepare("INSERT INTO pact.option (idOffre,dateLancement,dateFin,duree_total,prix_total,nomOption) VALUES (?,?,?,?,?,?)");
            $stmt->execute([$_POST['idOffre'],$_POST['customDate'],$formattedDate, $_POST['nbWeek'], $prix , $_POST['nomOption']]);
        } else {
            $pasok=2;
        }
    }
    
    // if ($EnLigne[0]['statut'] == 'actif' && ($_POST['nomOption']=='ALaUne'? !$resultUne : !$resultRelief) && !$date) {
    //     $duree = $_POST['nbWeek'] * 7;
    //     $date = new DateTime();
    //     $date->modify("+$duree days"); // Ajout de la durée
    //     $formattedDate = $date->format('Y-m-d'); // Conversion de l'objet DateTime en format SQL compatible
                
    //     $idoption = $offreId;
                        
    //     $stmt = $conn->prepare("INSERT INTO pact.option (idOffre,dateLancement,dateFin,duree_total,prix_total,nomOption) VALUES (?,CURRENT_DATE,?,?,?,?)");
    //     $stmt->execute([$_POST['idOffre'], $formattedDate , $_POST['nbWeek'], $prix , $_POST['nomOption']]);
    // }else {
    //     $stmt = $conn->prepare("SELECT * FROM pact.option WHERE idoffre = ? AND nomoption = ? AND datefin IS NULL");
    //     $stmt->execute([$offreId,$_POST['nomOption']]);
    //     $ttOpt = $stmt->fetchAll();
    //     if (count($ttOpt)<1) {
    //         if ($date) {
    //             $stmt = $conn->prepare("SELECT * FROM pact.option WHERE idoffre = ? AND nomoption = ? AND datefin>CURRENT_DATE");
    //             $stmt->execute([$offreId,$_POST['nomOption']]);
    //             $ttOpt = $stmt->fetchAll();
    //             if (!$ttOpt) {
    //                 $duree = $_POST['nbWeek'] * 7;
    //                 $dt = NEW DateTime($_POST['customDate']);
    //                 $dt->modify("+$duree days"); // Ajout de la durée
    //                 $formattedDate = $dt->format('Y-m-d');
    //                 $stmt = $conn->prepare("INSERT INTO pact.option (idOffre,dateLancement,dateFin,duree_total,prix_total,nomOption) VALUES (?,?,?,?,?,?)");
    //                 $stmt->execute([$_POST['idOffre'],$_POST['customDate'],$formattedDate, $_POST['nbWeek'], $prix , $_POST['nomOption']]);
    //             }else {
    //                 $ttOpt = $ttOpt[0];
    //                 $dtL = new DateTime($ttOpt['datelancement']); // Date de lancement de la première plage
    //                 $dtF = new DateTime($ttOpt['datefin']); // Date de fin de la première plage

    //                 $dtL2 = new DateTime($_POST['customDate']); // Date de lancement de la deuxième plage
    //                 $duree = $_POST['nbWeek'] * 7; // Durée en jours
    //                 $dtF2 = clone $dtL2; // Cloner la date de lancement pour calculer la fin
    //                 $dtF2->modify("+$duree days"); // Ajouter la durée pour obtenir la date de fin

    //                 if ($dtF2 < $dtL || $dtL2 > $dtF) {
    //                     $duree = $_POST['nbWeek'] * 7;
    //                     $dt = NEW DateTime($_POST['customDate']);
    //                     $dt->modify("+$duree days"); // Ajout de la durée
    //                     $formattedDate = $dt->format('Y-m-d');
    //                     $stmt = $conn->prepare("INSERT INTO pact.option (idOffre,dateLancement,dateFin,duree_total,prix_total,nomOption) VALUES (?,?,?,?,?,?)");
    //                     $stmt->execute([$_POST['idOffre'],$_POST['customDate'],$formattedDate, $_POST['nbWeek'], $prix , $_POST['nomOption']]);
    //                 } else {
    //                     $pasok=2;
    //                 }
    //             }
    //         }else {
    //             $stmt = $conn->prepare("SELECT * FROM pact.option WHERE idoffre = ? AND nomoption = ? AND datefin>CURRENT_DATE");
    //             $stmt->execute([$offreId,$_POST['nomOption']]);
    //             $ttOpt = $stmt->fetchAll();
    //             if (count($ttOpt)<2) {
    //                 if ($ttOpt) {
    //                     $duree = $_POST['nbWeek'] * 7;
    //                     $dt = NEW DateTime($ttOpt[0]['datefin']);
    //                     $dt->modify("+$duree days"); // Ajout de la durée
    //                     $formattedDate = $dt->format('Y-m-d');
    //                     $stmt = $conn->prepare("INSERT INTO pact.option (idOffre,dateLancement,dateFin,duree_total,prix_total,nomOption) VALUES (?,?,?,?,?,?)");
    //                     $stmt->execute([$_POST['idOffre'], $ttOpt[0]['datefin'], $formattedDate, $_POST['nbWeek'], $prix , $_POST['nomOption']]);
    //                 }else {
    //                     $stmt = $conn->prepare("INSERT INTO pact.option (idOffre,dateLancement,dateFin,duree_total,prix_total,nomOption) VALUES (?,NULL,NULL,?,?,?)");
    //                     $stmt->execute([$_POST['idOffre'], $_POST['nbWeek'], $prix , $_POST['nomOption']]);
    //                 }
    //             }
    //         }
    //     }
    // }
}elseif ($_POST['type'] == 'resilier') {
    $idoption = $_POST['idoption'];
    $stmt = $conn->prepare("DELETE FROM pact._option_offre Where idoption = ?");
    $stmt->execute([$idoption]);
    $stmt = $conn->prepare("DELETE FROM pact._dateOption Where idoption = ?");
    $stmt->execute([$idoption]);
} elseif ($_POST['type'] == 'arreter') {
    $idoption = $_POST['idoption'];

    $stmt = $conn->prepare("UPDATE pact._dateOption SET datefin = CURRENT_DATE WHERE idoption = ?");
    $stmt->execute([$idoption]);
}

$stmt = $conn->prepare("SELECT * FROM pact.option");
$stmt->execute();


echo <<<HTML
<form id="redirectForm" method="POST" action="detailsOffer.php">
    <input type="hidden" name="error" value="{$pasok}">
    <input type="hidden" name="idoffre" value="{$offreId}">
    <input type="hidden" name="popup">
</form>
<script>
   document.getElementById('redirectForm').submit();
</script>
HTML;

exit;
?>