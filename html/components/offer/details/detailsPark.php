<!-- Parc d'attraction -->
<?php
// Initialisation des données à vide
$parc = [
    "agemin" => "",
    "nbattraction" => "",
    "prixminimal" => "",
    "urlplan" => ""
];

// Si le parc d'attraction était déà existante, on récupère les données
if ($categorie["_parcattraction"]) {
    $stmt = $conn->prepare("SELECT * from pact._parcattraction where idoffre=?");
    $stmt->execute([$idOffre]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $parc["agemin"] = $result["agemin"];
        $parc["nbattraction"] = $result["nbattraction"];
        $parc["prixminimal"] = $result["prixminimal"];
        $parc["urlplan"] = $result["urlplan"];
    }
}
// Il reste à initialisé les valeurs dans les input
?>
<section id="park">
    <article>
        <div>
            <label>Age minimum</label>
            <input type="number" id="agePark" name="AgePark" min="0" readonly placeholder="0">
        </div>

        <div>
            <label>Nombre d'attraction</label>
            <input type="number" id="nbAttrac" name="nbAttrac"min="0"placeholder="0">
        </div>

        <div>
            <label>Prix Minimum</label>
            <input type="number" id="prixMinPark" name="prixMinPark" min="0" placeholder="0">
        </div>



    </article>


    <article>


        <br>
        <div id="choixImage2">
            <label>Plan : </label>
            <p>
                Vous pouvez insérer jusqu'à 1 photo<br>

            </p>
        </div>
        <label for="ajoutPhoto2" class="buttonDetailOffer blueBtnOffer">Ajouter</label>
        <input type="file" id="ajoutPhoto2" name="ajoutPhoto2[]"
            accept="image/PNG, image/JPG, image/JPEG, image/WEBP, image/GIF" method="post" multiple>
        <div id="afficheImages2"></div>
        <br>


        <br>

    </article>
</section>