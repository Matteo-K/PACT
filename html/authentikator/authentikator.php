<?php

require 'vendor/autoload.php';

use OTPHP\TOTP;

// Créer une instance TOTP avec une clé secrète générée aléatoirement
$totp = TOTP::create();
$totp->setLabel('Gabriel');
$totp->setIssuer('PACT');

// Récupérer la clé secrète (à stocker dans la base de données)
$secret = $totp->getSecret();

// Générer l'URI de provisionnement
$uri = $totp->getProvisioningUri();

// Générer l'URL du QR Code
$url = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($uri);

?>

<input type="checkbox" id="qrcode"> Afficher QR Code
<div id="divAuthent"></div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    try {
        const checkbox = document.getElementById("authentikator");
        const checkmark = document.getElementById("qrcode")
        const div = document.getElementById("divAuthent");

        const qrCodeUrl = "<?php echo $url; ?>"; // URL générée en PHP

        checkmark.addEventListener("click", () => {
            if (checkbox.checked) {
                div.innerHTML = `<img id="qrCode" src="${qrCodeUrl}" alt="QR Code">`;
            } else {
                div.innerHTML = "";
            }
        });

        console.log("QR Code toggle prêt !");
    } catch (error) {
        console.error("Erreur :", error);
    }
});
</script>
