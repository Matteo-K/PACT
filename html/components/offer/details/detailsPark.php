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
    <div>
        <!-- Gestion de l'âge -->
        <label class="labelTitre" for="park_ageMin">Age minimum*<span id="park_msgAge" class="msgError"></span></label>
        <div>
            <input type="number" id="park_ageMin" name="park_ageMin" min="0"  placeholder="0" value="<?= $parc["agemin"] ?>">
        </div>
        <!--Gestion du nombre d'acttration -->
        <label class="labelTitre" for="park_nbAttrac">Nombre d'attractions*<span id="park_msgAttract" class="msgError"></span></label>
        <div>
            <input type="number" id="park_nbAttrac" name="park_nbAttrac" min="0"placeholder="0"  class="nbAttrac" value="<?= $parc["nbattraction"] ?>">
        </div>
        <!-- Gestion du prix minimum -->
        <label class="labelTitre" for="park_prixMin">Prix Minimum*<span id="park_msgPrix" class="msgError"></span></label>
        <div>
            <input type="number" id="park_prixMin" name="park_prixMin" min="0" placeholder="0" value="<?= $parc["prixminimal"] ?>">
            <label for="park_prixMin">€</label>
        </div>
    </div>

    <!-- Plan du parc -->
     <div>
         <div id="park_planTitres">
            <div id="insereImg">
                <label class="labelTitre">Photo du plan*<span id="park_msgPlan" class="msgError"></span></label>
                <label for="park_plan" class="modifierBut">Ajouter</label>
            </div>
            <div id="park_zoneImg"></div>
            <label class="labelSousTitre">Vous pouvez insérer <?= $limitImgPlan ?> photo de votre plan</label> <!-- Indication pour l'utilisateur -->
            <label class="labelSousTitre"> Cliquez sur l'image pour la supprimer</label>
            <input type="file" id="park_plan" name="park_plan[]"
            accept="image/PNG, image/JPG, image/JPEG, image/WEBP, image/GIF" method="post" multiple class="zoneImages" >
        </div>
    </div>
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

    const park_inputAge = document.getElementById("park_ageMin");
    const park_inputAttract = document.getElementById("park_nbAttrac");
    const park_inputPrix = document.getElementById("park_prixMin");
    const park_zoneImg = document.getElementById("park_zoneImg");

    const park_msgAge = document.getElementById("park_msgAge");
    const park_msgAttract = document.getElementById("park_msgAttract");
    const park_msgPrix = document.getElementById("park_msgPrix");
    const park_msgPlan = document.getElementById("park_msgPlan");

    function checkPark() {
        let age = checkparkAgeMin();
        let attract = checkParkNbAttrac();
        let prix = checkParkPrixMin();
        let img = checkParkPlan();
        return age && attract && prix;
    }

    function checkparkAgeMin() {
        let res = true;
        const age = park_inputAge.value.trim();
        const agePattern = /^\d+$/;
    
        if (!agePattern.test(age) && age !== "") {
            park_msgAge.textContent = "Le champ âge doit contenir des chiffres positifs";
            res = false;
        }
        return res;
    }

    park_inputAge.addEventListener("blur", () => checkparkAgeMin());
    park_inputAge.addEventListener("focus", () => {
        park_msgAge.textContent = "";
        park_inputAge.classList.remove("inputErreur");
    });

    function checkParkNbAttrac() {
        let res = true;
        const nbAttract = park_inputAttract.value.trim();
        const prixPattern = /^\d+$/;
    
        if (!prixPattern.test(nbAttract) && nbAttract !== "") {
            park_msgAttract.textContent = "Le champ nombre d'attraction doit contenir des chiffres positifs";
            res = false;
        }
    
        return res;
    }

    park_inputAttract.addEventListener("blur", () => checkParkNbAttrac());
    park_inputAttract.addEventListener("focus", () => {
        park_msgAttract.textContent = "";
        park_inputAttract.classList.remove("inputErreur");
    });
    
    function checkParkPrixMin() {
        let res = true;
        const prix = park_inputPrix.value.trim();
        const prixPattern = /^\d+$/;
    
        if (!prixPattern.test(prix) && prix !== "") {
            park_msgPrix.textContent = "Le champ prix doit contenir des chiffres positifs";
            res = false;
        }
    
        return res;
    }

    park_inputPrix.addEventListener("blur", () => checkParkPrixMin());
    park_inputPrix.addEventListener("focus", () => {
        park_msgPrix.textContent = "";
        park_inputPrix.classList.remove("inputErreur");
    });

    function checkParkPlan() {
        let res = true;
    
        if (park_zoneImg.childElementCount == 0) {
            park_msgPlan.textContent = "Ajouter une image";
            res = false;
        } else {
            park_msgPlan.textContent = "";
        }
    
        return res;
    }

    park_zoneImg.addEventListener("input", () => {
        park_msgPlan.textContent = "";
    });

</script>