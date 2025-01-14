<!-- Activité -->
<?php
// Initialisation des données à vide
$activite = [
    "duree" => "",
    "agemin" => "",
    "prixminimal" => "",
    "accessibilite" => true,
    "nomAccess" => [],
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

    // Accessibilité
    $stmt = $conn->prepare("SELECT * from pact._offreAccess where idoffre=?");
    $stmt->execute([$idOffre]);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $visite["nomAccess"][] = $row["nomAccess"];
    }
}

?>

<section id="activity">

    <!-- Zone pour l'age minimum pour l'activité -->
    <label class="labelTitre" for="actv_ageMin"> Âge : </label>
    <div>
        <input type="number" id="actv_ageMin" name="actv_ageMin" min="0" placeholder="0" value="<?= $activite["agemin"] ?>"/>
        <label for="actv_ageMin"> ans </label>
    </div>
    
    <!-- Gestion des prestations proposée dans l'activité -->
    <div>
        <label class="labelTitre" for="actv_presta">Préstation(s) : </label>
        <select name="actv_presta" id="actv_presta">
            <option value="defaultPrestaActv">-- Sélectionner une prestation --</option>
            <?php foreach ($prestation as $value) { ?>
                <option value="<?= $value ?>"><?= $value ?></option>
            <?php } ?>
        </select>
        <div>
            <label class="labelSousTitre" for="actv_presta">Préstation inclus : </label>
            <div id="zonePrestationInclusAtcv">
            </div>
        </div>
        <div>
            <label class="labelSousTitre" for="actv_presta">Préstation non-inclus : </label>
            <div id="zonePrestationNonInclusAtcv">
            </div>
        </div>
    </div>

    <!-- Prix -->
    <label class="labelTitre" for="actv_prixMin">Prix minimum</label>
    <div>
        <input type="number" id="actv_prixMin" name="actv_prixMin" min="0" placeholder="0" value="<?= $activite["agemin"]; ?>">
        <label for="actv_prixMin">€</label>
    </div>

    <!-- Accessibilité -->
    <div>
        <label class="labelTitre" for="actv_access">Accessibilité(s) : </label>
        <select name="actv_access" id="actv_access">
            <option value="defaultPrestaActv">-- Sélectionner un handicap --</option>
            <?php foreach ($accessibilite as $value) { ?>
                <option value="<?= $value ?>"><?= $value ?></option>
            <?php } ?>
        </select>
        <div id="actv_Zoneaccess">
        </div>
    </div>

    <div>
    <!-- Gestion de la durée -->
        <label for="actv_hrMin" class="labelTitre">Durée de l'activité : </label>
        <input type="number" style="display : none;" id="actv_min" name="actv_min" placeholder="0" value="<?php echo $activite["duree"] ?>">
        
        <input type="time" id="actv_hrMin" name="actv_hrMin" placeholder="0">
    </div>

</section>


<script>
    // Ajouté des prestations
    // Ajouté des accessibilités

    function ajoutAccessibilite(nomAccess) {
    }

    const access = document.getElementById("actv_access");

    // Vérification des champs
</script>