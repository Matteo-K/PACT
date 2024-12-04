
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

    // Définit la note finale et met à jour les étoiles
    function definirNote(note) {
        noteActuelle = note;
        etoiles.forEach((etoile, i) => {
            if (i < note) {
                etoile.classList.add("pleine");
                etoile.classList.remove("vide");
            } else {
                etoile.classList.remove("pleine");
                etoile.classList.add("vide");
            }
        });

        // Affiche la note sélectionnée dans la console
        console.log("Note sélectionnée :", note);
    }
});

</script>