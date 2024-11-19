// +++++++++++++++++++//
// Script JS Antoine //
// +++++++++++++++++//
document.addEventListener('DOMContentLoaded', function() {
    // Gestion du bouton retour
    document.getElementById('retour').addEventListener('click', function() {
        window.history.back();
    });

    const boutonInscription = document.getElementById('boutonInscriptionPro');
    boutonInscription.addEventListener('click', validationForm);

    // Récupérer les éléments nécessaires pour le SIREN
    const sirenLabel = document.querySelector("label[for='siren']");
    const sirenInput = document.getElementById("siren");
    const publicRadio = document.getElementById("radioPublic");
    const priveRadio = document.getElementById("radioPrive");

    // Fonction pour mettre à jour l'affichage du SIREN
    function updateSirenVisibility() {
        if (priveRadio.checked) {
            sirenLabel.style.display = "block"; // Affiche le label
            sirenInput.style.display = "block"; // Affiche le champ
        } 
        
        else {
            sirenLabel.style.display = "none"; // Cache le label
            sirenInput.style.display = "none"; // Cache le champ
        }
    }

    // Initialiser l'affichage
    updateSirenVisibility();

    // Ajouter des écouteurs d'événements sur les boutons radio
    publicRadio.addEventListener("click", updateSirenVisibility);
    priveRadio.addEventListener("click", updateSirenVisibility);
});

function validationForm(event) {
    // Empêche l'envoi du formulaire
    event.preventDefault();

    // Vider les erreurs précédentes
    clearErrors();

    // Récupérer les éléments de radio
    const publicRadio = document.getElementById("radioPublic");
    const privateRadio = document.getElementById("radioPrive");

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

    let errors = [];

    // Vérification des champs
    if (!denomination || !telephone || !email || !adresse || !code || !ville || !motdepasse || !confirmer || (!document.getElementById('radioPublic').checked && !document.getElementById('radioPrive').checked)) {
        errors.push('Tous les champs marqués d\'un astérisque (*) doivent être remplis.');
    }

    // Vérification du numéro de téléphone
    const phonePattern = /^0[1-9]([.\-/]?[0-9]{2}){4}$/;
    if (!phonePattern.test(telephone)) {
        errors.push('Veuillez entrer un numéro de téléphone valide (sans lettres et avec des séparateurs valides tels que . , / ou -).');
    }

    // Vérification du format de l'adresse e-mail
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        errors.push('Veuillez entrer une adresse e-mail valide.');
    }

    // Vérification de l'adresse postale
    const adressePattern = /^\d+\s+(bis\s+)?[A-Za-z\s]+/i;
    if (!adressePattern.test(adresse)) {
        errors.push('Veuillez entrer une adresse postale valide (ex : 123 bis Rue de Paris).');
    }

    // Vérification du code postal
    const codePattern = /^[0-9]{5}$/;
    if (!codePattern.test(code)) {
        errors.push('Veuillez entrer un code postal valide (5 chiffres).');
    }

    // Vérification de la ville
    const villePattern = /^[A-Za-z\s\-]+$/; 
    if (!villePattern.test(ville)) {
        errors.push('Veuillez entrer une ville valide (lettres et espaces seulement).');
    }

    // Vérification du numéro de SIREN
    if (document.getElementById('radioPrive').checked && !/^(?:\d{3} \d{3} \d{3}|\d{9})$/.test(siren)) {
        errors.push('Veuillez entrer un numéro de SIREN valide (ex : 123 456 789 ou 123456789).');
    }

    // Vérification du mot de passe
    const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{10,}$/;
    if (!passwordPattern.test(motdepasse)) {
        errors.push('Le mot de passe doit contenir au moins 10 caractères, dont une majuscule, une minuscule et un chiffre.');
    }

    // Vérification de la confirmation du mot de passe
    if (motdepasse !== confirmer) {
        errors.push('Les mots de passe ne correspondent pas.');
    }

    // Vérification des CGU
    if (!cgu) {
        errors.push('Vous devez accepter les conditions générales d\'utilisation.');
    }

    // Affichage des erreurs
    if (errors.length > 0) {
        showErrors(errors);
        return; // Arrêter la soumission du formulaire
    }

    // Si aucune erreur, soumettre le formulaire
    document.getElementById('formPro').submit();
}

// Fonction pour afficher les erreurs dans le formulaire
function showErrors(errors) {
    const errorContainer = document.querySelector('.messageErreur');
    const ul = document.createElement('ul');
    
    errors.forEach(error => {
        const li = document.createElement('li');
        li.textContent = error;
        ul.appendChild(li);
    });
    
    errorContainer.innerHTML = ''; // Vider les anciennes erreurs
    errorContainer.appendChild(ul);
}

// Fonction pour effacer les erreurs précédentes
function clearErrors() {
    const errorContainer = document.querySelector('.messageErreur');
    errorContainer.innerHTML = ''; // Vider toutes les erreurs
}