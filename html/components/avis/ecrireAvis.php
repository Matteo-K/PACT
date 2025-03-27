<section>
    <form id="formCreationAvis" onsubmit="return validerFormulaire()" action="/enregAvis.php?membre" method="post" enctype="multipart/form-data">
        
        <span id="error_form" style="display: none; color: red;"></span>
        <div class="note">
            <!-- Étoiles pour la notation -->
            <?php for ($i = 1; $i <= 5; $i++) { ?>
                <div
                    class="star ecrire vide"
                    id="star-<?= $i ?>"
                    role="button"
                    aria-label="Étoile <?= $i ?> sur 5">
                </div>
            <?php } ?>
            <input name="note" id="note-value" type="hidden" value="" required>
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
                value="<?= date('Y-m') ?>"
                required>
        </div>

        <!-- Qui vous accompagnait -->
        <div id='accompagnant'>
            <label>Qui vous accompagnait ? *</label>
            <div id="enCompagnie">
                <input type="radio" id="seul" name="compagnie" value="Seul">
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
            <label id="ajoutTitre" for="titre">Donnez un titre à l'avis *</label>
            <input id="titre" name="titre" type="text" required>
        </div>
        <div>
            <label id="ajoutCommentaire" for="avis">Ajoutez votre commentaire *</label>
            <textarea id="avis" name="avis" required></textarea>
        </div>

        <!-- Photos -->
        <div id="divAjoutPhoto">
            <label id="btnAjoutPhoto" for="ajoutPhoto" class="classAjouterPhotos">
                <img src="./img/icone/addImage.png" alt="Icone d'ajout d'image" title="Icone d'ajout d'image">
                <p>Ajouter des Photos</p>
                </label>
            <input
                type="file"
                id="ajoutPhoto"
                name="images[]"
                accept="image/PNG, image/JPG, image/JPEG, image/WEBP, image/GIF"
                multiple
                onchange="handleFiles(this)" />
            <div id="afficheImagesAvis"></div>
        </div>
        <!-- Consentement -->
        <div>
            <input id="consentement" name="consentement" type="checkbox" required>
            <label for="consentement">Je certifie que cet avis reflète ma propre expérience et mon opinion authentique sur cet établissement.</label>
        </div>

        <input type="hidden" name="idoffre" value="<?= $idOffre ?>">
        <input type="hidden" name="action" value="ecrireAvis">
        <div class="soumission">
            <button type="submit">Soumettre l'avis</button>
        </div>
    </form>
</section>

<script>
    const uniqueId = generateUniqueId();
    document.addEventListener("DOMContentLoaded", () => {
        const etoiles = document.querySelectorAll(".star.ecrire");
        const noteInput = document.getElementById("note-value");

        let formCreationAvis = document.getElementById("formCreationAvis");
        let input = document.createElement("input");
        input.type = "hidden";
        input.name = "uniqueField";
        input.value = uniqueId;
        formCreationAvis.appendChild(input);

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

    function validerFormulaire() {
        const radios = document.getElementsByName('compagnie');
        let selectionne = false;

        // Vérifie si l'une des options radio est sélectionnée
        for (let i = 0; i < radios.length; i++) {
            if (radios[i].checked) {
                selectionne = true;
                break;
            }
        }

        // Si aucune option n'est sélectionnée, afficher l'erreur et empêcher l'envoi du formulaire
        if (!selectionne) {
            const errorMessage = document.getElementById("error_form");
            errorMessage.textContent = "Veuillez sélectionner qui vous accompagnait.";
            errorMessage.style.display = "block"; // Affiche le message d'erreur
            return false; // Empêche la soumission du formulaire
        }

        // Si tout est valide, permettre la soumission
        return true;
    }

    // Fonction pour générer un ID unique
    function generateUniqueId() {
        return "temp_" + Math.random().toString(36).substr(2, 9);
    }
</script>
