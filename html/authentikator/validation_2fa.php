<?php
require "../config.php";
require '../vendor/autoload.php';

use OTPHP\TOTP;

// Vérifier si un code a été envoyé
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['code_2fa'])) {
    $code = htmlspecialchars($_POST['code_2fa']);

    // Vérifier la présence du secret en session
    if (!isset($_SESSION['secret_2fa'])) {
        echo "<span style='color: red;'>Erreur : aucun secret trouvé.</span>";
        exit;
    }

    $secret = $_SESSION['secret_2fa'];
    $totp = TOTP::create($secret);

    // Vérifier si le code est valide avec un "window" de 1 (cela permet de valider le code actuel + le code dans la période précédente)
    if ($totp->verify($code, null, 1)) {  // "1" permet d'ajouter une fenêtre de 1 période (30 secondes)
        $_SESSION['2fa_verified'] = true; // Stocke l'état validé en session
        echo "<span style='color: green;'>2FA activé avec succès !</span>";
    } else {
        $_SESSION['2fa_verified'] = false; // Stocke l'échec
        echo "<span style='color: red;'>Code invalide. Réessayez.</span>";
    }
}
?>
