
<div>
    <?php
        for($i = 1; $i <= 5; $i++){
    ?>
            <div class="star ecrire vide" id="star-<?=$i?>"></div>
    <?php
        }
    ?>
</div>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const etoiles = document.querySelectorAll(".star");

        etoiles.forEach((etoile, index) => {
            // Survol : afficher temporairement les étoiles remplies
            etoile.addEventListener("mouseover", () => {
                survolerEtoiles(index + 1);
            });

            // Quitter le survol : rétablir l'état initial ou la sélection
            etoile.addEventListener("mouseout", () => {
                reinitialiserSurvol();
            });

            // Clic : définir la note de manière permanente
            etoile.addEventListener("click", () => {
                definirNote(index + 1);
            });
        });

        let noteActuelle = 0;

        // Prévisualiser les étoiles remplies lors du survol
        function survolerEtoiles(note) {
            etoiles.forEach((etoile, i) => {
                if (i < note) {
                    etoile.classList.add("pleine");
                } else {
                    etoile.classList.remove("pleine");
                }
            });
        }

        // Réinitialiser l'état des étoiles après le survol
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

        // Définir la note de manière permanente
        function definirNote(note) {
            noteActuelle = note; // Enregistre la note sélectionnée
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