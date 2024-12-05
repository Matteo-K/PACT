<!-- Spectacle -->
<?php
// Initialisation des données à vide
$spectacle = [
    "duree" => "",
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
    <div>
        <label for="nbPlaceShow" class="labelNbPlace" >Nombre de places : </label> <!-- Label nombre de place -->

        <div>
            <input type="number" class="nb" id="nbPlaceShow" name="nbPlaceShow" min="0" placeholder="0" value="<?php echo $spectacle["nbplace"] ?>">
            <!-- Pour le nombre de place -->
            places
        </div>
    <!-- Gestion du prix -->
        <label for="PrixMinShow" class="labelShow1" >Prix minimum : </label>

        <div>
            <input type="number" class="nb" id="PrixMinShow" name="PrixMinShow" min="0" placeholder="0" value="<?php echo $spectacle["prixminimal"] ?>">
            <!-- Pour entrer un prix minimum -->
            €
        </div>
    </div>




    <div class="classDivShow1">
    <!-- Gestion de la durée -->
        <label class="ligne1">Durée du Spectacle : </label>

        <div>
            <div class="classDivLigne">
            <label for="nbMin" class="ligne1">En minutes : </label>
            <input type="number" class="nb" id="nbMin" name="nbMin" min="0" placeholder="0" >
                
            

                <label for="nbMinutesHeure" class="ligne4">En heures : </label>
                <input type="time" id="nbMinutesHeure" class="nb" name="nbMinutesHeure" placeholder="0" value="<?php echo $spectacle["duree"] ?>">
                <!-- Pour entrer un prix minimum -->
            </div>
        </div>
    </div>

</section>