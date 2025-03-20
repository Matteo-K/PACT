document.addEventListener("DOMContentLoaded", () => {
    const chekbox = document.getElementById("authentikator");
    const div = document.getElementById("divAuthent");
    
    chekbox.addEventListener("click",() => {
        // QR Code URL (à remplacer dynamiquement par ton code PHP)
        const qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=OTPAUTH_URI";
    
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