<!-- Spectacle -->
<?php
// Initialisation des données à vide
$spectacle = [
    "duree" => "0",
    "nbplace" => "",
    "prixminimal" => ""
];

// Si le spectacle était déjà existant, on récupère les données
if ($categorie["_spectacle"]) {
    $stmt = $conn->prepare("SELECT * from pact._spectacle where idoffre=?");
    $stmt->execute([$idOffre]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $spectacle["duree"] = $result["duree"];
        $spectacle["nbplace"] = $result["nbplace"];
        $spectacle["prixminimal"] = $result["prixminimal"];
    }
}

?>
<section id="show"> 
    <!-- Gestion du nombre de place pour le parc d'attraction -->
    <div id="show_nbPlacePrixMin">
        <!-- Gestion nombre d'attraction -->
        <div>
            <label class="labelTitre" for="show_nbPlace">Nombre de places*<span id="show_msgNbPlace" class="msgError"></span></label> <!-- Label nombre de place -->
            <div>
                <input type="number" id="show_nbPlace" name="show_nbPlace" min="0" placeholder="0" value="<?php echo $spectacle["nbplace"] ?>">
                <!-- Pour le nombre de place -->
                <label for="show_nbPlace">places</label>
            </div>
        </div>
        <!-- Gestion du prix -->
         <div>
            <label class="labelTitre" for="show_prixMin">Prix minimum*<span id="show_msgPrix" class="msgError"></span></label>
            <div>
                <input type="number" id="show_prixMin" name="show_prixMin" min="0" placeholder="0" value="<?php echo $spectacle["prixminimal"] ?>">
                <!-- Pour entrer un prix minimum -->
                <label for="show_prixMin">€</label>
            </div>
        </div>
        <!-- Gestion de la durée -->
        <div>
            <label for="show_hrMin" class="labelTitre">Durée du Spectacle*<span id="show_msgDuree" class="msgError"></span></label>
            <div>
                <input type="hidden" id="show_min" name="show_min" placeholder="0" value="<?php echo $spectacle["duree"] ?>">
                <input type="time" id="show_hrMin" name="show_hrMin" placeholder="0">
            </div>
        </div>
    </div>


</section>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const show_minutesInput = document.getElementById("show_min");
        const show_hoursInput = document.getElementById("show_hrMin");
        
        show_minutesInput.addEventListener("change", () => minutesToHours(show_minutesInput, show_hoursInput));
        show_hoursInput.addEventListener("change", () => hoursToMinutes(show_minutesInput, show_hoursInput));

        minutesToHours(show_minutesInput, show_hoursInput);
    });

    const show_inputNbPlace = document.getElementById("show_nbPlace");
    const show_inputPrix = document.getElementById("show_prixMin");
    const show_inputDuree = document.getElementById("show_hrMin");

    const show_msgNbPlace = document.getElementById("show_msgNbPlace");
    const show_msgPrix = document.getElementById("show_msgPrix");
    const show_msgDuree = document.getElementById("show_msgDuree");

    show_inputNbPlace.addEventListener("focus", () => {
        show_msgNbPlace.textContent = "";
    });
    show_inputPrix.addEventListener("focus", () => {
        show_msgPrix.textContent = "";
    });
    show_inputDuree.addEventListener("focus", () => {
        show_msgDuree.textContent = "";
    });

    show_inputNbPlace.addEventListener("blur", checkShowNbPlace);
    show_inputPrix.addEventListener("blur", checkShowPrixMin);
    show_inputDuree.addEventListener("blur", checkShowDuree);

    function checkSpectacle() {
        let nbPlace = checkShowNbPlace();
        let prix = checkShowPrixMin();
        let duree = checkShowDuree();
        return nbPlace && prix && duree;
    }

    function checkShowNbPlace() {
        let res = true;
        const age = show_inputNbPlace.value.trim();
        const agePattern = /^\d+$/;

        if (!agePattern.test(age) || age === "") {
            show_msgNbPlace.textContent = "Doit contenir des chiffres positifs";
            show_inputNbPlace.classList.add("inputErreur");
            res = false;
        }
        return res;
    }

    function checkShowPrixMin() {
        let res = true;
        const prix = show_inputPrix.value.trim();
        const prixPattern = /^\d+$/;

        if (!prixPattern.test(prix) || prix === "") {
            show_msgPrix.textContent = "Doit contenir des chiffres positifs";
            show_inputPrix.classList.add("inputErreur");
            res = false;
        }

        return res;
    }

    function checkShowDuree() {
        let res = true;
        const duree = show_inputDuree.value.trim();
        const timePattern = /^([01]?[0-9]|2[0-3]):([0-5]?[0-9])$/;

        if (!timePattern.test(duree) || duree === "") {
            show_msgDuree.textContent = "Format HH:MM";
            show_inputDuree.classList.add("inputErreur");
            res = false;
        }
        return res;
    }
</script>