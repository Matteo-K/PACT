document.addEventListener('DOMContentLoaded', function() {
    // Gestion du bouton retour
    document.getElementById('retour').addEventListener('click', function() {
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
});

// Fonction pour afficher les messages d'erreur à côté de chaque champ
function displayFieldError(inputElement, messageErreur) {
    const errorElement = inputElement.nextElementSibling; // Supposons qu'un élément d'erreur est placé après le champ
    if (!errorElement || !errorElement.classList.contains('error-message')) {
        // Si l'élément d'erreur n'existe pas, on le crée
        const errorDiv = document.createElement('div');
        errorDiv.classList.add('error-message');
        inputElement.parentNode.appendChild(errorDiv);
    }
    inputElement.classList.add('error');
    inputElement.classList.remove('valid');
    errorElement.innerHTML = messageErreur;  // Affiche l'erreur
}

// Fonction pour supprimer les messages d'erreur et rétablir les styles
function clearFieldError(inputElement) {
    const errorElement = inputElement.nextElementSibling;
    if (errorElement && errorElement.classList.contains('error-message')) {
        errorElement.innerHTML = '';  // Effacer le message
        inputElement.classList.remove('error');
        inputElement.classList.add('valid');
    }
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

// Validation lors du "blur" de chaque champ
document.getElementById('denomination').addEventListener('blur', function() {
    const pattern = /^.+$/;  // Vérifier que le champ n'est pas vide
    validateField(this, pattern, 'La dénomination est obligatoire.');
});

document.getElementById('telephone').addEventListener('blur', function() {
    const phonePattern = /^0[1-9]([.\-/]?[0-9]{2}){4}$/;
    validateField(this, phonePattern, 'Veuillez entrer un numéro de téléphone valide.');
});

document.getElementById('email').addEventListener('blur', function() {
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    validateField(this, emailPattern, 'Veuillez entrer une adresse e-mail valide.');
});

document.getElementById('adresse').addEventListener('blur', function() {
    const adressePattern = /^\d+\s+(bis\s+)?[A-Za-z\s]+/i;
    validateField(this, adressePattern, 'Veuillez entrer une adresse postale valide (ex : 123 Rue de Paris).');
});

document.getElementById('code').addEventListener('blur', function() {
    const codePattern = /^[0-9]{5}$/;
    validateField(this, codePattern, 'Veuillez entrer un code postal valide (5 chiffres).');
});

document.getElementById('ville').addEventListener('blur', function() {
    const villePattern = /^[A-Za-z\s\-]+$/;
    validateField(this, villePattern, 'Veuillez entrer une ville valide (lettres et espaces seulement).');
});

document.getElementById('siren').addEventListener('blur', function() {
    const sirenPattern = /^(?:\d{3} \d{3} \d{3}|\d{9})$/;
    validateField(this, sirenPattern, 'Veuillez entrer un numéro de SIREN valide (ex : 123 456 789).');
});

document.getElementById('motdepasse').addEventListener('blur', function() {
    const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{10,}$/;
    validateField(this, passwordPattern, 'Le mot de passe doit contenir au moins 10 caractères, dont une majuscule, une minuscule et un chiffre.');
});

document.getElementById('confirmer').addEventListener('blur', function() {
    const motdepasse = document.getElementById('motdepasse').value;
    if (this.value !== motdepasse) {
        displayFieldError(this, 'Les mots de passe ne correspondent pas.');
    } else {
        clearFieldError(this);
    }
});

document.getElementById('cgu').addEventListener('change', function() {
    const cguChecked = this.checked;
    if (!cguChecked) {
        displayFieldError(this, 'Vous devez accepter les conditions générales d\'utilisation.');
    } else {
        clearFieldError(this);
    }
});
