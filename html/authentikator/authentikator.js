document.addEventListener("DOMContentLoaded", () => {
    const checkbox = document.getElementById("authentikator");
    const div = document.getElementById("divAuthent");
    const pseudoInput = document.getElementById("pseudoMembre");
    const codeInput = document.getElementById("code_2fa"); // Récupère l'input 2FA
    const status = document.getElementById("status");

    function updateQRCode() {
        let pseudo = pseudoInput.value.trim();
        if (pseudo === "") pseudo = "SansPseudo"; // Définit un pseudo par défaut

        if (checkbox.checked) {
            fetch("authentikator/authentikator.php?pseudo=" + encodeURIComponent(pseudo))
                .then(response => response.text())
                .then(data => {
                    // Supprime l'ancien QR Code s'il existe
                    let oldQRCode = div.querySelector("#qrCodeImg");
                    if (oldQRCode) oldQRCode.remove();

                    // Ajouter le QR Code en premier enfant
                    div.insertAdjacentHTML("afterbegin", `<img id="qrCodeImg" src="${data}" alt="QR Code">`);

                    // Afficher la div avec une hauteur fixe
                    div.style.height = "fit-content";
                    div.style.minHeight = "280px"
                    div.style.opacity = "1";
                })
                .catch(error => console.error("Erreur :", error));
        } else {
            // Réduire la div et masquer le contenu
            div.style.height = "0";
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
            fetch("validation_2fa.php", {
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
            fetch("reset_session.php", {
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

    // Mettre à jour le QR Code quand le pseudo change
    pseudoInput.addEventListener("input", updateQRCode);
});
