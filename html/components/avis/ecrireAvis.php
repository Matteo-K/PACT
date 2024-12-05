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
            <label for="photo">Ajoutez des photos (3 maximum)</label>
            <p>Facultatif</p>
            <input type="file" id="photo" name="photo[]" multiple accept="image/*" max="3">
        </div>

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
            etoile.addEventListener("mouseover", () => survolerEtoiles(index + 1));

            // Sortie du survol : restaure l'état initial
            etoile.addEventListener("mouseout", reinitialiserSurvol);

            // Clic : enregistre la note de manière permanente
            etoile.addEventListener("click", () => definirNote(index + 1));
        });

        function survolerEtoiles(note) {
            etoiles.forEach((etoile, i) => {
                etoile.classList.toggle("pleine", i < note);
            });
        }

        function reinitialiserSurvol() {
            etoiles.forEach((etoile, i) => {
                etoile.classList.toggle("pleine", i < noteActuelle);
                etoile.classList.toggle("vide", i >= noteActuelle);
            });
        }

        function definirNote(note) {
            noteActuelle = note % 5 + 1;
            noteInput.value = note; // Met à jour l'input caché
            reinitialiserSurvol();
        }
    });
</script>
