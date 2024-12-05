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

// Une activité peut avoir plusieurs prestations (Voir avec BDD)
// Si l'offre n'existe pas 
$stmt = $conn->prepare("SELECT * from pact._accessibilite");
$stmt->execute();
while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $accessibilite[] = $result["nomaccess"];
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
// Il reste à initialisé les valeurs dans les input
?>

<section id="activity"> <!-- Section pour le css -->

    <div class="divAge"> <!-- Zone pour l'age minimum pour l'activité -->
        <label class="labAgeAct" name="labAgeAct"> Age: </label>
        <input type="number" id="numberAct" name="ageAct" min="0" placeholder="0" />
        <label class="labAnsAct" name="labAgeAct"> Ans </label>
    </div>
    <!-- Gestion des prestations proposée dans l'activité -->

    <!-- Faire une select depuis la BDD--> <!------------------------------------------------------------------!-->
    <div class="presta">
        <label>Prestation(s)</label>

        <textarea name="textPrestationsAct" id="textePrestation" placeholder="Entrer une prestation "></textarea>

        <input type="button" id="buttonAjoutPresta" name="BtnAjoutPresta" value="Ajouter des presations">


    </div>


    <!-- Gestion des prix de l'activité -->
    <div class="divP">
        <label>Prix minimum</label>

        <div class="divP1">
            <input type="number" id="PrixMinAct" name="PrixMinAct" min="0" placeholder="0">
            <label>€</label>
        </div>
    </div>
    <!-- Partie accéssibilité à modifiier avec la bdd a jour -->
    <div class="access">
        <label id="labAccess">Accessibilité</label> <!-- Label Accessibilité -->


        <div class="acces1">
            <!-- Gestion de l'accessibilité (handicap ) depuis la BDD -->
            <div class="access">
                <select name="nomAccess" id="nomAccess">
                    <option value="SelectionAccess">-- Sélectionner un handicap --</option>
                    <?php foreach ($nomAccess as $key => $value) { ?>
                        <option value="<?php echo $value ?>"><?php echo $value ?></option>
                    <?php } ?>
                </select>
            </div>



            <!-- Gestion de la durée de l'activité -->
            <div class="divD">
                <label class="labDuréeAct" name="labDuréeAct"> Durée: </label>
                <div class="divD1">
                    <input type="number" id="numberAct" name="duréeAct" placeholder="0" />
                    <label class="labHAct" name="labHAct"> H </label>
                </div>

            </div>

</section>