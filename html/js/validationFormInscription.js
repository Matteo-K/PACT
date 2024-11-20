document.addEventListener('DOMContentLoaded', function () {
    const messageErreurDiv = document.getElementById('messageErreur');
    const form = document.getElementById('formPro');

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

    // Fonction pour afficher les erreurs globalement dans la div
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

    // Fonction pour afficher un message d'erreur à côté d'un champ
    function displayFieldError(inputElement, messageErreur) {
        let errorElement = inputElement.nextElementSibling;
        if (!errorElement || !errorElement.classList.contains('error-message')) {
            errorElement = document.createElement('div');
            errorElement.classList.add('error-message');
            inputElement.parentNode.appendChild(errorElement);
        }
        inputElement.classList.add('error');
        inputElement.classList.remove('valid');
        errorElement.textContent = messageErreur;
    }

    // Fonction pour supprimer un message d'erreur et rétablir les styles
    function clearFieldError(inputElement) {
        const errorElement = inputElement.nextElementSibling;
        if (errorElement && errorElement.classList.contains('error-message')) {
            errorElement.remove();
        }
        inputElement.classList.remove('error');
        inputElement.classList.add('valid');
    }

    // Fonction pour valider un champ
    function validateField(inputElement, pattern, messageErreur) {
        if (!pattern.test(inputElement.value.trim())) {
            displayFieldError(inputElement, messageErreur);
            return false;
        } else {
            clearFieldError(inputElement);
            return true;
        }
    }

    // Validation complète au moment de la soumission
    form.addEventListener('submit', function (event) {
        event.preventDefault(); // Empêche l'envoi par défaut
        const errors = [];

        // Valider chaque champ
        if (!validateField(document.getElementById('denomination'), /^.+$/, 'La dénomination est obligatoire.')) {
            errors.push('La dénomination est obligatoire.');
        }

        if (!validateField(document.getElementById('telephone'), /^0[1-9]([.\-/]?[0-9]{2}){4}$/, 'Veuillez entrer un numéro de téléphone valide.')) {
            errors.push('Veuillez entrer un numéro de téléphone valide.');
        }

        if (!validateField(document.getElementById('email'), /^[^\s@]+@[^\s@]+\.[^\s@]+$/, 'Veuillez entrer une adresse e-mail valide.')) {
            errors.push('Veuillez entrer une adresse e-mail valide.');
        }

        if (!validateField(document.getElementById('adresse'), /^\d+\s+(bis\s+)?[A-Za-z\s]+/i, 'Veuillez entrer une adresse postale valide.')) {
            errors.push('Veuillez entrer une adresse postale valide.');
        }

        if (!validateField(document.getElementById('code'), /^[0-9]{5}$/, 'Veuillez entrer un code postal valide.')) {
            errors.push('Veuillez entrer un code postal valide.');
        }

        if (!validateField(document.getElementById('ville'), /^[A-Za-z\s\-]+$/, 'Veuillez entrer une ville valide.')) {
            errors.push('Veuillez entrer une ville valide.');
        }

        if (priveRadio.checked && !validateField(document.getElementById('siren'), /^(?:\d{3} \d{3} \d{3}|\d{9})$/, 'Veuillez entrer un numéro de SIREN valide.')) {
            errors.push('Veuillez entrer un numéro de SIREN valide.');
        }

        const motdepasse = document.getElementById('motdepasse').value;
        if (!validateField(document.getElementById('motdepasse'), /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{10,}$/, 'Le mot de passe doit contenir au moins 10 caractères, dont une majuscule, une minuscule et un chiffre.')) {
            errors.push('Le mot de passe doit contenir au moins 10 caractères, dont une majuscule, une minuscule et un chiffre.');
        }

        const confirmer = document.getElementById('confirmer').value;
        if (motdepasse !== confirmer) {
            displayFieldError(document.getElementById('confirmer'), 'Les mots de passe ne correspondent pas.');
            errors.push('Les mots de passe ne correspondent pas.');
        }

        if (!document.getElementById('cgu').checked) {
            errors.push('Vous devez accepter les conditions générales d\'utilisation.');
        }

        // Afficher les erreurs globales
        displayGlobalErrors(errors);

        // Si aucune erreur, envoyer le formulaire
        if (errors.length === 0) {
            form.submit();
        }
    });
});
