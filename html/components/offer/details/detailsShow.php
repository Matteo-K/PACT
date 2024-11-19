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
        $spectacle["agemin"] = $result["agemin"];
        $spectacle["nbplace"] = $result["nbplace"];
        $spectacle["prixminimal"] = $result["prixminimal"];
    }
}
// Il reste à initialisé les valeurs dans les input
?>
<section id="show"> <!-- Id pour pouvoir modifer separement dans le css -->
    <article>

        <div>
            <label class="ligne1">Nombre de places : </label> <!-- Label nombre de place -->
            
            <div>
                <input type="number" 2class="nb" name="nbPlaceShow" placeholder="0"> <!-- Pour le nombre de place -->
                <?php echo "places" ?>
            </div>

            <label class="ligne1">Prix minimum : </label>
            
            <div>
                <input type="number" class="nb" name="PrixMinShow" placeholder="0"> <!-- Pour entrer un prix minimum -->
                <?php echo "€" ?>
            </div>
        </div>

    </article>


    <article>

        <label class="ligne1">Durée du Spectacle : </label>
        <br>


        <div>
            <label class="ligne1">En minutes : </label>

            <div>
                <input type="time" class="nb" name="nbHeures" placeholder="0"> <!-- Pour entrer un prix minimum -->



                <label class="ligne1">En heures : </label>


                <input type="time" class="nb" name="nbMinutesHeure" placeholder="0">
                <!-- Pour entrer un prix minimum -->
            </div>
        </div>
    </article>



</section>