// Script Javascript de validation du formulaire pour la création de compte pro

document.getElementById('retour').addEventListener('click', function() {
    window.history.back();
});


document.addEventListener('DOMContentLoaded', function() {
    const boutonInscription = document.getElementById('boutonInscriptionPro');
    
    // Ajouter l'écouteur d'événement une seule fois
    boutonInscription.addEventListener('click', validationForm);
});


function validationForm(event) {
    event.preventDefault(); // Empêche l'envoi du formulaire

    // Récupérer les champs
    const denomination = document.getElementById('denomination').value.trim();
    const telephone = document.getElementById('telephone').value.trim();
    const email = document.getElementById('email').value.trim();
    const adresse = document.getElementById('adresse').value.trim();
    const code = document.getElementById('code').value.trim();
    const ville = document.getElementById('ville').value.trim();
    const siren = document.getElementById('siren').value.trim();
    const motdepasse = document.getElementById('motdepasse').value;
    const confirmer = document.getElementById('confirmer').value;
    const cgu = document.getElementById('cgu').checked;

    // Vérification des champs
    if (!denomination || !telephone || !email || !adresse || !code || !ville || !siren || !motdepasse || !confirmer) {
        alert('Tous les champs marqués d\'un astérisque (*) doivent être remplis.');
        return;
    }

    // Vérification du numéro de téléphone (format français sans points)
    const phonePattern = /^0[1-9]([ -]?[0-9]{2}){4}$/;
    if (!phonePattern.test(telephone)) {
        alert('Veuillez entrer un numéro de téléphone valide (sans points).');
        return;
    }

    // Vérification du format de l'adresse e-mail
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        alert('Veuillez entrer une adresse e-mail valide.');
        return;
    }

    // Vérification de l'adresse postale (doit contenir un numéro suivi d'au moins un mot)
    const adressePattern = /^\d+\s+[A-Za-z\s]+/;
    if (!adressePattern.test(adresse)) {
        alert('Veuillez entrer une adresse postale valide (ex : 123 Rue de Paris).');
        return;
    }

    // Vérification du code postal (5 chiffres)
    const codePattern = /^[0-9]{5}$/;
    if (!codePattern.test(code)) {
        alert('Veuillez entrer un code postal valide (5 chiffres).');
        return;
    }

    // Vérification de la ville (lettres et espaces uniquement)
    const villePattern = /^[A-Za-z\s\-]+$/; // Autorise les lettres, les espaces et les tirets
    if (!villePattern.test(ville)) {
        alert('Veuillez entrer une ville valide (lettres et espaces seulement).');
        return;
    }

    // Vérification du numéro de SIREN (9 chiffres ou format avec espaces)
    const sirenPattern = /^(?:\d{3} \d{3} \d{3}|\d{9})$/;
    if (!sirenPattern.test(siren)) {
        alert('Veuillez entrer un numéro de SIREN valide (ex : 123 456 789 ou 123456789).');
        return;
    }


    // Vérification du mot de passe
    const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{10,}$/;
    if (!passwordPattern.test(motdepasse)) {
        alert('Le mot de passe doit contenir au moins 10 caractères, dont une majuscule, une minuscule et un chiffre.');
        return;
    }

    // Vérification de la confirmation du mot de passe
    if (motdepasse !== confirmer) {
        alert('Les mots de passe ne correspondent pas.');
        return;
    }

    // Vérification des CGU
    if (!cgu) {
        alert('Vous devez accepter les conditions générales d\'utilisation.');
        return;
    }

    // Si toutes les vérifications sont passées, soumettre le formulaire
    document.getElementById('formPro').submit();
}