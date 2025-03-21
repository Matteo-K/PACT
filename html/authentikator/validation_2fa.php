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

    // Debug : Afficher le code et secret pour vérifier qu'ils sont bien récupérés
    echo "<pre>";
    echo "Code entré : " . $code . "<br>";
    echo "Secret en session : " . $secret . "<br>";
    echo "Code actuel (expected) : " . $totp->now() . "<br>";
    echo "</pre>";

    // Vérifier si le code est valide avec une fenêtre de 1 période (30 secondes)
    // Permet de vérifier le code actuel et celui des 30 secondes précédentes
    if ($totp->verify($code, null, 30)) {  // Test avec window = 2 (actuel et une période avant)
        $_SESSION['2fa_verified'] = true;
        echo "<span style='color: green;'>2FA activé avec succès !</span>";
    } else {
        $_SESSION['2fa_verified'] = false;
        echo "<span style='color: red;'>Code invalide. Réessayez.</span>";
    }
}
?>
