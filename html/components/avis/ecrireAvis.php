
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

            etoile.addEventListener("click", () => {
                // Définit définitivement la note
                definirNote(index + 1);
            });
        });

        function definirNote(note) {
            etoiles.forEach((etoile, i) => {
                if (i < note) {
                    etoile.classList.add("remplie");
                    etoile.classList.remove("vide");
                } else {
                    etoile.classList.remove("remplie");
                    etoile.classList.add("vide");
                }
            });

            console.log("Note sélectionnée :", note);
        }
    });
</script>
