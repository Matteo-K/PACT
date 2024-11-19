<!-- Parc d'attraction -->
<?php
// Initialisation des données à vide


// Si le parc d'attraction était déà existante, on récupère les données
if ($categorie["_parcattraction"]) {
    $stmt = $conn->prepare("SELECT * from pact._parcattraction where idoffre=?");
    $stmt->execute([$idOffre]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result != false) {
        
    }
}
?>
<section id="park">
    <article>


        <br>
        <div id="choixImage2">
            <label>Plan : </label>
            <p>
                Vous pouvez insérer jusqu'à 5 photos<br>

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