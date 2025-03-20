document.addEventListener("DOMContentLoaded", () => {
    const checkbox = document.getElementById("authentikator");
    const div = document.getElementById("divAuthent");
    const pseudoInput = document.getElementById("pseudo");

    function updateQRCode() {
        let pseudo = pseudoInput.value.trim();
        if (pseudo === "") pseudo = "SansPseudo"; // Définit un pseudo par défaut

        if (checkbox.checked) {
            fetch("authentikator/authentikator.php?pseudo=" + encodeURIComponent(pseudo))
                .then(response => response.text())
                .then(data => {
                    div.innerHTML = `<img id="qrCode" src="${data}" alt="QR Code">`;

                    // Attendre un peu avant d'afficher en douceur
                    setTimeout(() => {
                        document.getElementById("qrCode").style.opacity = "1";
                    }, 100);
                })
                .catch(error => console.error("Erreur :", error));
        } else {
            div.innerHTML = ""; // Supprimer le QR Code si décoché
        }
    }

    // Mettre à jour le QR Code quand on coche/décoche
    checkbox.addEventListener("change", updateQRCode);

    // Mettre à jour le QR Code quand le pseudo change
    pseudoInput.addEventListener("input", updateQRCode);
});
