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

$url = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($uri) . '";
?>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        try {
            const chekbox = document.getElementById("qrcode");
            const div = document.getElementById("divAuthent");

            chekbox.addEventListener("click",() => {

                const qrCodeUrl = <?php echo $url ?>;
            
                checkbox.addEventListener("click", () => {
                    if (checkbox.checked) {
                        div.innerHTML = `<img id="qrCode" src="${qrCodeUrl}" alt="QR Code">`;
                    } else {
                        div.innerHTML = "";
                    }
                });
            });
            console.log("réussie");
        } catch (error) {
            console.log(error);
        }
    });
</script>
