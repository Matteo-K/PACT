<!-- Activité -->
<?php
// Initialisation des données à vide
$activite = [
    "duree" => "0",
    "agemin" => "",
    "prixminimal" => "",
    "accessibilite" => true,
    "nomAccess" => [],
    "prestationInclu" => [],
    "prestationNonInclu" => [],
];
$accessibilite = [];
$prestation = [];
$maxPresta = 10;
$maxAccess = 10;

// accessibilité
$stmt = $conn->prepare("SELECT nomaccess from pact._accessibilite");
$stmt->execute();
while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $accessibilite[] = $result["nomaccess"];
}

// prestation
$stmt = $conn->prepare("SELECT nompresta from pact._prestation");
$stmt->execute();
while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $prestation[] = $result["nompresta"];
}

// Si l'activité était déà existante, on récupère les données
if ($categorie["_activite"]) {
    $stmt = $conn->prepare("SELECT duree, agemin, prixminimal from pact._activite where idoffre=?");
    $stmt->execute([$idOffre]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        $activite["duree"] = $result["duree"];
        $activite["agemin"] = $result["agemin"];
        $activite["prixminimal"] = $result["prixminimal"];
    }

    // Prestation
    $stmt = $conn->prepare("SELECT nompresta FROM pact._offreprestation_non_inclu ni where idoffre = ?");
    $stmt->execute([$idOffre]);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $activite["prestationNonInclu"][] = $row["nompresta"];
    }

    $stmt = $conn->prepare("SELECT nompresta FROM pact._offreprestation_inclu ni where idoffre = ?");
    $stmt->execute([$idOffre]);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $activite["prestationInclu"][] = $row["nompresta"];
    }
    

    // Accessibilité
    $stmt = $conn->prepare("SELECT nomaccess from pact._offreAccess where idoffre=?");
    $stmt->execute([$idOffre]);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $activite["nomAccess"][] = $row["nomaccess"];
    }
}

?>

<section id="activity">

    <!-- Gestion des prestations proposée dans l'activité -->
    <div>
        <label class="labelTitre" for="actv_presta">Préstation(s)</label>
        <div id="actv_prestaGroup">
            <div id="actv_blocPrestaInclu">
                <div id="actv_inputAutoCompletePrestaInclu">
                    <label class="labelSousTitre" for="actv_prestaInclu">Prestations incluses<span id="msgPrestaInclus" class="msgError"></span></label>
                    <input type="text" id="actv_inputPrestaInclus" 
                    name="actv_inputPrestaInclus" 
                    placeholder="Entrez & selectionnez une prestation correspondant à votre activité">
                    
                    <ul id="actv_autocompletionInclus"></ul>
                </div>
                <label class="labelSousTitre">
                    Vous pouvez entrer jusqu'à <?= $maxPresta ?> prestations inclus
                </label>
                <ul id="atcv_zonePrestationInclus">
                </ul>
            </div>
            <div id="actv_blocPrestaNonInclu">
                <div id="actv_inputAutoCompletePrestaNonInclu">
                    <label class="labelSousTitre" for="actv_prestaNonInclu">Prestations non-incluses<span id="msgPrestaNonInclus" class="msgError"></span></label>
                    <input type="text" id="actv_inputPrestaNonInclus" 
                    name="actv_inputPrestaNonInclus" 
                    placeholder="Entrez & selectionnez une prestation correspondant à votre activité">
                    
                    <ul id="actv_autocompletionNonInclus"></ul>
                </div>
                <label class="labelSousTitre">
                    Vous pouvez entrer jusqu'à <?= $maxPresta ?> prestations non inclus
                </label>
                <ul id="atcv_zonePrestationNonInclus">
                </ul>
            </div>
        </div>
    </div>
        
    <div>
        <div>
            <div>
                <!-- Zone pour l'age minimum pour l'activité -->
                <label class="labelTitre" for="actv_ageMin">Age*<span id="actv_msgAge" class="msgError"></span></label>
                <div>
                    <input type="number" id="actv_ageMin" name="actv_ageMin" min="0" placeholder="0" value="<?= $activite["agemin"] ?>"/>
                    <label for="actv_ageMin"> ans </label>
                </div>
                
                <!-- Prix -->
                <label class="labelTitre" for="actv_prixMin">Prix minimum*<span id="actv_msgPrix" class="msgError"></span></label>
                <div>
                    <input type="number" id="actv_prixMin" name="actv_prixMin" min="0" placeholder="0" value="<?= $activite["agemin"]; ?>">
                    <label for="actv_prixMin">€</label>
                </div>

                <!-- Gestion de la durée -->
                <label for="actv_hrMin" class="labelTitre">Durée de l'activité*<span id="actv_msgDuree" class="msgError"></span></label>
                <div>
                    <input type="number" style="display : none;" id="actv_min" name="actv_min" placeholder="0" value="<?php echo $activite["duree"] ?>">
                    <input type="time" id="actv_hrMin" name="actv_hrMin" placeholder="0">
                </div>
            </div>
            <!-- Accessibilité -->
            <div id="actv_blocAccess">
                <div id="actv_inputAutoCompleteAccess">
                    <label class="labelTitre" for="actv_inputAccess">Accessibilités<span id="actv_msgAccess" class="msgError"></span></label>
                    <input type="text" id="actv_inputAccess" 
                    name="actv_inputAccess"
                    placeholder="Entrez & selectionnez une accessibilité correspondant à votre activité">
                    
                    <ul id="actv_autocompletionAccess"></ul>
                </div>
                <label class="labelSousTitre">
                    Vous pouvez entrer jusqu'à <?= $maxAccess ?> accessibilités
                </label>
                <ul id="atcv_zonePrestationAccess">
                </ul>
            </div>
        </div>
    </div>

</section>


<script>
    // ### Durée
    document.addEventListener("DOMContentLoaded", function () {
        const actv_minutesInput = document.getElementById("actv_min");
        const actv_hoursInput = document.getElementById("actv_hrMin");
        
        actv_minutesInput.addEventListener("change", () => minutesToHours(actv_minutesInput, actv_hoursInput));
        actv_hoursInput.addEventListener("change", () => hoursToMinutes(actv_minutesInput, actv_hoursInput));

        minutesToHours(actv_minutesInput, actv_hoursInput);
    });

    // ### Prestation
    const listePrestation = <?= json_encode($prestation); ?>;
    const prestationInclu = <?= json_encode($activite["prestationInclu"]); ?>;
    const prestationNonInclu = <?= json_encode($activite["prestationNonInclu"]); ?>;

    function checkAddPrestation(value, index, msgErreur, nomListe) {
        const indexList = nomListe === "inclu" ? index + 1 : index - 1;
        const res = !listElements[indexList].includes(value);

        if (!res) {
            if (nomListe == "inclu") {
                msgErreur.textContent = value + " est déjà présent dans les prestations non-incluses";
            } else {
                msgErreur.textContent = value + " est déjà présent dans les prestations incluses";
            }
        }

        return res;
    }

    const actv_inputInclu = document.getElementById("actv_inputPrestaInclus");
    const actv_inputNonInclu = document.getElementById("actv_inputPrestaNonInclus");

    const actv_zoneInclu = document.getElementById("atcv_zonePrestationInclus");
    const actv_zoneNonInclu = document.getElementById("atcv_zonePrestationNonInclus");

    const actv_msgInclu = document.getElementById("msgPrestaInclus");
    const actv_msgNonInclu = document.getElementById("msgPrestaNonInclus");
    const actv_maxPrestation = <?= $maxPresta ?>;

    const classLi = ["elementCategorie"];

    const indexPrestaInclu = createAutoCompletion(
        actv_inputInclu,
        "actv_autocompletionInclus",
        actv_msgInclu,
        listePrestation,
        ajoutElement,
        actv_inputInclu, //-- paramètres de la fonction ajoutElement
        actv_zoneInclu,
        actv_msgInclu,
        'prestationInclu[]',
        actv_maxPrestation,
        "li",
        classLi,
        checkAddPrestation,
        "inclu"
    );

    const indexPrestaNonInclu = createAutoCompletion(
        actv_inputNonInclu,
        "actv_autocompletionNonInclus",
        actv_msgNonInclu,
        listePrestation,
        ajoutElement,
        actv_inputNonInclu, //-- paramètres de la fonction ajoutElement
        actv_zoneNonInclu,
        actv_msgNonInclu,
        'prestationNonInclu[]',
        actv_maxPrestation,
        "li",
        classLi,
        checkAddPrestation,
        "nonInclu"
    );

    /* Initialisation de prestation inclus */
    prestationInclu.forEach(valeur => {
        ajoutElement(valeur,
            indexPrestaInclu,
            actv_inputInclu, //-- paramètres de la fonction ajoutElement
            actv_zoneInclu,
            actv_msgInclu,
            'prestationInclu[]',
            actv_maxPrestation,
            "li",
            classLi,
            checkAddPrestation,
            "inclu"
        );
    });

    /* Initialisation de prestation inclus */
    prestationNonInclu.forEach(valeur => {
            ajoutElement(valeur,
            indexPrestaNonInclu,
            actv_inputNonInclu,
            actv_zoneNonInclu,
            actv_msgNonInclu,
            'prestationNonInclu[]',
            actv_maxPrestation,
            "li",
            classLi,
            checkAddPrestation,
            "nonInclu"
        );
    });

    // ### Accessibilités

    const actv_accessGeneral = <?= json_encode($accessibilite); ?>;
    const actv_access = <?= json_encode($activite["nomAccess"]); ?>;

    const actv_inputAccess = document.getElementById("actv_inputAccess");
    const actv_zone = document.getElementById("atcv_zonePrestationAccess");
    const actv_msgAccess = document.getElementById("actv_msgAccess");

    const actv_maxAccess = <?= $maxAccess ?>;

    const actv_indexAccess = createAutoCompletion(
        actv_inputAccess,
        "actv_autocompletionAccess",
        actv_msgAccess,
        actv_accessGeneral,
        ajoutElement,
        actv_inputAccess, //-- paramètres de la fonction ajoutElement
        actv_zone,
        actv_msgAccess,
        'actv_access[]',
        actv_maxAccess,
        "li",
        classLi
    );

    /* Initialisation de prestation inclus */
    actv_access.forEach(valeur => {
        ajoutElement(valeur,
            actv_indexAccess,
            actv_inputAccess, //-- paramètres de la fonction ajoutElement
            actv_zone,
            actv_msgAccess,
            'actv_access[]',
            actv_maxAccess,
            "li",
            classLi
        );
    });

    // Vérification des champs

    const actv_inputAge = document.getElementById("actv_ageMin");
    const actv_inputPrix = document.getElementById("actv_prixMin");
    const actv_inputDuree = document.getElementById("actv_hrMin");

    const actv_msgAge = document.getElementById("actv_msgAge");
    const actv_msgPrix = document.getElementById("actv_msgPrix");
    const actv_msgDuree = document.getElementById("actv_msgDuree");

    actv_inputAge.addEventListener("focus", () => {
        actv_msgAge.textContent = "";
        actv_inputAge.classList.remove("inputErreur");
    });
    actv_inputPrix.addEventListener("focus", () => {
        actv_msgPrix.textContent = "";
        actv_inputPrix.classList.remove("inputErreur");
    });
    actv_inputDuree.addEventListener("focus", () => {
        actv_msgDuree.textContent = "";
        actv_inputDuree.classList.remove("inputErreur");
    });

    actv_inputAge.addEventListener("blur", checkActvAgeMin);
    actv_inputPrix.addEventListener("blur", checkActvPrixMin);
    actv_inputDuree.addEventListener("blur", checkActvDuree);

    function checkActivity() {
        let age = checkActvAgeMin();
        let prix = checkActvPrixMin();
        let duree = checkActvDuree();
        return age && prix && duree;
    }

    function checkActvAgeMin() {
        let res = true;
        const age = actv_inputAge.value.trim();
        const agePattern = /^\d+$/;

        if (!agePattern.test(age) && age === "") {
            actv_msgAge.textContent = "Doit contenir des chiffres positifs";
            actv_inputAge.classList.add("inputErreur");
            res = false;
        }
        return res;
    }

    function checkActvPrixMin() {
        let res = true;
        const prix = actv_inputPrix.value.trim();
        const prixPattern = /^\d+$/;

        if (!prixPattern.test(prix) && prix === "") {
            actv_msgPrix.textContent = "Doit contenir des chiffres positifs";
            actv_inputPrix.classList.add("inputErreur");
            res = false;
        }

        return res;
    }

    function checkActvDuree() {
        let res = true;
        const duree = actv_inputDuree.value.trim();
        const timePattern = /^([01]?[0-9]|2[0-3]):([0-5]?[0-9])$/;

        if (!timePattern.test(duree) && duree === "") {
            actv_msgDuree.textContent = "Format HH:MM";
            actv_inputDuree.classList.add("inputErreur");
            res = false;
        }
        return res;
    }
</script>