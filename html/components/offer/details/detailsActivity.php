<!-- Activité -->
<?php
// Initialisation des données à vide
$activite = [
    "duree" => "",
    "agemin" => "",
    "prixminimal" => "",
    "accessibilite" => true,
    "nomAccess" => [],
    "prestationInclu" => [],
    "prestationNonInclu" => [],
];
$accessibilite = [];
$prestation = [];

// accessibilité
$stmt = $conn->prepare("SELECT * from pact._accessibilite");
$stmt->execute();
while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $accessibilite[] = $result["nomaccess"];
}

// prestation
$stmt = $conn->prepare("SELECT * from pact._prestation");
$stmt->execute();
while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $prestation[] = $result["nompresta"];
}

// Si l'activité était déà existante, on récupère les données
if ($categorie["_activite"]) {
    $stmt = $conn->prepare("SELECT * from pact._activite where idoffre=?");
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
    $stmt = $conn->prepare("SELECT * from pact._offreAccess where idoffre=?");
    $stmt->execute([$idOffre]);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $visite["nomAccess"][] = $row["nomAccess"];
    }
}

?>

<section id="activity">

    <!-- Gestion des prestations proposée dans l'activité -->
    <div>
        <label class="labelTitre" for="actv_presta">Préstation(s)</label>
        <select name="actv_presta" id="actv_presta">
            <option value="defaultPrestaActv">-- Sélectionner une prestation --</option>
            <?php foreach ($prestation as $value) { ?>
                <option value="<?= $value ?>"><?= $value ?></option>
            <?php } ?>
        </select>
        <div>
            <div id="actv_inputAutoCompletePrestaInclu">
                <label class="labelSousTitre" for="actv_prestaInclu">Prestations incluses<span id="msgPrestaInclus" class="msgError"></span></label>
                <input type="text" id="actv_inputPrestaInclus" 
                name="actv_inputPrestaInclus" 
                placeholder="Entrez & selectionnez une prestation correspondant à votre activité">

                <ul id="actv_autocompletionInclus"></ul>
            </div>
            <ul id="atcv_zonePrestationInclusAtcv">
            </ul>
        </div>
        <div>
            <div id="actv_inputAutoCompletePrestaNonInclu">
                <label class="labelSousTitre" for="actv_prestaNonInclu">Prestations non-incluses<span id="msgPrestaNonInclus" class="msgError"></span></label>
                <input type="text" id="actv_inputPrestaNonInclus" 
                name="actv_inputPrestaNonInclus" 
                placeholder="Entrez & selectionnez une prestation correspondant à votre activité">

                <ul id="actv_autocompletionNonInclus"></ul>
            </div>
            <ul id="atcv_zonePrestationNonInclusAtcv">
            </ul>
        </div>
    </div>

    <div>
        <div>
            <!-- Zone pour l'age minimum pour l'activité -->
            <label class="labelTitre" for="actv_ageMin">Age*</label>
            <div>
                <input type="number" id="actv_ageMin" name="actv_ageMin" min="0" placeholder="0" value="<?= $activite["agemin"] ?>"/>
                <label for="actv_ageMin"> ans </label>
            </div>
        
            <!-- Prix -->
            <label class="labelTitre" for="actv_prixMin">Prix minimum*</label>
            <div>
                <input type="number" id="actv_prixMin" name="actv_prixMin" min="0" placeholder="0" value="<?= $activite["agemin"]; ?>">
                <label for="actv_prixMin">€</label>
            </div>

            <!-- Gestion de la durée -->
            <label for="actv_hrMin" class="labelTitre">Durée de l'activité*</label>
            <div>
                <input type="number" style="display : none;" id="actv_min" name="actv_min" placeholder="0" value="<?php echo $activite["duree"] ?>">
                <input type="time" id="actv_hrMin" name="actv_hrMin" placeholder="0">
            </div>
        </div>

        <!-- Accessibilité -->
        <div>
            <label class="labelTitre" for="actv_access">Accessibilités</label>
            <select name="actv_access" id="actv_access">
                <option value="defaultPrestaActv">-- Sélectionner un handicap --</option>
                <?php foreach ($accessibilite as $value) { ?>
                    <option value="<?= $value ?>"><?= $value ?></option>
                <?php } ?>
            </select>
            <div id="actv_Zoneaccess">
            </div>
        </div>
    </div>

</section>


<script>
    // Durée
    document.addEventListener("DOMContentLoaded", function () {
        const minutesInput = document.getElementById("actv_min");
        const hoursInput = document.getElementById("actv_hrMin");
        
        minutesInput.addEventListener("change", () => minutesToHours(minutesInput, hoursInput));
        hoursInput.addEventListener("change", () => hoursToMinutes(minutesInput, hoursInput));

        minutesToHours(minutesInput, hoursInput);
    });

    // Prestation
    const listePrestation = <?= json_encode($prestation); ?>;
    const prestationInclu = <?= json_encode($activite["prestationInclu"]); ?>;
    const prestationNonInclu = <?= json_encode($activite["prestationNonInclu"]); ?>;

    function checkAddPrestation(value, index, msgErreur, nomListe) {
        const indexList = nomListe === "inclu" ? index + 1 : index - 1;
        const res = listElements[indexList].includes(value);

        console.table([listElements[index], listElements[indexList], value]);

        if (!res) {
            if (nomListe == "inclu") {
                msgErreur.textContent = value + " est déjà présent dans les prestations non-incluses";
            } else {
                msgErreur.textContent = value + " est déjà présent dans les prestations incluses";
            }
        }

        return res;
    }

    const inputInclu = document.getElementById("actv_inputPrestaInclus");
    const inputNonInclu = document.getElementById("actv_inputPrestaNonInclus");

    const zoneInclu = document.getElementById("atcv_zonePrestationInclusAtcv");
    const zoneNonInclu = document.getElementById("atcv_zonePrestationNonInclusAtcv");

    const msgInclu = document.getElementById("msgPrestaInclus");
    const msgNonInclu = document.getElementById("msgPrestaNonInclus");
    const maxPrestation = 10;

    const indexPrestaInclu = createAutoCompletion(
        inputInclu,
        "actv_autocompletionInclus",
        msgInclu,
        listePrestation,
        ajoutElement,
        inputInclu, //-- paramètres de la fonction ajoutElement
        zoneInclu,
        msgInclu,
        'prestationInclu[]',
        maxPrestation,
        "li",
        [],
        checkAddPrestation,
        "inclu"
    );

    const indexPrestaNonInclu = createAutoCompletion(
        inputNonInclu,
        "actv_autocompletionNonInclus",
        msgNonInclu,
        listePrestation,
        ajoutElement,
        inputNonInclu, //-- paramètres de la fonction ajoutElement
        zoneNonInclu,
        msgNonInclu,
        'prestationNonInclu[]',
        maxPrestation,
        "li",
        [],
        checkAddPrestation,
        "nonInclu"
    );

    /* Initialisation de prestation inclus */
    prestationInclu.forEach(valeur => {
        ajoutElement(valeur,
            indexPrestaInclu,
            inputNonInclu, //-- paramètres de la fonction ajoutElement
            zoneNonInclu,
            msgNonInclu,
            'prestationNonInclu[]',
            maxPrestation,
            "li",
            [],
            checkAddPrestation,
            "inclu"
        );
    });

    /* Initialisation de prestation inclus */
    prestationNonInclu.forEach(valeur => {
            ajoutElement(valeur,
            indexPrestaNonInclu,
            inputNonInclu,
            zoneNonInclu,
            msgNonInclu,
            'prestationNonInclu[]',
            maxPrestation,
            "li",
            [],
            checkAddPrestation,
            "nonInclu"
        );
    });

    // Ajouté des accessibilités

    const access = document.getElementById("actv_access");

    // Vérification des champs

    function checkActivity() {
        return true;
    }

    function checkActvAgeMin() {
        
    }

    function checkActvPrixMin() {
        
    }

    function checkActvDuree() {
        
    }
</script>