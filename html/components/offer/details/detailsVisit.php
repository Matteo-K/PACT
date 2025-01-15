<!-- Visite -->
<?php
$langue = [];
$accessibilite = [];
$visite = [
    "guide" => true,
    "duree" => "",
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
        $visite["nomAccess"][] = $row["nomAccess"];
    }
}

?>
<!-- Partie sur les informations de la visite -->
<section id="visit"> <!-- donne un id a la section pour l'identifier dans le css -->
    <!-- Visite guidée ou non  -->
    <div>
        <!-- Par défaut la visite est guidée-->
        <label class="labelTitre" for="visit_guide">Visite Guidée*</label>
        <div>
            <input type="radio" id="guidee" name="visit_guidee" value="guidee" <?php echo $visite["guide"] ? "checked" : "" ?>>
            <span class="checkmark"></span>
            <label for="guidee"> Oui</label>
            <input type="radio" id="pasGuidee" name="visit_guidee" value="pasGuidee" <?php echo $visite["guide"] ? "checked" : "" ?>>
            <span class="checkmark"></span>
            <label for="pasGuidee"> Non </label>
        </div>
        <label for="visit_hrMin" class="labelTitre">Durée de la visite*</label>
        <div>
            <input type="hidden" id="visit_min" name="visit_min" placeholder="0" value="<?php echo $visite["duree"] ?>">
            <input type="time" id="visit_hrMin" name="visit_hrMin" placeholder="0">
        </div>
        <!-- Gestion du prix minimum pour une visite -->
        <label class="labelTitre" for="visit_minPrix">Prix minimum*</label>
        <div>
            <input type="number" id="visit_minPrix" name="visit_minPrix" min="0" placeholder="0" value="<?= $visite["prixminimal"] ?>">
            <label for="visit_minPrix">€</label>
        </div>
    </div>

    <div>
        <!-- Accessibilité -->
        <div>
            <div id="visit_inputAutoCompleteAccess">
                <label class="labelSousTitre" for="visit_inputAccess">Accessibilités<span id="visit_msgAccess" class="msgError"></span></label>
                <input type="text" id="visit_inputAccess" 
                name="*visit_inputAccess"
                placeholder="Entrez & selectionnez une accessibilité correspondant à votre activité">

                <ul id="visit_autocompletionAccess"></ul>
            </div>
            <ul id="visit_zonePrestationAccess">
            </ul>
        </div>

        <!-- Partie pour la gestion des langues proposer par la visite -->
        <div>
            <div id="visit_inputLangue">
                <label class="labelTitre" for="visit_langue">Langues proposées<span id="msgLangue" class="msgError"></span></label>
                <input type="text" id="visit_langue" 
                name="visit_langue" 
                placeholder="Entrez & sélectionnez les langue(s) proposée(s) par votre visite">

                <ul id="visit_autocompletionLangue"></ul>
            </div>

            <ul id="visit_zoneLangue">
            </ul>
        </div>
    </div>
</section>
<script>
    // Durée
    document.addEventListener("DOMContentLoaded", function () {
        const minutesInput = document.getElementById("show_min");
        const hoursInput = document.getElementById("show_hrMin");
        
        minutesInput.addEventListener("change", () => minutesToHours(minutesInput, hoursInput));
        hoursInput.addEventListener("change", () => hoursToMinutes(minutesInput, hoursInput));

        minutesToHours(minutesInput, hoursInput);
    });

    // Ajout des accessibilités
    // Ajout des langues

    // Validation des champs
</script>