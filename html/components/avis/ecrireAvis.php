
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
    const etoiles = document.querySelectorAll(".star.ecrire");

    etoiles.forEach((etoile) => {
        etoile.addEventListener("mouseenter", () => {
            // Met en surbrillance les étoiles jusqu'à celle survolée
            const note = parseInt(etoile.getAttribute("data-note"));
            surbrillanceEtoiles(etoiles, note);
        });

        etoile.addEventListener("mouseleave", () => {
            // Réinitialise les étoiles lorsqu'on quitte la souris
            reinitialiserEtoiles(etoiles);
        });

        etoile.addEventListener("click", () => {
            // Définit définitivement la note
            const note = parseInt(etoile.getAttribute("data-note"));
            definirNote(etoiles, note);
        });
    });

    function surbrillanceEtoiles(etoiles, nombre) {
        etoiles.forEach((etoile, i) => {
            if (i < nombre) {
                etoile.classList.add("pleine");
                etoile.classList.remove("vide");
            } else {
                etoile.classList.remove("pleine");
                etoile.classList.add("vide");
            }
        });
    }

    function reinitialiserEtoiles(etoiles) {
        etoiles.forEach((etoile) => {
            etoile.classList.remove("pleine");
            etoile.classList.add("vide");
        });
    }

    function definirNote(etoiles, note) {
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