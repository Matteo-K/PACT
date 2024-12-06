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


$stmt = $conn->prepare("SELECT * from pact._image where idoffre=?");
$stmt->execute([$idOffre]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<section id="park"> <!-- Section pour le CSS -->
    <!-- Gestion de l'àge -->
        <div class="ageMinP">
            <label class="labelGauche">Age minimum :</label>
            <input type="number" id="agePark" name="AgePark" min="0"  placeholder="0">
        </div>
    <!--Gestion du nombre d'actration -->
        <div class="NbEtPrixPark">
            <label class="labelGauche">Nombre d'attractions :</label>
            <input type="number" id="nbAttrac" name="nbAttrac"min="0"placeholder="0"  class="nbAttrac">
    <!-- Gestion du prix minimum -->
            <label class="labelGauche">Prix Minimum :</label>
            <input type="number" id="prixMinPark" name="prixMinPark" min="0" placeholder="0">
            <label class="labelEuro">€</label>
        </div>

    
    <!-- Pour ajouter le plan du parc -->
        <div id="choixImage2">
            <h3>Plan : </h3>
            <p>
                Vous pouvez insérer jusqu'à 1 photo

            </p>
        </div>
        <label for="ajoutPhoto2" class="buttonDetailOffer blueBtnOffer">Ajouter</label>
        <input type="file" id="ajoutPhoto2" name="ajoutPhoto2[]"
            accept="image/PNG, image/JPG, image/JPEG, image/WEBP, image/GIF" method="post" multiple class="zoneImages" >
        <div id="afficheImages2" class="afficheImages2"></div>



</section>