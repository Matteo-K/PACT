document.addEventListener("DOMContentLoaded", () => {
    const checkbox = document.getElementById("authentikator");
    const div = document.getElementById("divAuthent");
    const pseudoInput = document.getElementById("pseudo");

    checkbox.addEventListener("change", () => {
        const pseudo = pseudoInput.value.trim();

        if (checkbox.checked && pseudo !== "") {
            fetch("authentikator/authentikator.php?pseudo=" + encodeURIComponent(pseudo))
                .then(response => response.text())
                .then(data => {
                    div.innerHTML = `<img id="qrCode" src="${data}" alt="QR Code">`;
                })
                .catch(error => console.error("Erreur :", error));
        } else if (pseudo == "") {
            fetch("authentikator.php?pseudo=" + encodeURIComponent("SansPseudo"))
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