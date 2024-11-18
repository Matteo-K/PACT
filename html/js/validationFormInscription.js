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


    // Appliquer la validation en temps réel (sur blur pour chaque champ)
    document.getElementById('denomination').addEventListener('blur', validateDenomination);
    document.getElementById('telephone').addEventListener('blur', validatePhone);
    document.getElementById('email').addEventListener('blur', validateEmail);
    document.getElementById('adresse').addEventListener('blur', validateAdresse);
    document.getElementById('code').addEventListener('blur', validateCode);
    document.getElementById('ville').addEventListener('blur', validateVille);
    document.getElementById('siren').addEventListener('blur', validateSiren);
    document.getElementById('motdepasse').addEventListener('blur', validatePassword);
    document.getElementById('confirmer').addEventListener('blur', validateConfirmPassword);

    // Ajouter la validation du checkbox des CGU
    document.getElementById('cgu').addEventListener('change', validateCGU);
});

    function validationForm(event) {
        // Empêche l'envoi du formulaire si des erreurs existent
        event.preventDefault();

        // Vérifie si tous les champs sont valides avant de soumettre
        if (isFormValid()) {
            document.getElementById('formPro').submit();
        } 
        
        else {
            alert("Veuillez corriger les erreurs avant de soumettre le formulaire.");
        }
    }

    // Vérifier si tous les champs sont valides
    function isFormValid() {
        return !document.querySelectorAll('.invalid').length;
    }

    // Validation des champs individuellement

    // Validation de la dénomination
    function validateDenomination() {
        const denomination = document.getElementById('denomination');

        if (!denomination.value.trim()) {
            showError(denomination, "La dénomination est requise.");
        }
        
        else {
            removeError(denomination);
        }
    }

    // Validation du téléphone
    function validatePhone() {
        const telephone = document.getElementById('telephone');
        const phonePattern = /^0[1-9]([.\-/]?[0-9]{2}){4}$/;

        if (!phonePattern.test(telephone.value.trim())) {
            showError(telephone, "Veuillez entrer un numéro de téléphone valide.");
        } 
        
        else {
            removeError(telephone);
        }
    }

    // Validation de l'email
    function validateEmail() {
        const email = document.getElementById('email');
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailPattern.test(email.value.trim())) {
            showError(email, "Veuillez entrer une adresse e-mail valide.");
        } 
        
        else {
            removeError(email);
        }
    }

    // Validation de l'adresse
    function validateAdresse() {
        const adresse = document.getElementById('adresse');
        const adressePattern = /^\d+\s+(bis\s+)?[A-Za-z\s]+/i;

        if (!adressePattern.test(adresse.value.trim())) {
            showError(adresse, "Veuillez entrer une adresse postale valide.");
        } 
        
        else {
            removeError(adresse);
        } 
    }

    // Validation du code postal
    function validateCode() {
        const code = document.getElementById('code');
        const codePattern = /^[0-9]{5}$/;

        if (!codePattern.test(code.value.trim())) {
            showError(code, "Veuillez entrer un code postal valide.");
        } 
        
        else {
            removeError(code);
        }
    }

    // Validation de la ville
    function validateVille() {
        const ville = document.getElementById('ville');
        const villePattern = /^[A-Za-z\s\-]+$/;

        if (!villePattern.test(ville.value.trim())) {
            showError(ville, "Veuillez entrer une ville valide.");
        } 
        
        else {
            removeError(ville);
        }
    }

    // Validation du SIREN (uniquement si secteur privé)
    function validateSiren() {
        const siren = document.getElementById('siren');
        const sirenPattern = /^(?:\d{3} \d{3} \d{3}|\d{9})$/;
        const privateRadio = document.getElementById('radioPrive');

        if (privateRadio.checked && !sirenPattern.test(siren.value.trim())) {
            showError(siren, "Veuillez entrer un numéro de SIREN valide.");
        } 
        
        else {
            removeError(siren);
        }
    }

    // Validation du mot de passe
    function validatePassword() {
        const motdepasse = document.getElementById('motdepasse');
        const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{10,}$/;

        if (!passwordPattern.test(motdepasse.value.trim())) {
            showError(motdepasse, "Le mot de passe doit contenir au moins 10 caractères, une majuscule, une minuscule et un chiffre.");
        } 
        
        else {
            removeError(motdepasse);
        }
    }

    // Validation de la confirmation du mot de passe
    function validateConfirmPassword() {
        const motdepasse = document.getElementById('motdepasse').value.trim();
        const confirmer = document.getElementById('confirmer').value.trim();

        if (motdepasse !== confirmer) {
            showError(document.getElementById('confirmer'), "Les mots de passe ne correspondent pas.");
        } 
        
        else {
            removeError(document.getElementById('confirmer'));
        }
    }

    // Validation des CGU
    function validateCGU() {
        const cgu = document.getElementById('cgu');

        if (!cgu.checked) {
            showError(cgu, "Vous devez accepter les conditions générales d'utilisation.");
        } 
        
        else {
            removeError(cgu);
        }
    }

    // Afficher un message d'erreur et ajouter une bordure rouge
    function showError(element, message) {
        const errorContainer = document.getElementById('formErrors');
        let errorMessage = document.createElement('div');

        errorMessage.classList.add('error');
        errorMessage.innerText = message;

        // Ajout de l'erreur en haut du formulaire
        errorContainer.appendChild(errorMessage);

        // Ajout de la classe d'erreur pour bordure rouge
        element.classList.add('invalid');
    }

    // Retirer le message d'erreur et la bordure rouge
    function removeError(element) {
        const errorContainer = document.getElementById('formErrors');

        // Retirer tous les messages d'erreur associés à ce champ
        let errors = errorContainer.getElementsByClassName('error');

        for (let i = 0; i < errors.length; i++) {

            if (errors[i].innerText.includes(element.id)) {
                errors[i].remove();
            }
        }

        // Retirer la bordure rouge
        element.classList.remove('invalid');
    }