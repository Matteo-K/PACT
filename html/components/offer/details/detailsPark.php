<!-- Parc d'attraction -->
<?php
// Initialisation des données à vide
$parc = [
    "agemin" => "",
    "nbattraction" => "",
    "prixminimal" => "",
    "urlplan" => ""
];
$limitImgPlan = 1;

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

?>
<section id="park">
    <!-- Gestion de l'âge -->
    <label for="park_ageMin">Age minimum :</label>
    <input type="number" id="park_ageMin" name="park_ageMin" min="0"  placeholder="0" value="<?= $parc["agemin"] ?>">
    <!--Gestion du nombre d'acttration -->
    <label for="park_nbAttrac">Nombre d'attractions :</label>
    <input type="number" id="park_nbAttrac" name="park_nbAttrac" min="0"placeholder="0"  class="nbAttrac" value="<?= $parc["nbattraction"] ?>">
    <!-- Gestion du prix minimum -->
    <label for="park_prixMin">Prix Minimum :</label>
    <input type="number" id="park_prixMin" name="park_prixMin" min="0" placeholder="0" value="<?= $parc["prixminimal"] ?>">
    <label for="park_prixMin">€</label>

    <!-- Plan du parc -->
    <div id="park_planTitres">
        <label class="labelPhotos">Photo du plan* </label>
        <label class="labNbPhotos">Vous pouvez insérer <?= $limitImgPlan ?> photo de votre plan</label> <!-- Indication pour l'utilisateur -->
        <label class="labelSuppPhotos"> Cliquez sur l'image pour la supprimer</label>
    </div>

    <label for="park_plan" class="buttonDetailOffer blueBtnOffer">Ajouter</label>
    <input type="file" id="park_plan" name="park_plan[]"
        accept="image/PNG, image/JPG, image/JPEG, image/WEBP, image/GIF" method="post" multiple class="zoneImages" >

    <div id="park_zoneImg"></div>
</section>
<script>
    // Chargement pour l'image du plan
    loadEventLoadImg(
        document.getElementById('park_plan'),
        'img/imagePlan/',
        document.getElementById('park_zoneImg'),
        <?= $limitImgPlan ?>,
        <?= $idOffre ?>
    );
</script>