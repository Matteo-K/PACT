<!-- Visite -->
<?php
$langue = [];
$accessibilite = [];
$maxLangue = 10;
$maxAcces = 10;
$visite = [
    "guide" => true,
    "duree" => "0",
    "prixminimal" => "",
    "accessibilite" => true,
    "nomAccess" => [],
    "langue" => []
];

$stmt = $conn->prepare("SELECT * from pact._langue");
$stmt->execute();
while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $langue[] = $result["langue"];
}

$stmt = $conn->prepare("SELECT * from pact._accessibilite");
$stmt->execute();
while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $accessibilite[] = $result["nomaccess"];
}

// Si la visite était déà existante, on récupère les données
if ($categorie["_visite"]) {
    $stmt = $conn->prepare("SELECT * from pact._visite where idoffre=?");
    $stmt->execute([$idOffre]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $visite["guide"] = $result["guide"];
        $visite["duree"] = $result["duree"];
        $visite["prixminimal"] = $result["prixminimal"];
        $visite["accessibilite"] = $result["accessibilite"];
    }
    // Langue
    $stmt = $conn->prepare("SELECT langue from pact._visite_langue where idoffre=?");
    $stmt->execute([$idOffre]);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $visite["langue"][] = $row["langue"];
    }

    // Accessibilité
    $stmt = $conn->prepare("SELECT * from pact._offreAccess where idoffre=?");
    $stmt->execute([$idOffre]);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $visite["nomAccess"][] = $row["nomaccess"];
    }
}

?>
<!-- Partie sur les informations de la visite -->
<section id="visit">
    <!-- Visite guidée ou non  -->
    <div>
        <!-- Par défaut la visite est guidée-->
        <label class="labelTitre" for="visit_guide">Visite Guidée*</label>
        <div>
            <label for="guidee" class="labelSousTitre"> 
                <input type="radio" id="guidee" name="visit_guidee" value="guidee" <?php echo $visite["guide"] ? "checked" : "" ?>>
                <span class="checkmark"></span>
                Oui
            </label>
            <label for="pasGuidee" class="labelSousTitre"> 
                <input type="radio" id="pasGuidee" name="visit_guidee" value="pasGuidee" <?php echo $visite["guide"] ? "checked" : "" ?>>
                <span class="checkmark"></span>
                Non
            </label>
        </div>
        <label for="visit_hrMin" class="labelTitre">Durée de la visite*<span id="visit_msgDuree" class="msgError"></span></label>
        <div>
            <input type="hidden" id="visit_min" name="visit_min" placeholder="0" value="<?php echo $visite["duree"] ?>">
            <input type="time" id="visit_hrMin" name="visit_hrMin" placeholder="0">
        </div>
        <!-- Gestion du prix minimum pour une visite -->
        <label class="labelTitre" for="visit_minPrix">Prix minimum*<span id="visit_msgPrix" class="msgError"></span></label>
        <div>
            <input type="number" id="visit_minPrix" name="visit_minPrix" min="0" placeholder="0" value="<?= $visite["prixminimal"] ?>">
            <label for="visit_minPrix" class="labelSousTitre">€</label>
        </div>
    </div>

    <div>
        <!-- Accessibilité -->
        <div id="visit_blocAccess">
            <div id="visit_inputAutoCompleteAccess">
                <label class="labelSousTitre" for="visit_inputAccess">Accessibilités<span id="visit_msgAccess" class="msgError"></span></label>
                <input type="text" id="visit_inputAccess" 
                name="*visit_inputAccess"
                placeholder="Entrez & selectionnez une accessibilité">

                <ul id="visit_autocompletionAccess"></ul>
            </div>
            <label class="labelSousTitre">
                Vous pouvez entrer jusqu'à <?= $maxAcces ?> accessibilités
            </label>
            <ul id="visit_zonePrestationAccess">
            </ul>
        </div>

        <!-- Partie pour la gestion des langues proposer par la visite -->
        <div id="visit_blocLangue">
            <div id="visit_inputAutoCompleteLangue">
                <label class="labelTitre" for="visit_langue">Langues proposées<span id="msgLangue" class="msgError"></span></label>
                <input type="text" id="visit_langue" 
                name="visit_langue" 
                placeholder="Entrez & sélectionnez une langue">

                <ul id="visit_autocompletionLangue"></ul>
            </div>
            <label class="labelSousTitre">
                Vous pouvez entrer jusqu'à <?= $maxLangue ?> langues
            </label>
            <ul id="visit_zoneLangue">
            </ul>
        </div>
    </div>
</section>
<script>
    // Durée
    document.addEventListener("DOMContentLoaded", function () {
        const visit_minutesInput = document.getElementById("visit_min");
        const visit_hoursInput = document.getElementById("visit_hrMin");
        
        visit_minutesInput.addEventListener("change", () => minutesToHours(visit_minutesInput, visit_hoursInput));
        visit_hoursInput.addEventListener("change", () => hoursToMinutes(visit_minutesInput, visit_hoursInput));

        minutesToHours(visit_minutesInput, visit_hoursInput);
    });

    // Ajout des accessibilités
    // ### Accessibilités
    const visit_accessGeneral = <?= json_encode($accessibilite); ?>;
    const visit_access = <?= json_encode($visite["nomAccess"]); ?>;

    const visit_inputAccess = document.getElementById("visit_inputAccess");
    const visit_zoneAccess = document.getElementById("visit_zonePrestationAccess");
    const visit_msgAccess = document.getElementById("visit_msgAccess");

    const visit_maxAccess = <?= $maxAcces ?>;
    const classLiVisit = ["elementCategorie"];

    const visit_indexAccess = createAutoCompletion(
        visit_inputAccess,
        "visit_autocompletionAccess",
        visit_msgAccess,
        visit_accessGeneral,
        ajoutElement,
        visit_inputAccess, //-- paramètres de la fonction ajoutElement
        visit_zoneAccess,
        visit_msgAccess,
        'visit_access[]',
        visit_maxAccess,
        "li",
        classLiVisit
    );

    /* Initialisation de prestation inclus */
    visit_access.forEach(valeur => {
        ajoutElement(valeur,
            visit_indexAccess,
            visit_inputAccess, //-- paramètres de la fonction ajoutElement
            visit_zoneAccess,
            visit_msgAccess,
            'visit_access[]',
            visit_maxAccess,
            "li",
            classLiVisit
        );
    });
    
    // Ajout des langues
    const visit_langueGeneral = <?= json_encode($accessibilite); ?>;
    const visit_langue = <?= json_encode($visite["langue"]); ?>;

    const visit_inputLangue = document.getElementById("visit_langue");
    const visit_zoneLangue = document.getElementById("visit_zoneLangue");
    const visit_msgLangue = document.getElementById("msgLangue");

    const visit_maxLangue = <?= $maxLangue ?>;

    const visit_indexLangue = createAutoCompletion(
        visit_inputLangue,
        "visit_autocompletionLangue",
        visit_msgLangue,
        visit_langueGeneral,
        ajoutElement,
        visit_inputLangue, //-- paramètres de la fonction ajoutElement
        visit_zoneLangue,
        visit_msgLangue,
        'visit_langue[]',
        visit_maxLangue,
        "li",
        classLiVisit
    );

    /* Initialisation de prestation inclus */
    visit_langue.forEach(valeur => {
        ajoutElement(valeur,
            visit_indexLangue,
            visit_inputLangue, //-- paramètres de la fonction ajoutElement
            visit_zoneLangue,
            visit_msgLangue,
            'visit_langue[]',
            visit_maxLangue,
            "li",
            classLiVisit
        );
    });

    // Validation des champs

    const visit_inputPrix = document.getElementById("visit_minPrix");
    const visit_inputDuree = document.getElementById("visit_hrMin");

    const visit_msgMin = document.getElementById("visit_msgPrix");
    const visit_msgDuree = document.getElementById("visit_msgDuree");

    function checkVisit() {
        let min = checkVisitPrixMin();
        let duree = checkVisitDuree();
        return min && duree;
    }

    function checkVisitPrixMin() {
        let res = true;
        const prix = visit_inputPrix.value.trim();
        const prixPattern = /^\d+$/;

        if (!prixPattern.test(prix) && prix === "") {
            actv_msgPrix.textContent = "Doit contenir des chiffres positifs";
            visit_inputPrix.classList.add("inputErreur");
            res = false;
        }

        return res;
    }

    visit_inputPrix.addEventListener("blur", () => checkVisitPrixMin());
    visit_inputPrix.addEventListener("focus", () => {
        visit_msgMin.textContent = "";
        visit_inputPrix.classList.remove("inputErreur");
    });

    function checkVisitDuree() {
        let res = true;
        const duree = visit_inputDuree.value.trim();
        const timePattern = /^([01]?[0-9]|2[0-3]):([0-5]?[0-9])$/;

        if (!timePattern.test(duree) && duree === "") {
            visit_msgDuree.textContent = "Format HH:MM";
            visit_inputDuree.classList.add("inputErreur");
            res = false;
        }
        return res;
    }

    visit_inputDuree.addEventListener("blur", () => checkVisitDuree());
    visit_inputDuree.addEventListener("focus", () => {
        visit_msgDuree.textContent = "";
        visit_inputDuree.classList.remove("inputErreur");
    });
</script>