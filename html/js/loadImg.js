/**
 * Gestion du chargement des images lors des modifications d'images
 * @author Gabriel, Ewen
 * Flexibilité Mattéo
 */

let existingImagesCount = []; // Compteur des images existantes sur le serveur

// Fonction pour charger les images existantes
function loadExistingImages(dossierImg, zoneImg, limit, idOffre, indexCountImg) {
    fetch('upload.php?dossierImg='+dossierImg+'&idOffre=' + idOffre)
        .then(response => response.json())
        .then(data => {
            zoneImg.innerHTML = ""; // Réinitialise la liste
            data.images.forEach((image, index) => {
                const div = document.createElement('div');
                const img = document.createElement('img');
                img.src = `${dossierImg}${idOffre}/${image}`;
                img.alt = image;
                img.title = `Cliquez pour supprimer ${image}`;
                img.style.cursor = 'pointer';
                
                // Ajouter un attribut data-index pour garder une référence unique de l'image
                img.setAttribute('data-index', index);
            
                // Ajoute un gestionnaire de clic pour supprimer l'image
                
                const croix = doucment.createElement('img');
                croix.src = `img/icone/croix_blanche.png`;
                croix.alt = image;
                croix.title = `Cliquez pour supprimer ${image}`;
                croix.onclick = () => {
                    if (confirm("Êtes-vous sûr de vouloir supprimer cette image ?")) {
                        deleteImage(image, div, index, dossierImg, zoneImg, limit, idOffre, indexCountImg); // Supprime l'image si l'utilisateur confirme
                    }
                };
                
                div.appendChild(img);
                div.appendChild(croix);
                zoneImg.appendChild(div);
            });
            existingImagesCount[indexCountImg] = data.images.length; // Met à jour le compteur d'images existantes
        })
        .catch(error => console.error('Erreur de chargement des images:', error)
    );
}

// Fonction pour supprimer une image existante
function deleteImage(fileName, imgElement, index, dossierImg, zoneImg, limit, idOffre, indexCountImg) {
    // Supprimer l'image du DOM immédiatement
    imgElement.remove();

    // Faire la requête de suppression côté serveur
    fetch('upload.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=delete&fileName=${encodeURIComponent(fileName)}&idOffre=${idOffre}&dossierImg=${dossierImg}`,
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            // En cas d'erreur côté serveur, restaurer l'image dans le DOM et afficher un message
            alert('Erreur lors de la suppression de l\'image sur le serveur : ' + data.error);
            // Recharger la liste des images pour restaurer l'état correct
            loadExistingImages(dossierImg, zoneImg, limit, idOffre);
        }
    })
    .catch(error => {
        // En cas de problème avec la requête, restaurer l'image et afficher un message d'erreur
        alert('Erreur lors de la suppression de l\'image. Veuillez réessayer.');
        console.log(error);
        // Recharger la liste des images pour restaurer l'état
        loadExistingImages(dossierImg, zoneImg, limit, idOffre);
    });
    checkImg();
}

// Fonction pour gérer les fichiers sélectionnés
function handleFiles(input, dossierImg, zoneImg, limit, idOffre, indexCountImg) {
    const maxFiles = limit;

    // Vérifie la limite d'images
    const totalSelected = existingImagesCount[indexCountImg] + input.files.length;

    if (totalSelected > maxFiles) { 
        alert(
            `Erreur : Vous ne pouvez importer que ${maxFiles} photos maximum.\n` +
            `${existingImagesCount[indexCountImg]} sont déjà sur le serveur, et vous avez sélectionné ${input.files.length}.`
        );
        input.value = ""; // Réinitialise la sélection
        return;
    }

    // Ajoute les nouvelles images sélectionnées
    Array.from(input.files).forEach(file => {
        if (existingImagesCount[indexCountImg] < maxFiles) {
            if (file.type.startsWith("image/")) {
                // Envoyer directement chaque fichier pour importation
                uploadFile(file, dossierImg, zoneImg, limit, idOffre);
            }
        }
    });

    input.value = ""; // Réinitialise l'input
}

// Fonction pour envoyer un fichier au serveur
function uploadFile(file, dossierImg, zoneImg, limit, idOffre, indexCountImg) {
    const formData = new FormData();
    formData.append('images[]', file);
    formData.append('action', 'upload');
    formData.append('idOffre', idOffre);
    formData.append('dossierImg', dossierImg);
    formData.append('limit', limit);

    fetch('upload.php', {
        method: 'POST',
        body: formData,
    })
        .then(response => response.json())
        .then(data => {
            const errors = data.filter(result => result.error);
            if (errors.length > 0) {
                alert(`Erreur lors du téléchargement de "${file.name}" :\n${errors.map(e => e.error).join('\n')}`);
            } else {
                loadExistingImages(dossierImg, zoneImg, limit, idOffre, indexCountImg); // Recharge la liste des images existantes
            }
        })
        .catch(error => console.error('Erreur lors de l\'envoi du fichier:', error));
}

// Charger les images existantes au chargement de la page
function loadEventLoadImg(inputFile, dossierImg, zoneImg, limit, idOffre) {
    const indexCountImg = existingImagesCount.length;
    existingImagesCount[indexCountImg] = 0;

    // Rafraîchir la liste manuellement
    window.onload = loadExistingImages(dossierImg, zoneImg, limit, idOffre, indexCountImg);

    // Gestion des fichiers sélectionnés (liaison de l'événement onchange)
    inputFile.addEventListener('change', function () {
        handleFiles(inputFile, dossierImg, zoneImg, limit, idOffre, indexCountImg);
    });
}