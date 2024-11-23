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
    $ajst = $conn->prepare("INSERT INTO pact._historiqueStatut(idoffre,datelancement,dureeenligne) VALUES ($offreId,CURRENT_DATE,NULL)");
    $ajst->execute();
}else {
    $ajst = $conn->prepare("UPDATE pact._historiqueStatut SET dureeenligne = (CURRENT_DATE - datelancement) WHERE idoffre = $offreId AND dureeenligne IS NULL");
    $ajst->execute();
}

// Rediriger vers la page précédente ou une autre page
header("Location: detailsOffer.php?idoffre=$offreId&ouvert=$ouvert");
exit;
?>
