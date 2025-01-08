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
        $visite["nomAccess"] = $result["nomAccess"];
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
        <label for="visit_guide">Visite Guidée</label>
        <input type="radio" id="guidee" name="visit_guidee" value="guidee" <?php echo $visite["guide"] ? "checked" : "" ?>>
        <label for="guidee"> Oui</label>
        <input type="radio" id="pasGuidee" name="visit_guidee" value="pasGuidee" <?php echo $visite["guide"] ? "checked" : "" ?>>
        <label for="pasGuidee"> Non </label>

        <label for="visit_duree">Durée :</label> <!-- Label durée -->

        <input type="number" id="visit_duree" name="visit_duree" min="0" placeholder="0" value="<?= $visite["duree"]; ?>"/>
        <label for="visit_duree">h</label>
    </div>

    <!-- Gestion de l'accessibilité (handicap) depuis la BDD -->
    <div>
        <select name="visit_access" id="visit_access">
            <option value="defaultAccessVisit" selected>-- Sélectionner un handicap --</option>
            <?php foreach ($accessibilite as $key => $value) { ?>
                <option value="<?php echo $value ?>"><?php echo $value ?></option>
            <?php } ?>
        </select>
        <!-- Zone d'handicap -->
        <div id="zoneHandicap">
        </div>
    </div>

    <!-- Gestion du prix minimum pour une visite -->
    <label for="visit_minPrix">Prix minimum</label>
    <div>
        <input type="number" id="visit_minPrix" name="visit_minPrix" min="0" placeholder="0" value="<?= $visite["prixminimal"] ?>">
        <label for="visit_minPrix">€</label>
    </div>

    <!-- Partie pour la gestion des langues proposer par la visite -->
    <div>
        <label>Langues proposée(s) :</label>
        <label>Sélectionner les langue(s) proposée(s) par votre visite."</label>

        <!-- Proposition des langues disponible à partir de la BDD -->
        <select name="visit_langue" id="visit_langue">
            <option value="defaultLangueVisit">-- Sélectionner une langue --</option>
            <?php foreach ($langue as $key => $value) { ?>
                <option value="<?php echo $value ?>"><?php echo $value ?></option>
            <?php } ?>
        </select>

        <div id="zoneLangue">
        </div>
</section>
<script>
    // Ajout des accessibilités
    // Ajout des langues

    // Validation des champs
</script>