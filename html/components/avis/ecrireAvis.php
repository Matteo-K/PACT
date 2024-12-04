<section>
    <form action="submitNote.php" method="post">
        <div id="note">
            <!-- Étoiles pour la notation -->
            <?php
                for ($i = 1; $i <= 5; $i++) {
            ?>
                    <div 
                        class="star ecrire vide" 
                        id="star-<?=$i?>" 
                        role="button" 
                        aria-label="Étoile <?= $i ?> sur 5">
                    </div>
            <?php
                }
            ?>
            <input name="note" id="note-value" type="hidden" value="0">
        </div>

        <!-- Champ pour la date -->
        <div>
            <label for="date-avis">Donnez la date de visite :</label>
            <input 
                type="month" 
                id="date-avis" 
                name="date" min="<?= date('Y-m', strtotime('-1 year')) ?>" max="<?= date('Y-m') ?>" required>
        </div>
        <div>
            <label for="enCompagnie">Qui vous accompagnait ?</label>
            <div id="enCompagnie">
                <input type="radio" id="seul" name="compagnie" value="Seul" required>
                <label for="seul">Seul(e)</label>
                
                <input type="radio" id="amis" name="compagnie" value="Amis">
                <label for="amis">Amis</label>
                
                <input type="radio" id="famille" name="compagnie" value="En_Famille">
                <label for="famille">En famille</label>
                
                <input type="radio" id="couple" name="compagnie" value="Couple">
                <label for="autre">Couple</label>
                <input type="radio" id="affaire" name="compagnie" value="Affaire">
                <label for="autre">Affaire</label>
            </div>
        </div>

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

        // Remplit les étoiles jusqu'à `note` pour le survol
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
            noteInput.value = note%5+1; // Met à jour l'input caché
            etoiles.forEach((etoile, i) => {
                if (i < note) {
                    etoile.classList.add("pleine");
                    etoile.classList.remove("vide");
                } else {
                    etoile.classList.remove("pleine");
                    etoile.classList.add("vide");
                }
            });

            console.log("Note sélectionnée :", note);
        }
    });
</script>