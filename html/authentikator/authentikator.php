<?php

require '../vendor/autoload.php';
require '../config.php';

use OTPHP\TOTP;

if (isset($_GET['pseudo'])) {
    $pseudo = htmlspecialchars($_GET['pseudo']);

    // Créer une instance TOTP avec le pseudo comme label
    $totp = TOTP::create();
    $totp->setLabel($pseudo);
    $totp->setIssuer('PACT');

    $secret = $totp->getSecret();
    $_SESSION['secret_a2f'] = $secret;

    // Générer l'URI de provisionnement
    $uri = $totp->getProvisioningUri();

    // Générer et renvoyer le QR Code + clé secrète en JSON
    $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($uri);

    echo json_encode([
        "qrCodeUrl" => $qrCodeUrl,
        "secret" => $secret
    ]);
}

?>
