<!-- Spectacle -->
<?php
// Initialisation des données à vide
$spectacle = [
    "duree" => "",
    "nbplace" => "",
    "prixminimal" => ""
];

// Si le spectacle était déà existante, on récupère les données
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
// Il reste à initialisé les valeurs dans les input
?>
<section id="show"> <!-- Id pour pouvoir modifer separement dans le css -->


    <div>
        <label class="ligne1">Nombre de places : </label> <!-- Label nombre de place -->

        <div>
            <input type="number" 2class="nb" name="nbPlaceShow" min="0" placeholder="0">
            <!-- Pour le nombre de place -->
            <?php echo "places" ?>
        </div>

        <label class="labelShow1">Prix minimum : </label>

        <div>
            <input type="number" class="nb" name="PrixMinShow" min="0" placeholder="0">
            <!-- Pour entrer un prix minimum -->
            <?php echo "€" ?>
        </div>
    </div>




    <div class="classDivShow1">

        <label class="ligne1">Durée du Spectacle : </label>

        <div>
            <div class="classDivLigne">
            <label class="ligne1">En minutes : </label>
            
                <input type="number" class="nb" name="nbHeures" min="0" placeholder="0">
                
            

                <label class="ligne2">En heures : </label>
                <input type="time" class="nb" name="nbMinutesHeure" placeholder="0">
                <label class="ligne3">h</label>
            </div>
        </div>
    </div>

</section>