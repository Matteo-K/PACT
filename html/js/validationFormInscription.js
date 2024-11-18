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




    const form = document.getElementById('formPro');
    const formErrors = document.getElementById('formErrors');

    // Validation en temps réel sur blur
    const fields = [
        { id: 'denomination', validator: validateDenomination },
        { id: 'telephone', validator: validatePhone },
        { id: 'email', validator: validateEmail },
        { id: 'adresse', validator: validateAdresse },
        { id: 'code', validator: validateCode },
        { id: 'ville', validator: validateVille },
        { id: 'siren', validator: validateSiren },
        { id: 'motdepasse', validator: validatePassword },
        { id: 'confirmer', validator: validateConfirmPassword },
        { id: 'cgu', validator: validateCGU, event: 'change' } // Sur changement pour les checkbox
    ];

    
    fields.forEach(field => {
        const input = document.getElementById(field.id);
        const eventType = field.event || 'blur'; // Par défaut, écouter l'événement blur
        input.addEventListener(eventType, field.validator);
    });

    // Validation au clic sur le bouton
    document.getElementById('boutonInscriptionPro').addEventListener('click', function (event) {
        event.preventDefault();

        let hasErrors = false;

        fields.forEach(field => {
            const input = document.getElementById(field.id);
            field.validator();
            if (input.classList.contains('invalid')) {
                hasErrors = true;
            }
        });

        if (!hasErrors) {
            form.submit();
        } else {
            alert("Veuillez corriger les erreurs avant de soumettre le formulaire.");
        }
    });

    // Ajout d’un message d’erreur et bordure rouge
    function showError(element, message) {
        removeError(element); // Supprimez l’erreur existante si elle est déjà affichée

        const errorMessage = document.createElement('div');
        errorMessage.classList.add('error');
        errorMessage.setAttribute('data-for', element.id); // Attribuez une référence au champ
        errorMessage.innerText = message;

        formErrors.appendChild(errorMessage); // Affiche l'erreur en haut du formulaire
        element.classList.add('invalid'); // Ajoute la bordure rouge
    }

    // Supprime le message d’erreur et restaure la bordure
    function removeError(element) {
        const existingError = formErrors.querySelector(`[data-for="${element.id}"]`);
        if (existingError) {
            existingError.remove();
        }
        element.classList.remove('invalid'); // Retire la bordure rouge
    }

    // Fonctions de validation des champs

    function validateDenomination() {
        const denomination = document.getElementById('denomination');
        if (!denomination.value.trim()) {
            showError(denomination, "La dénomination est requise.");
        } else {
            removeError(denomination);
        }
    }

    function validatePhone() {
        const telephone = document.getElementById('telephone');
        const phonePattern = /^0[1-9]([.\-/]?[0-9]{2}){4}$/;
        if (!phonePattern.test(telephone.value.trim())) {
            showError(telephone, "Veuillez entrer un numéro de téléphone valide.");
        } else {
            removeError(telephone);
        }
    }

    function validateEmail() {
        const email = document.getElementById('email');
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email.value.trim())) {
            showError(email, "Veuillez entrer une adresse e-mail valide.");
        } else {
            removeError(email);
        }
    }

    function validateAdresse() {
        const adresse = document.getElementById('adresse');
        const adressePattern = /^\d+\s+(bis\s+)?[A-Za-z\s]+/i;
        if (!adressePattern.test(adresse.value.trim())) {
            showError(adresse, "Veuillez entrer une adresse postale valide.");
        } else {
            removeError(adresse);
        }
    }

    function validateCode() {
        const code = document.getElementById('code');
        const codePattern = /^[0-9]{5}$/;
        if (!codePattern.test(code.value.trim())) {
            showError(code, "Veuillez entrer un code postal valide.");
        } else {
            removeError(code);
        }
    }

    function validateVille() {
        const ville = document.getElementById('ville');
        const villePattern = /^[A-Za-z\s\-]+$/;
        if (!villePattern.test(ville.value.trim())) {
            showError(ville, "Veuillez entrer une ville valide.");
        } else {
            removeError(ville);
        }
    }

    function validateSiren() {
        const siren = document.getElementById('siren');
        const sirenPattern = /^(?:\d{3} \d{3} \d{3}|\d{9})$/;
        if (document.getElementById('radioPrive').checked && !sirenPattern.test(siren.value.trim())) {
            showError(siren, "Veuillez entrer un numéro de SIREN valide.");
        } else {
            removeError(siren);
        }
    }

    function validatePassword() {
        const motdepasse = document.getElementById('motdepasse');
        const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{10,}$/;
        if (!passwordPattern.test(motdepasse.value.trim())) {
            showError(motdepasse, "Le mot de passe doit contenir au moins 10 caractères, une majuscule, une minuscule et un chiffre.");
        } else {
            removeError(motdepasse);
        }
    }

    function validateConfirmPassword() {
        const motdepasse = document.getElementById('motdepasse').value.trim();
        const confirmer = document.getElementById('confirmer').value.trim();
        if (motdepasse !== confirmer) {
            showError(document.getElementById('confirmer'), "Les mots de passe ne correspondent pas.");
        } else {
            removeError(document.getElementById('confirmer'));
        }
    }

    function validateCGU() {
        const cgu = document.getElementById('cgu');
        if (!cgu.checked) {
            showError(cgu, "Vous devez accepter les conditions générales d'utilisation.");
        } else {
            removeError(cgu);
        }
    }
});