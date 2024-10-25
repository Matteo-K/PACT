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


// Rediriger vers la page précédente ou une autre page
header("Location: detailsOffer.php");
exit;
?>
