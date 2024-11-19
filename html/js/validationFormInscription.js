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



    // Liste des champs à valider
    const formFields = [
        { id: 'denomination', validation: validateDenomination },
        { id: 'telephone', validation: validateTelephone },
        { id: 'email', validation: validateEmail },
        { id: 'adresse', validation: validateAdresse },
        { id: 'code', validation: validateCode },
        { id: 'ville', validation: validateVille },
        { id: 'siren', validation: validateSiren },
        { id: 'motdepasse', validation: validatePassword },
        { id: 'confirmer', validation: validatePasswordConfirmation },
        { id: 'cgu', validation: validateCGU }
    ];

    // Ajouter un événement blur à chaque champ
    formFields.forEach(function(field) {
        const element = document.getElementById(field.id);
        element.addEventListener('blur', function() {
            field.validation(element);
        });
    });

    // Fonction pour valider chaque champ
    function validateDenomination(element) {
        const value = element.value.trim();
        if (!value) {
            showError("La dénomination est requise.");
        } else {
            clearError();
        }
    }

    function validateTelephone(element) {
        const value = element.value.trim();
        const phonePattern = /^0[1-9](?:[.\-/\s]?[0-9]{2}){4}$/;
        if (!phonePattern.test(value)) {
            showError("Veuillez entrer un numéro de téléphone valide.");
        } else {
            clearError();
        }
    }

    function validateEmail(element) {
        const value = element.value.trim();
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(value)) {
            showError("Veuillez entrer une adresse e-mail valide.");
        } else {
            clearError();
        }
    }

    function validateAdresse(element) {
        const value = element.value.trim();
        const adressePattern = /^\d+\s+(bis\s+)?[A-Za-z\s]+/i;
        if (!adressePattern.test(value)) {
            showError("Veuillez entrer une adresse postale valide.");
        } else {
            clearError();
        }
    }

    function validateCode(element) {
        const value = element.value.trim();
        const codePattern = /^[0-9]{5}$/;
        if (!codePattern.test(value)) {
            showError("Veuillez entrer un code postal valide.");
        } else {
            clearError();
        }
    }

    function validateVille(element) {
        const value = element.value.trim();
        const villePattern = /^[A-Za-z\s\-]+$/;
        if (!villePattern.test(value)) {
            showError("Veuillez entrer une ville valide.");
        } else {
            clearError();
        }
    }

    function validateSiren(element) {
        const value = element.value.trim();
        const privateRadio = document.getElementById("radioPrive");
        const sirenPattern = /^(?:\d{3} \d{3} \d{3}|\d{9})$/;
        if (privateRadio.checked && !sirenPattern.test(value)) {
            showError("Veuillez entrer un numéro de SIREN valide.");
        } else {
            clearError();
        }
    }

    function validatePassword(element) {
        const value = element.value;
        const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{10,}$/;
        if (!passwordPattern.test(value)) {
            showError("Le mot de passe doit contenir au moins 10 caractères, dont une majuscule, une minuscule et un chiffre.");
        } else {
            clearError();
        }
    }

    function validatePasswordConfirmation(element) {
        const value = element.value;
        const motdepasse = document.getElementById('motdepasse').value;
        if (motdepasse !== value) {
            showError("Les mots de passe ne correspondent pas.");
        } else {
            clearError();
        }
    }

    function validateCGU(element) {
        if (!element.checked) {
            showError("Vous devez accepter les conditions générales d'utilisation.");
        } else {
            clearError();
        }
    }

    // Fonction pour afficher l'erreur dans le conteneur global
    function showError(message) {
        const errorContainer = document.querySelector('.messageErreur');
        const errorMessage = document.createElement('p');
        errorMessage.classList.add('error-message');
        errorMessage.textContent = message;
        errorContainer.appendChild(errorMessage);
    }

    // Fonction pour effacer toutes les erreurs
    function clearError() {
        const errorContainer = document.querySelector('.messageErreur');
        errorContainer.innerHTML = ''; // Supprime toutes les erreurs du conteneur
    }

    // Empêcher l'envoi du formulaire si des erreurs existent
    document.getElementById('formPro').addEventListener('submit', function(event) {
        clearError(); // Supprimer les erreurs existantes avant de soumettre
        formFields.forEach(function(field) {
            const element = document.getElementById(field.id);
            field.validation(element);
        });

        // Vérifier s'il y a des erreurs
        const errors = document.querySelectorAll('.error-message');
        if (errors.length > 0) {
            event.preventDefault(); // Empêcher l'envoi si des erreurs existent
        }
    });
});