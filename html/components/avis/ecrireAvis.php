<section>
    <form action="" method="post" enctype="multipart/form-data">
        <div id="note">
            <!-- Étoiles pour la notation -->
            <?php for ($i = 1; $i <= 5; $i++) { ?>
                <div
                    class="star ecrire vide"
                    id="star-<?= $i ?>"
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
                onchange="handleFiles(this)" />
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
        const etoiles = document.querySelectorAll(".star.ecrire");
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
            noteA = note % 5;
            if (noteA == 0) {
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
    const uniqueId = generateUniqueId();

    function handleFiles(inputElement) {
        const maxImages = 3;
        const files = inputElement.files;
        const formData = new FormData();
        const afficheImages = document.getElementById("afficheImages");

        // Vérifie le nombre d'images à uploader
        if (files.length > maxImages) {
            alert(`Vous pouvez sélectionner au maximum ${maxImages} fichiers.`);
            return;
        }

        for (let i = 0; i < files.length; i++) {
            formData.append("images[]", files[i]);
        }

        // Ajoute un ID unique à l'upload pour un dossier temporaire
        formData.append("unique_id", uniqueId);

        // Envoie les fichiers au serveur via une requête AJAX
        fetch("upload_temp_files.php", {
                method: "POST",
                body: formData,
            })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    displayUploadedFiles(uniqueId); // Met à jour dynamiquement les images
                } else {
                    alert("Erreur lors de l'upload : " + data.message);
                }
            })
            .catch((error) => {
                console.error("Erreur lors de la requête :", error);
                alert("Une erreur est survenue pendant l'upload.");
            });
    }

    function displayUploadedFiles(uniqueId) {
        const afficheImages = document.getElementById("afficheImages");
        afficheImages.innerHTML = ""; // Réinitialise l'affichage

        fetch(`list_temp_files.php?unique_id=${uniqueId}`)
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    data.files.forEach((fileUrl) => {
                        const div = document.createElement("div");
                        div.classList.add("image-container");
                        div.style.position = "relative";

                        const img = document.createElement("img");
                        img.src = fileUrl;
                        img.alt = "Image uploaded";
                        img.style.width = "100px";
                        img.style.margin = "10px";

                        const deleteIcon = document.createElement("img");
                        deleteIcon.src = "img/icone/croix.png"; // Remplace par le chemin de ton image de croix
                        deleteIcon.alt = "Supprimer";
                        deleteIcon.style.width = "20px";
                        deleteIcon.style.height = "20px";
                        deleteIcon.style.position = "absolute";
                        deleteIcon.style.top = "5px";
                        deleteIcon.style.right = "5px";
                        deleteIcon.style.cursor = "pointer";

                        deleteIcon.addEventListener("click", () => {
                            deleteFile(fileUrl, uniqueId, div);
                        });

                        div.appendChild(img);
                        div.appendChild(deleteIcon);
                        afficheImages.appendChild(div);
                    });
                } else {
                    alert("Erreur lors de la récupération des fichiers : " + data.message);
                }
            })
            .catch((error) => {
                console.error("Erreur lors de la récupération :", error);
                alert("Une erreur est survenue pendant la récupération des fichiers.");
            });
    }

    function deleteFile(fileUrl, uniqueId, imageContainer) {
        const formData = new FormData();
        formData.append("fileUrl", fileUrl); // L'URL du fichier à supprimer
        formData.append("unique_id", uniqueId); // L'ID unique pour le dossier temporaire

        // Envoi de la requête AJAX pour supprimer le fichier
        fetch("delete_temp_files.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Si la suppression est réussie, retire l'image du DOM
                    imageContainer.remove();
                } else {
                    alert("Erreur lors de la suppression de l'image : " + data.message);
                }
            })
            .catch(error => {
                console.error("Erreur lors de la suppression :", error);
                alert("Une erreur est survenue pendant la suppression.");
            });
    }

    // Fonction pour générer un ID unique
    function generateUniqueId() {
        return "temp_" + Math.random().toString(36).substr(2, 9);
    }
</script>