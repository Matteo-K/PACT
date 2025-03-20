<?php

require '../vendor/autoload.php';

use OTPHP\TOTP;

// Créer une instance TOTP avec une clé secrète générée aléatoirement
$totp = TOTP::create();
$totp->setLabel('Gabriel');
$totp->setIssuer('PACT');

// Récupérer la clé secrète (à stocker dans la base de données)
$secret = $totp->getSecret();

// Générer l'URI de provisionnement
$uri = $totp->getProvisioningUri();

$url = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($uri) . '";
// Encoder l'URI en QR Code
echo '<img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($uri) . '" alt="QR Code Google Authenticator">';
?>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const chekbox = document.getElementById("authentikator");
        const div = document.getElementById("divAuthent");
        
        chekbox.addEventListener("click",() => {
            // QR Code URL (à remplacer dynamiquement par ton code PHP)
            const qrCodeUrl = <?php echo $url ?>;
        
            checkbox.addEventListener("click", () => {
                if (checkbox.checked) {
                    // Ajouter dynamiquement le QR Code
                    div.innerHTML = `<img id="qrCode" src="${qrCodeUrl}" alt="QR Code">`;
                } else {
                    // Supprimer le QR Code
                    div.innerHTML = "";
                }
            });
        });
    });
</script>
