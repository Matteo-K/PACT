
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
        
        etoiles.forEach((etoile, index) => {
            etoile.addEventListener("mouseenter", () => {
                // Met en surbrillance les étoiles jusqu'à celle survolée
                surbrillanceEtoiles(index + 1);
            });

            etoile.addEventListener("mouseleave", () => {
                // Réinitialise les étoiles lorsqu'on quitte la souris
                reinitialiserEtoiles();
            });

            etoile.addEventListener("click", () => {
                // Définit définitivement la note
                definirNote(index + 1);
            });
        });

        function surbrillanceEtoiles(nombre) {
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

        function reinitialiserEtoiles() {
            etoiles.forEach((etoile) => {
                etoile.classList.remove("pleine");
                etoile.classList.add("vide");
            });
        }

        function definirNote(note) {
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