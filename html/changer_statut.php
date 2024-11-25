<?php
// Connexion à la base de données
// Assure-toi de remplacer les valeurs par les tiennes
require_once "config.php";
// Récupérer les données du formulaire
$offreId = $_POST['offre_id'];
$nouveauStatut = $_POST['nouveau_statut'];
$ouvert= $_POST['ouvert'];

// Mettre à jour le statut de l'offre
$stmt = $conn->prepare("UPDATE pact._offre SET statut = :statut WHERE idoffre = :id");
$stmt->bindParam(':statut', $nouveauStatut);
$stmt->bindParam(':id', $offreId);
$stmt->execute();

if ($nouveauStatut=='actif') {
    $ajst = $conn->prepare("SELECT idstatut,datelancement,dureeenligne FROM pact._historiqueStatut where idoffre=$offreId ORDER BY datelancement DESC");
    $ajst->execute();
    $resultat = $ajst->fetchAll(PDO::FETCH_ASSOC);

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
        $ajst = $conn->prepare("UPDATE pact._historiqueStatut SET dureeenligne = NULL WHERE idstatut = $idstatut");
        $ajst->execute();

    }else {
        $ajst = $conn->prepare("INSERT INTO pact._historiqueStatut(idoffre,datelancement,dureeenligne) VALUES ($offreId,CURRENT_DATE,NULL)");
        $ajst->execute();
    }
}else {
    $ajst = $conn->prepare("UPDATE pact._historiqueStatut SET dureeenligne = (CURRENT_DATE - datelancement)+1 WHERE idoffre = $offreId AND dureeenligne IS NULL");
    $ajst->execute();
}

// Rediriger vers la page précédente ou une autre page
header("Location: detailsOffer.php?idoffre=$offreId&ouvert=$ouvert");
exit;
?>
