<?php

require '../vendor/autoload.php';

use OTPHP\TOTP;

// Créer une instance TOTP avec une clé secrète générée aléatoirement
$totp = TOTP::create();
$totp->setLabel('gabrielfroc22@gmail.com');
$totp->setIssuer('the-void.ventsdouest.dev');

// Récupérer la clé secrète (à stocker dans la base de données)
$secret = $totp->getSecret();

// Générer l'URI de provisionnement
$uri = $totp->getProvisioningUri();

// Afficher les informations
echo "Clé secrète : " . $secret . "<br>";
echo "URI de provisionnement : " . $uri . "<br>";

// Encoder l'URI en QR Code
echo '<img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($uri) . '" alt="QR Code Google Authenticator">';
?>
