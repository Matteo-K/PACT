document.addEventListener("DOMContentLoaded", () => {
    const checkbox = document.getElementById("authentikator");
    const div = document.getElementById("divAuthent");
    const pseudoInput = document.getElementById("pseudoMembre");

    function updateQRCode() {
        let pseudo = pseudoInput.value.trim();
        if (pseudo === "") pseudo = "SansPseudo"; // Définit un pseudo par défaut

        if (checkbox.checked) {
            fetch("authentikator/authentikator.php?pseudo=" + encodeURIComponent(pseudo))
                .then(response => response.text())
                .then(data => {
                    div.innerHTML = `<img id="qrCode" src="${data}" alt="QR Code">`;
                    
                    // Afficher la div avec une hauteur fixe
                    div.style.height = "220px";
                    div.style.opacity = "1";
                })
                .catch(error => console.error("Erreur :", error));
        } else {
            // Réduire la div et masquer le contenu
            div.style.height = "0";
            div.style.opacity = "0";
            setTimeout(() => {
                div.innerHTML = ""; // Supprime l'image après l'animation
            }, 300);
        }
    }

    // Mettre à jour le QR Code quand on coche/décoche
    checkbox.addEventListener("change", updateQRCode);

    // Mettre à jour le QR Code quand le pseudo change
    pseudoInput.addEventListener("input", updateQRCode);
});
