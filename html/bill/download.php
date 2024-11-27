<?php 
require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    header("Location: ../index.php");
    exit;
}


use Dompdf\Dompdf;

// Initialisation de Dompdf
$dompdf = new Dompdf();

// HTML à convertir
ob_start();
include 'bill.php'; // Inclut le fichier HTML
$html = ob_get_clean();

// Charger le contenu HTML
$dompdf->loadHtml($html);

// Configurer le format et l'orientation de la page
$dompdf->setPaper('A4', 'portrait'); // Options : A4, Letter, etc.

// Rendre le HTML en PDF
$dompdf->render();

// Télécharger le fichier PDF
$dompdf->stream("Facture". $denomination . $mois . $annee . ".pdf", ["Attachment" => $boole]); // "Attachment" => true pour forcer le téléchargement
?>