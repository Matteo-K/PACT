<?php
// Connexion à la base de données
// Assure-toi de remplacer les valeurs par les tiennes
require_once "config.php";
// Récupérer les données du formulaire
$offreId = $_POST['offre_id'];
$nouveauStatut = $_POST['nouveau_statut'];

// Mettre à jour le statut de l'offre
$stmt = $conn->prepare("UPDATE pact._offre SET statut = :statut WHERE idoffre = :id");
$stmt->bindParam(':statut', $nouveauStatut);
$stmt->bindParam(':id', $offreId);
$stmt->execute();

if ($nouveauStatut=='actif') {
    $ajst = $conn->prepare("SELECT idstatut,datelancement,dureeenligne FROM pact._historiqueStatut where idoffre=$offreId ORDER BY datelancement DESC");
    $ajst->execute();
    $resultat = $ajst->fetchAll(PDO::FETCH_ASSOC);
    if ($resultat) {
        $idstatut=$resultat[0]['idstatut'];
    
        $dateLancement = $resultat[0]['datelancement'];
        $dureeEnLigne = $resultat[0]['dureeenligne'];
    
        if (!$dateLancement instanceof DateTime) {
            $dateLancementObj = new DateTime($dateLancement);  // Créer un objet DateTime à partir de la chaîne
        } else {
            $dateLancementObj = $dateLancement;  // Si c'est déjà un objet DateTime, l'utiliser tel quel
        }
        
        // Ajouter la durée (dureeEnLigne) à la date de lancement
        $dateLancementObj->modify("+$dureeEnLigne days");
        
        // Obtenir la date actuelle (actuelle sans l'heure)
        $currentDate = new DateTime();
        $currentDateFormatted = $currentDate->format('Y-m-d');  // Formater la date actuelle au format 'YYYY-MM-DD'
        
        // Formater la date de lancement modifiée au même format
        $dateLancementObjFormatted = $dateLancementObj->format('Y-m-d');  // Formater la date de lancement modifiée
        
        // Comparer les deux dates formatées
        if ($dateLancementObjFormatted >= $currentDateFormatted) {
            $ajst = $conn->prepare("UPDATE pact._historiqueStatut SET dureeenligne = NULL, prixduree = NULL WHERE idstatut = $idstatut");
            $ajst->execute();
    
        }else {
            $ajst = $conn->prepare("INSERT INTO pact._historiqueStatut(idoffre,datelancement,dureeenligne,prixduree) VALUES ($offreId,CURRENT_DATE,NULL,NULL)");
            $ajst->execute();
        }

        $ajst = $conn->prepare("SELECT * FROM pact.option WHERE idoffre=? and (datefin is null)");
        $ajst->execute([$offreId]);
        $tema = $ajst->fetchAll();
        if ($tema) {
            foreach ($tema as $key => $value) {
                if ($value['nomoption'] == 'ALaUne') {
                    $alaUne = $value;
                }
                if ($value['nomoption'] == 'EnRelief') {
                    $relief = $value;
                }
            }
        }else {
            $alaUne = false;
            $relief = false;
        }

        $ajst = $conn->prepare("SELECT datefin FROM pact.option WHERE idoffre=? and (datefin>CURRENT_DATE)");
        $ajst->execute([$offreId]);
        $dtOpt = $ajst->fetchAll();

        if (!$dtOpt) {
            if ($alaUne != false) {
                $duree = $alaUne['duree_total'] * 7;
                $date = new DateTime();
                $date->modify("+$duree days"); // Ajout de la durée
                $formattedDate = $date->format('Y-m-d'); // Conversion de l'objet DateTime en format SQL compatible
                        
                $idoption = $alaUne['idoption'];
                        
                // Requête SQL sécurisée avec des paramètres
                $ajst = $conn->prepare("UPDATE pact._dateoption 
                                        SET datelancement = CURRENT_DATE, 
                                            datefin = :datefin 
                                        WHERE idoption = :idoption");
            
                // Liaison des paramètres
                $ajst->bindParam(':datefin', $formattedDate);
                $ajst->bindParam(':idoption', $idoption, PDO::PARAM_INT);
                        
                // Exécution de la requête
                $ajst->execute();
            }
            if ($relief) {
                $duree = $relief['duree'] * 7;
                $date = new DateTime();
                $date->modify("+$duree days"); // Ajout de la durée
                $formattedDate = $date->format('Y-m-d'); // Conversion de l'objet DateTime en format SQL compatible
                        
                $idoption = $relief['idoption'];
                        
                // Requête SQL sécurisée avec des paramètres
                $ajst = $conn->prepare("UPDATE pact._dateoption 
                                        SET datelancement = CURRENT_DATE, 
                                            datefin = :datefin 
                                        WHERE idoption = :idoption");
            
                // Liaison des paramètres
                $ajst->bindParam(':datefin', $formattedDate);
                $ajst->bindParam(':idoption', $idoption, PDO::PARAM_INT);
                        
                // Exécution de la requête
                $ajst->execute();
            }
        }
    } else {
        // première mise en ligne
        $ajst = $conn->prepare("INSERT INTO pact._historiqueStatut(idoffre,datelancement,dureeenligne,prixduree) VALUES ($offreId,CURRENT_DATE,NULL,NULL)");
        $ajst->execute();
        // $ajst = $conn->prepare("SELECT idoffre,idoption,duree_total FROM pact.option WHERE idoffre = $offreId");
        // $ajst->execute();
        // $result = $ajst->fetchAll();
        // if ($result) {
        //     foreach ($result as $key => $value) {
        //         $duree = $value['duree_total'] * 7;
        //         $date = new DateTime();
        //         $date->modify("+$duree days"); // Ajout de la durée
        //         $formattedDate = $date->format('Y-m-d'); // Conversion de l'objet DateTime en format SQL compatible
                        
        //         $idoption = $value['idoption'];
                        
        //         // Requête SQL sécurisée avec des paramètres
        //         $ajst = $conn->prepare("UPDATE pact._dateoption 
        //                                 SET datelancement = CURRENT_DATE, 
        //                                     datefin = :datefin 
        //                                 WHERE idoption = :idoption");
            
        //         // Liaison des paramètres
        //         $ajst->bindParam(':datefin', $formattedDate);
        //         $ajst->bindParam(':idoption', $idoption, PDO::PARAM_INT);
                        
        //         // Exécution de la requête
        //         $ajst->execute();
        //     }
        // }
    }
}else {
    $ajst = $conn->prepare("SELECT tarif FROM pact._abonner JOIN pact._abonnement ON pact._abonner.nomabonnement = pact._abonnement.nomabonnement WHERE idoffre = ?");
    $ajst->execute([$offreId]);
    $prix = ($ajst->fetchAll())[0]['tarif'];
    $dd = $conn->prepare("SELECT datelancement FROM pact._historiqueStatut WHERE idoffre = ? AND dureeenligne is NULL");
    $dd->execute([$offreId]);
    $dateL = ($dd->fetchAll())[0]['datelancement'];
    
    $dateLancement = new DateTime($dateL); // Convertir en objet DateTime
    $aujourdhui = new DateTime(); // Date actuelle
    $dureeInterval = $aujourdhui->diff($dateLancement); // Différence entre les dates
    $duree = $dureeInterval->days + 1; // Ajouter 1 jour

    // Calcul du prix total
    $prixtt = $prix * $duree;

    $ajst = $conn->prepare("UPDATE pact._historiqueStatut SET dureeenligne = ?, prixduree = ? WHERE idoffre = $offreId AND dureeenligne IS NULL");
    $ajst->execute([$duree,$prixtt]);
}

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
