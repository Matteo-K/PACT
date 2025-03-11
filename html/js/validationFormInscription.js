document.addEventListener('DOMContentLoaded', function () {
    const messageErreurDiv = document.getElementById('messageErreur');
    const form = document.getElementById('formPro') || document.getElementById('formMember');   

    // Gestion du bouton retour
    document.getElementById('retour').addEventListener('click', function () {
        window.history.back();
    });

    // Vérifier si des erreurs ont été passées du côté serveur et les afficher
    // if (messageErreurDiv.innerHTML.trim() !== "") {
    //     messageErreurDiv.classList.add('show');
    // }

    // Récupérer les éléments nécessaires pour le SIREN
    const sirenLabel = document.querySelector("label[for='siren']");
    const sirenInput = document.getElementById("siren");
    const publicRadio = document.getElementById("radioPublic");
    const priveRadio = document.getElementById("radioPrive");


    
    // Fonction pour mettre à jour l'affichage du SIREN
    function updateSirenVisibility() {
        if (priveRadio.checked) {
            sirenLabel.style.display = "block";
            sirenInput.style.display = "block";
            sirenInput.setAttribute('required', 'required');
        } 
        
        else {
            sirenLabel.style.display = "none";
            sirenInput.style.display = "none";
            sirenInput.removeAttribute('required');
        }
    }



    // Initialiser l'affichage si on est dans accountPro.php ou dans changeAccountPro.php
    if (sirenInput && sirenLabel) {
        updateSirenVisibility();
        publicRadio?.addEventListener("click", updateSirenVisibility);
        priveRadio?.addEventListener("click", updateSirenVisibility);
    }



    // Fonction pour afficher les erreurs globales dans la div messageErreur
    function displayGlobalErrors(errors) {
        messageErreurDiv.innerHTML = '';
        if (errors.length > 0) {
            messageErreurDiv.style.display = 'block';
            const errorList = document.createElement('ul');

            errors.forEach(error => {
                const li = document.createElement('li');
                li.textContent = error;
                errorList.appendChild(li);
            });

            messageErreurDiv.appendChild(errorList);
        } 
        
        else {
            messageErreurDiv.style.display = 'none';
        }
    }



    // Fonction pour afficher un message d'erreur pour un champ
    function displayFieldError(inputElement, messageErreur) {
        let errorElement = document.querySelector("#messageErreur");
        errorElement.textContent = messageErreur;
        inputElement.style.borderColor = 'red';
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }



    // Fonction pour effacer un message d'erreur pour un champ
    function clearFieldError(inputElement) {
        const errorElement = document.querySelector("#messageErreur");
        errorElement.innerText = "";
        inputElement.style.borderColor = '';
    }



    // Fonction de validation pour chaque champ
    function validateField(inputElement, pattern, messageErreur) {
        if (inputElement.style.display !== 'none' && inputElement.hasAttribute('required')) {
            if (!pattern.test(inputElement.value.trim())) {
                displayFieldError(inputElement, messageErreur);
                return false;
            } 
            
            else {
                clearFieldError(inputElement);
                return true;
            }
        }
        return true;
    }
    


    // Récupérer le nom du fichier actuel
    let currentFile;

    // if (sirenInput && sirenLabel) {
    //     currentFile = 'accountPro.php';
    // }
    
    // else {
    //     currentFile = 'accountMember.php';
    // }

    if (window.location.pathname.includes("accountPro")) {
        currentFile = 'accountPro.php';
    } 
    
    else if (window.location.pathname.includes("accountMember")) {
        currentFile = 'accountMember.php';
    } 
    
    else if (window.location.pathname.includes("changeAccountPro")) {
        currentFile = 'changeAccountPro.php';
    } 
    
    else if (window.location.pathname.includes("changeAccountMember")) {
        currentFile = 'changeAccountMember.php';
    }


    // Définir le tableau fieldsToValidate en fonction du fichier
    let fieldsToValidate;

    if (currentFile === 'accountPro.php') {
        fieldsToValidate = [
            { id: 'denomination', pattern: /^.+$/, message: 'La dénomination est obligatoire.' },
            { id: 'telephone', pattern: /^0[1-9]([.\-/]?[0-9]{2}|\s?[0-9]{2}){4}$/, message: 'Veuillez entrer un numéro de téléphone valide (avec espaces, sans espaces, points, tirets ou slashs).' },
            { id: 'email', pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/, message: 'Veuillez entrer une adresse e-mail valide (avec un @ et un point, ex: exemple@gmail.com).' },
            { id: 'adresse', pattern: /^\d+\s+(bis\s+)?[A-Za-z\s]+/i, message: 'Veuillez entrer une adresse postale valide (avec un numéro de rue et le nom de la rue, ex: 123 Rue de Brest).' },
            { id: 'code', pattern: /^(?:[0-9]{5}|\d{2} \d{3})$/, message: 'Veuillez entrer un code postal valide (ex: 29000 ou 29 000).' },
            { id: 'ville', pattern: /^[A-Za-z\s\-]+$/, message: 'Veuillez entrer une ville valide.' },
            { id: 'siren', pattern: /^(?:\d{3}[\s.\-/]?\d{3}[\s.\-/]?\d{3})$/, message: 'Veuillez entrer un numéro de SIREN valide (avec espaces, sans espaces, points, tirets ou slashs).' },
            { id: 'motdepasse', pattern: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\w\d\s]).{10,}$/, message: 'Le mot de passe doit contenir au moins 10 caractères, dont une majuscule, une minuscule, un chiffre et un caractère spécial.' },
        ];
    } 
    
    else if (currentFile === 'accountMember.php') {
        fieldsToValidate = [
            { id: 'nomMembre', pattern: /^.+$/, message: 'Le nom est obligatoire.' },
            { id: 'prenomMembre', pattern: /^.+$/, message: 'Le prenom est obligatoire.' },
            { id: 'pseudoMembre', pattern: /^.+$/, message: 'Le pseudo est obligatoire.' },

            { id: 'telephoneMembre', pattern: /^0[1-9]([.\-/]?[0-9]{2}|\s?[0-9]{2}){4}$/, message: 'Veuillez entrer un numéro de téléphone valide (avec espaces, sans espaces, points, tirets ou slashs).' },
            { id: 'email', pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/, message: 'Veuillez entrer une adresse e-mail valide (avec un @ et un point, ex: exemple@gmail.com).' },
            { id: 'adresse', pattern: /^\d+\s+(bis\s+)?[A-Za-z\s]+/i, message: 'Veuillez entrer une adresse postale valide (avec un numéro de rue et le nom de la rue, ex: 123 Rue de Brest).' },
            { id: 'code', pattern: /^(?:[0-9]{5}|\d{2} \d{3})$/, message: 'Veuillez entrer un code postal valide (ex: 29000 ou 29 000).' },
            { id: 'ville', pattern: /^[A-Za-z\s\-]+$/, message: 'Veuillez entrer une ville valide.' },
            { id: 'motdepasse', pattern: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\w\d\s]).{10,}$/, message: 'Le mot de passe doit contenir au moins 10 caractères, dont une majuscule, une minuscule, un chiffre et un caractère spécial.' },
        ];
    }

    else if (currentFile === 'changeAccountPro.php') {
        fieldsToValidate = [
            { id: 'denomination', pattern: /^.+$/, message: 'La dénomination est obligatoire.' },

            { id: 'telephone', pattern: /^0[1-9]([.\-/]?[0-9]{2}|\s?[0-9]{2}){4}$/, message: 'Veuillez entrer un numéro de téléphone valide (avec espaces, sans espaces, points, tirets ou slashs).' },
            { id: 'email', pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/, message: 'Veuillez entrer une adresse e-mail valide (avec un @ et un point, ex: exemple@gmail.com).' },
            { id: 'adresse', pattern: /^\d+\s+(bis\s+)?[A-Za-z\s]+/i, message: 'Veuillez entrer une adresse postale valide (avec un numéro de rue et le nom de la rue, ex: 123 Rue de Brest).' },
            { id: 'code', pattern: /^(?:[0-9]{5}|\d{2} \d{3})$/, message: 'Veuillez entrer un code postal valide (ex: 29000 ou 29 000).' },
            { id: 'ville', pattern: /^[A-Za-z\s\-]+$/, message: 'Veuillez entrer une ville valide.' },
        ];
    }

    else if (currentFile === 'changeAccountMember.php') {
        fieldsToValidate = [
            { id: 'nomMembre', pattern: /^.+$/, message: 'Le nom est obligatoire.' },
            { id: 'prenomMembre', pattern: /^.+$/, message: 'Le prenom est obligatoire.' },
            { id: 'pseudoMembre', pattern: /^.+$/, message: 'Le pseudo est obligatoire.' },

            { id: 'telephoneMembre', pattern: /^0[1-9]([.\-/]?[0-9]{2}|\s?[0-9]{2}){4}$/, message: 'Veuillez entrer un numéro de téléphone valide (avec espaces, sans espaces, points, tirets ou slashs).' },
            { id: 'email', pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/, message: 'Veuillez entrer une adresse e-mail valide (avec un @ et un point, ex: exemple@gmail.com).' },
            { id: 'adresse', pattern: /^\d+\s+(bis\s+)?[A-Za-z\s]+/i, message: 'Veuillez entrer une adresse postale valide (avec un numéro de rue et le nom de la rue, ex: 123 Rue de Brest).' },
            { id: 'code', pattern: /^(?:[0-9]{5}|\d{2} \d{3})$/, message: 'Veuillez entrer un code postal valide (ex: 29000 ou 29 000).' },
            { id: 'ville', pattern: /^[A-Za-z\s\-]+$/, message: 'Veuillez entrer une ville valide.' },
        ];
    }



    // Ajouter l'événement "blur"
    fieldsToValidate.forEach(field => {
        const inputElement = document.getElementById(field.id);

        if (inputElement) {
            inputElement.addEventListener('blur', function () {
                validateField(inputElement, field.pattern, field.message);
            });
        }
    });



    // Validation pour la confirmation du mot de passe
    const confirmPasswordField = document.getElementById('confirmer');

    if(confirmPasswordField) {
        confirmPasswordField.addEventListener('blur', function () {
            const motdepasse = document.getElementById('motdepasse').value;
            const confirmer = confirmPasswordField.value;
    
            if (motdepasse !== confirmer) {
                displayFieldError(confirmPasswordField, 'Les mots de passe ne correspondent pas.');
            } 
            
            else {
                clearFieldError(confirmPasswordField);
            }
        });
    }



    // Validation globale lors de la soumission du formulaire
    form.addEventListener('submit', function (event) {
        event.preventDefault(); // Empêche l'envoi du formulaire

        const errors = [];

        // Vérifier les champs lors de la soumission
        fieldsToValidate.forEach(field => {
            const inputElement = document.getElementById(field.id);

            if (!validateField(inputElement, field.pattern, field.message)) {
                errors.push(field.message);
            }
        });

        if(confirmPasswordField) {
            const motdepasse = document.getElementById('motdepasse').value;
            const confirmer = document.getElementById('confirmer').value;

            if (motdepasse !== confirmer) {
                errors.push('Les mots de passe ne correspondent pas.');
            }

            if (!document.getElementById('cgu').checked) {
                errors.push('Vous devez accepter les conditions générales d\'utilisation.');
            }

            // Afficher les erreurs globales
            displayGlobalErrors(errors);

        }
        // Si aucune erreur, envoyer le formulaire
        if (errors.length === 0) {
            form.submit();
        }
    }); 
});