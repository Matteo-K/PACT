<?php
require "../config.php";
require '../vendor/autoload.php';

use OTPHP\TOTP;

// Vérifier si un code a été envoyé
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['code_2fa'])) {
    $code = htmlspecialchars($_POST['code_2fa']);

    // Vérifier la présence du secret en session
    if (!isset($_SESSION['secret_2fa'])) {
        echo "<span style='color: red;'>Erreur : aucun secret trouvé dans la session.</span>";
        exit;
    }

    $secret = $_SESSION['secret_2fa'];
    $totp = TOTP::create($secret);

     // Obtenir l'heure actuelle
     $currentTime = time();

     // Calculer l'intervalle de 30 secondes avant
     $previousInterval = $currentTime - 30;
 
     // Vérifier le code pour l'intervalle actuel
     $currentCode = $totp->at($currentTime);
 
     // Vérifier le code pour l'intervalle précédent
     $previousCode = $totp->at($previousInterval);
 
     // Vérifier si l'un des deux codes correspond au code saisi par l'utilisateur
     if ($code === $currentCode || $code === $previousCode) {
         $_SESSION['2fa_verified'] = true;
         echo "<span style='color: green;'>2FA activé avec succès !</span>";
     } else {
         $_SESSION['2fa_verified'] = false;
         echo "<span style='color: red;'>Code invalide. Réessayez.</span>";
     }
}
?>
