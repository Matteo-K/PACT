<?php

require '../vendor/autoload.php';

use OTPHP\TOTP;

// Génération d'un TOTP avec une clé secrète
$totp = TOTP::create();
$totp->setLabel('utilisateur@votre_site.com');
$totp->setIssuer('VotreSite');

// Obtenir l'URI de provisionnement (pour générer le QR Code)
$uri = $totp->getProvisioningUri();

// Afficher la clé secrète et l'URI du QR Code
echo "Clé secrète : " . $totp->getSecret() . "<br>";
echo "URI du QR Code : " . $uri;

?>