document.addEventListener("DOMContentLoaded", () => {
    const checkbox = document.getElementById("authentikator");
    const div = document.getElementById("divAuthent");
    const pseudoInput = document.getElementById("pseudo");

    checkbox.addEventListener("change", () => {
        let pseudo = pseudoInput.value.trim();
        if (pseudo === "") pseudo = "SansPseudo"; // Définit un pseudo par défaut

        if (checkbox.checked) {
            fetch("authentikator/authentikator.php?pseudo=" + encodeURIComponent(pseudo))
                .then(response => response.text())
                .then(data => {
                    div.innerHTML = `<img id="qrCode" src="${data}" alt="QR Code">`;
                })
                .catch(error => console.error("Erreur :", error));
        } else {
            div.innerHTML = ""; // Supprimer le QR Code si décoché
        }
    });
});
