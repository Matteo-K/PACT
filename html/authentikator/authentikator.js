document.addEventListener("DOMContentLoaded", () => {
    const checkbox = document.getElementById("authentikator");
    const div = document.getElementById("divAuthent");
    const pseudoInput = document.getElementById("pseudoMembre"); // Champ pour les membres
    const denominationInput = document.getElementById("denomination"); // Champ pour les professionnels
    const labelPseudoInput = document.getElementById("labelPseudo");
    const codeInput = document.getElementById("code_2fa"); // Récupère l'input 2FA
    const status = document.getElementById("status");

    // Fonction pour mettre à jour le QR Code
    function updateQRCode() {
        let pseudo = pseudoInput ? pseudoInput.value.trim() : (labelPseudoInput ? labelPseudoInput.value.trim() : "");
        let denomination = denominationInput ? denominationInput.value.trim() : "";

        // Utiliser pseudo si disponible, sinon utiliser denomination
        let pseudoOrDenomination = pseudo || denomination ? pseudo || denomination : "pas de nom";

        console.log(pseudoOrDenomination);

        // Si aucun pseudo ou denomination, on ne fait rien
        ;

        if (checkbox.checked) {
            fetch("authentikator/authentikator.php?pseudo=" + encodeURIComponent(pseudoOrDenomination))
            .then(response => response.json())
            .then(data => {
                // Supprime l'ancien QR Code et la clé secrète s'ils existent
                let oldQRCode = div.querySelector("#qrCodeImg");
                let oldSecret = div.querySelector("#secretKey");
                if (oldQRCode) oldQRCode.remove();
                if (oldSecret) oldSecret.remove();
            
                // Ajouter le QR Code en premier enfant
                div.insertAdjacentHTML("afterbegin", `
                    <img id="qrCodeImg" src="${data.qrCodeUrl}" alt="QR Code">
                    <p id="secretKey" style="font-size: 18px; font-weight: bold; margin-top: 10px;">Clé : ${data.secret}</p>
                `);
                
                // Afficher la div avec une hauteur fixe
                div.style.height = "fit-content";
                div.style.minHeight = "280px";
                div.style.opacity = "1";
            })
            .catch(error => console.error("Erreur :", error));

        } else {
            // Réduire la div et masquer le contenu
            console.log("pas checked");
            div.style.height = "0";
            div.style.minHeight = "0";
            div.style.opacity = "0";
            setTimeout(() => {
                let oldQRCode = div.querySelector("#qrCodeImg");
                if (oldQRCode) oldQRCode.remove(); // Supprime uniquement l'image
            }, 300);
        }
    }

    function check2FA() {
        let code = codeInput.value;

        if (code.length === 6) {
            fetch("authentikator/validation_2fa.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "code_2fa=" + code
            })
            .then(response => response.text())
            .then(data => {
                status.innerHTML = data;
            })
            .catch(error => {
                status.innerHTML = "Erreur lors de la vérification.";
            });
        } else if (code.length === 0) {
            // Si l'input est vidé, réinitialiser la session côté serveur
            fetch("authentikator/reset_session.php", {
                method: "POST"
            })
            .then(response => response.text())
            .then(data => {
                console.log(data); // Affiche le message du serveur
            })
            .catch(error => {
                console.error("Erreur lors de la réinitialisation de la session", error);
            });
        } else {
            status.innerHTML = "";
        }
    }

    // Vérifier 2FA dès que l'utilisateur tape 6 chiffres
    codeInput.addEventListener("input", check2FA);

    // Mettre à jour le QR Code quand on coche/décoche
    checkbox.addEventListener("change", updateQRCode);

    // Mettre à jour le QR Code quand le pseudo ou la denomination change
    if (pseudoInput) {
        pseudoInput.addEventListener("input", updateQRCode);
    } else if (denominationInput) {
        denominationInput.addEventListener("input", updateQRCode);
    } else if (labelPseudoInput) {
        labelPseudoInput.addEventListener("input", updateQRCode);
    }
});
