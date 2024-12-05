<section>
    <form action="submitNote.php" method="post" enctype="multipart/form-data">
        <div id="note">
            <!-- Étoiles pour la notation -->
            <?php for ($i = 1; $i <= 5; $i++) { ?>
                <div 
                    class="star ecrire vide" 
                    id="star-<?=$i?>" 
                    role="button" 
                    aria-label="Étoile <?= $i ?> sur 5">
                </div>
            <?php } ?>
            <input name="note" id="note-value" type="hidden" value="0">
        </div>

        <!-- Champ pour la date -->
        <div>
            <label for="date-avis">Donnez la date de visite : *</label>
            <input 
                type="month" 
                id="date-avis" 
                name="date" 
                min="<?= date('Y-m', strtotime('-1 year')) ?>" 
                max="<?= date('Y-m') ?>" 
                required>
        </div>

        <!-- Qui vous accompagnait -->
        <div>
            <label>Qui vous accompagnait ? *</label>
            <div id="enCompagnie">
                <input type="radio" id="seul" name="compagnie" value="Seul" required>
                <label class="tag" for="seul">Seul(e)</label>
                
                <input type="radio" id="amis" name="compagnie" value="Amis">
                <label class="tag" for="amis">Amis</label>
                
                <input type="radio" id="famille" name="compagnie" value="En_Famille">
                <label class="tag" for="famille">En famille</label>
                
                <input type="radio" id="couple" name="compagnie" value="Couple">
                <label class="tag" for="couple">Couple</label>

                <input type="radio" id="affaire" name="compagnie" value="Affaire">
                <label class="tag" for="affaire">Affaire</label>
            </div>
        </div>

        <!-- Titre et Avis -->
        <div>
            <label for="titre">Donnez un titre à l'avis *</label>
            <input id="titre" name="titre" type="text" required>
        </div>
        <div>
            <label for="avis">Ajoutez votre commentaire *</label>
            <textarea id="avis" name="avis" required></textarea>
        </div>

        <!-- Photos -->
        <div>
        <label for="ajoutPhoto" class="buttonDetailOffer blueBtnOffer">Ajouter</label>
            <!-- <input type="file" id="ajoutPhoto" name="ajoutPhoto[]" accept="image/PNG, image/JPG, image/JPEG, image/WEBP, image/GIF" method="post" multiple>  je teste-->
            <!-- <div id="afficheImages"></div> Gabriel je teste avec mon truc ewen  -->
            <input 
                type="file" 
                id="ajoutPhoto" 
                name="images[]" 
                accept="image/PNG, image/JPG, image/JPEG, image/WEBP, image/GIF"
                multiple 
                onchange="handleFiles(this)"
            />
            <div id="afficheImages"></div>

        <!-- Consentement -->
        <div>
            <input id="consentement" name="consentement" type="checkbox" required>
            <label for="consentement">Je certifie que cet avis reflète ma propre expérience et mon opinion authentique sur cet établissement.</label> 
        </div>  

        <button type="submit">Soumettre l'avis</button>
    </form>
</section>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const etoiles = document.querySelectorAll(".star");
        const noteInput = document.getElementById("note-value");

        // Note actuellement sélectionnée
        let noteActuelle = 0;

        etoiles.forEach((etoile, index) => {
            // Survol : remplit les étoiles jusqu'à l'étoile survolée
            etoile.addEventListener("mouseover", () => {
                survolerEtoiles(index + 1);
            });

            // Sortie du survol : restaure l'état initial
            etoile.addEventListener("mouseout", () => {
                reinitialiserSurvol();
            });

            // Clic : enregistre la note de manière permanente
            etoile.addEventListener("click", () => {
                definirNote(index + 1);
            });
        });

        // Remplit les étoiles jusqu'à note pour le survol
        function survolerEtoiles(note) {
            etoiles.forEach((etoile, i) => {
                if (i < note) {
                    etoile.classList.add("pleine");
                } else {
                    etoile.classList.remove("pleine");
                }
            });
        }

        // Réinitialise les étoiles après le survol
        function reinitialiserSurvol() {
            etoiles.forEach((etoile, i) => {
                etoile.classList.remove("pleine");
                if (i < noteActuelle) {
                    etoile.classList.add("pleine");
                } else {
                    etoile.classList.add("vide");
                }
            });
        }

        // Définit la note finale, met à jour les étoiles et l'input caché
        function definirNote(note) {
            noteActuelle = note;
            noteA = note%5;
            if(noteA == 0){
                noteA = 5
            }
            noteInput.value = noteA; // Met à jour l'input caché
            etoiles.forEach((etoile, i) => {
                if (i < note) {
                    etoile.classList.add("pleine");
                    etoile.classList.remove("vide");
                } else {
                    etoile.classList.remove("pleine");
                    etoile.classList.add("vide");
                }
            });
        }
    });

    // Fonction pour charger les images existantes
    function loadExistingImages() {
            fetch('upload.php?idOffre=' + idOffre)
                .then(response => response.json())
                .then(data => {
                    const existingPreview = document.getElementById('afficheImages');
                    existingPreview.innerHTML = ""; // Réinitialise la liste
                    data.images.forEach((image, index) => {
                        const img = document.createElement('img');
                        img.src = `img/imageOffre/${idOffre}/${image}`;
                        img.alt = image;
                        img.title = `Cliquez pour supprimer ${image}`;
                        img.style.cursor = 'pointer';
                    
                        // Ajouter un attribut data-index pour garder une référence unique de l'image
                        img.setAttribute('data-index', index);
                    
                        // Ajoute un gestionnaire de clic pour supprimer l'image
                        img.onclick = () => deleteImage(image, img, index); // Passe aussi l'index pour supprimer la bonne image côté client
                    
                        existingPreview.appendChild(img);
                    });
                    existingImagesCount = data.images.length; // Met à jour le compteur d'images existantes
                })
                .catch(error => console.error('Erreur de chargement des images:', error));
        }

// Fonction pour supprimer une image existante
function deleteImage(fileName, imgElement, index) {
    // Supprimer l'image du DOM immédiatement
    imgElement.remove();

    // Faire la requête de suppression côté serveur
    fetch('upload.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=delete&fileName=${encodeURIComponent(fileName)}&idOffre=${idOffre}`,
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            // En cas d'erreur côté serveur, restaurer l'image dans le DOM et afficher un message
            alert('Erreur lors de la suppression de l\'image sur le serveur : ' + data.error);
            // Recharger la liste des images pour restaurer l'état correct
            loadExistingImages();
        } else {
            // Si la suppression a réussi, afficher un message de succès
            alert('Image supprimée avec succès.');
            // Recharger la liste des images pour assurer que l'état est mis à jour
        }
    })
    .catch(error => {
        // En cas de problème avec la requête, restaurer l'image et afficher un message d'erreur
        alert('Erreur lors de la suppression de l\'image. Veuillez réessayer.');
        console.log(error);
        // Recharger la liste des images pour restaurer l'état
        loadExistingImages();
    });
}

    // Fonction pour gérer les fichiers sélectionnés
    function handleFiles(input) {
            const maxFiles = 10;
            // Vérifie la limite d'images
            const totalSelected = existingImagesCount + input.files.length;

            if (totalSelected > maxFiles) { 
                alert(
                    `Erreur : Vous ne pouvez importer que ${maxFiles} photos maximum.\n` +
                    `${existingImagesCount} sont déjà sur le serveur, et vous avez sélectionné ${input.files.length}.`
                );
                input.value = ""; // Réinitialise la sélection
                return;
            }

            // Ajoute les nouvelles images sélectionnées
            Array.from(input.files).forEach(file => {
                if (existingImagesCount < maxFiles) {
                    if (file.type.startsWith("image/")) {
                        // Envoyer directement chaque fichier pour importation
                        uploadFile(file);
                    }
                }
            });

            input.value = ""; // Réinitialise l'input
        }

        // Fonction pour envoyer un fichier au serveur
        function uploadFile(file) {
            const formData = new FormData();
            formData.append('images[]', file);
            formData.append('action', 'upload');
            formData.append('idOffre', idOffre);

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
                        alert(`Fichier "${file.name}" téléchargé avec succès !`);
                        loadExistingImages(); // Recharge la liste des images existantes
                    }
                })
                .catch(error => console.error('Erreur lors de l\'envoi du fichier:', error));
        }
</script>
