document.addEventListener('DOMContentLoaded', function () {
    const messageErreurDiv = document.getElementById('messageErreur');
    const form = document.getElementById('formPro') || document.getElementById('formMember');

    // Gestion du bouton retour
    document.getElementById('retour').addEventListener('click', function () {
        window.history.back();
    });

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
        } else {
            sirenLabel.style.display = "none"; // Cache le label
            sirenInput.style.display = "none"; // Cache le champ
        }
    }

    // Initialiser l'affichage
    updateSirenVisibility();

    // Ajouter des écouteurs d'événements sur les boutons radio
    publicRadio.addEventListener("click", updateSirenVisibility);
    priveRadio.addEventListener("click", updateSirenVisibility);



    // Fonction pour afficher les erreurs globales
    function displayGlobalErrors(errors) {
        messageErreurDiv.innerHTML = ''; // Efface les erreurs précédentes
        if (errors.length > 0) {
            messageErreurDiv.style.display = 'block';
            const errorList = document.createElement('ul');
            errors.forEach(error => {
                const li = document.createElement('li');
                li.textContent = error;
                errorList.appendChild(li);
            });
            messageErreurDiv.appendChild(errorList);
        } else {
            messageErreurDiv.style.display = 'none';
        }
    }

    // Fonction pour afficher un message d'erreur sur un champ
    function displayFieldError(inputElement, messageErreur) {
        const errorElement = document.querySelector("#messageErreur");
        if (errorElement) {
            errorElement.textContent = messageErreur;
        }
        inputElement.style.borderColor = 'red';
    }

    // Fonction pour effacer un message d'erreur sur un champ
    function clearFieldError(inputElement) {
        const errorElement = document.querySelector("#messageErreur");
        if (errorElement) {
            errorElement.innerText = "";
        }
        inputElement.style.borderColor = '';
    }

    // Fonction de validation pour chaque champ
    function validateField(inputElement, pattern, messageErreur) {
        if (!pattern.test(inputElement.value.trim())) {
            displayFieldError(inputElement, messageErreur);
            return false;
        } else {
            clearFieldError(inputElement);
            return true;
        }
    }

    // Récupérer le nom du fichier actuel
    const currentFile = window.location.pathname.split('/').pop();

    // Définir les champs à valider en fonction du formulaire
    let fieldsToValidate = [];

    if (currentFile === 'accountPro.php') {
        fieldsToValidate = [
            { id: 'denomination', pattern: /^.+$/, message: 'La dénomination est obligatoire.' },
            { id: 'telephone', pattern: /^0[1-9]([.\-/]?[0-9]{2}|\s?[0-9]{2}){4}$/, message: 'Numéro de téléphone invalide.' },
            { id: 'email', pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/, message: 'E-mail invalide.' },
            { id: 'adresse', pattern: /^\d+\s+(bis\s+)?[A-Za-z\s]+/i, message: 'Adresse invalide.' },
            { id: 'code', pattern: /^(?:[0-9]{5}|\d{2} \d{3})$/, message: 'Code postal invalide.' },
            { id: 'ville', pattern: /^[A-Za-z\s\-]+$/, message: 'Ville invalide.' },
            { id: 'siren', pattern: /^(?:\d{3}[\s.\-/]?\d{3}[\s.\-/]?\d{3})$/, message: 'Numéro de SIREN invalide.' },
            { id: 'motdepasse', pattern: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\w\d\s]).{10,}$/, message: 'Mot de passe invalide.' },
        ];
    } else if (currentFile === 'accountMember.php') {
        fieldsToValidate = [
            { id: 'nomMembre', pattern: /^.+$/, message: 'Le nom est obligatoire.' },
            { id: 'prenomMembre', pattern: /^.+$/, message: 'Le prénom est obligatoire.' },
            { id: 'pseudoMembre', pattern: /^.+$/, message: 'Le pseudo est obligatoire.' },
            { id: 'telephoneMembre', pattern: /^0[1-9]([.\-/]?[0-9]{2}|\s?[0-9]{2}){4}$/, message: 'Numéro de téléphone invalide.' },
            { id: 'email', pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/, message: 'E-mail invalide.' },
            { id: 'adresse', pattern: /^\d+\s+(bis\s+)?[A-Za-z\s]+/i, message: 'Adresse invalide.' },
            { id: 'code', pattern: /^(?:[0-9]{5}|\d{2} \d{3})$/, message: 'Code postal invalide.' },
            { id: 'ville', pattern: /^[A-Za-z\s\-]+$/, message: 'Ville invalide.' },
            { id: 'motdepasse', pattern: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\w\d\s]).{10,}$/, message: 'Mot de passe invalide.' },
        ];
    }

    // Ajouter l'événement "blur" pour les champs
    fieldsToValidate.forEach(field => {
        const inputElement = document.getElementById(field.id);
        if (inputElement) {
            inputElement.addEventListener('blur', function () {
                validateField(inputElement, field.pattern, field.message);
            });
        }
    });

    // Validation de la confirmation du mot de passe
    const confirmPasswordField = document.getElementById('confirmer');
    if (confirmPasswordField) {
        confirmPasswordField.addEventListener('blur', function () {
            const passwordField = document.getElementById('motdepasse');
            if (passwordField && passwordField.value !== confirmPasswordField.value) {
                displayFieldError(confirmPasswordField, 'Les mots de passe ne correspondent pas.');
            } else {
                clearFieldError(confirmPasswordField);
            }
        });
    }

    // Validation globale lors de la soumission
    if (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            const errors = [];

            fieldsToValidate.forEach(field => {
                const inputElement = document.getElementById(field.id);
                if (inputElement && !validateField(inputElement, field.pattern, field.message)) {
                    errors.push(field.message);
                }
            });

            if (confirmPasswordField) {
                const passwordField = document.getElementById('motdepasse');
                if (passwordField && passwordField.value !== confirmPasswordField.value) {
                    errors.push('Les mots de passe ne correspondent pas.');
                }
            }

            if (!document.getElementById('cgu').checked) {
                errors.push('Vous devez accepter les conditions générales d\'utilisation.');
            }

            displayGlobalErrors(errors);

            if (errors.length === 0) {
                form.submit();
            }
        });
    }
});