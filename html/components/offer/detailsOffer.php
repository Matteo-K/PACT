<?php
$stmt = $conn->prepare("SELECT * FROM pact._offre WHERE idoffre=?");
// $stmtResto = $conn->prepare("SELECT * FROM pact._restauration WHERE idoffre=?");
// $stmtSpec = $conn->prepare("SELECT * FROM pact._spectacle WHERE idoffre=?");
// $stmtVisite = $conn->prepare("SELECT * FROM pact.visite WHERE idoffre=?");
// $stmt = $conn->prepare("SELECT * FROM pact._restauration WHERE idoffre=?");

$stmt->execute([$idOffre]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$titre = $result["nom"] ?? "";
$description = $result["description"] ?? "";
$resume = $result["resume"] ?? "";

// Récupération du type d'offre si il existe

$stmt = $conn->prepare("SELECT table_name
    FROM (
        SELECT '_restauration' AS table_name, COUNT(*) AS rows FROM pact._restauration WHERE idoffre = ?
        UNION ALL
        SELECT '_spectacle', COUNT(*) FROM pact._spectacle WHERE idoffre = ?
        UNION ALL
        SELECT '_parcattraction', COUNT(*) FROM pact._parcattraction WHERE idoffre = ?
        UNION ALL
        SELECT '_visite', COUNT(*) FROM pact._visite WHERE idoffre = ?
        UNION ALL
        SELECT '_activite', COUNT(*) FROM pact._activite WHERE idoffre = ?
    ) AS result
    WHERE rows > 0;");
$stmt->execute([$idOffre, $idOffre, $idOffre, $idOffre, $idOffre]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

$categorie = [
    "_restauration" => false,
    "_spectacle" => false,
    "_parcattraction" => false,
    "_visite" => false,
    "_activite" => false,
];


$disableCategorie = false;

if ($result != false) {
    $categorie[$result["table_name"]] = true;
    $disableCategorie = true;

    switch ($result["table_name"]) {
        case '_restauration':
            $stmt = $conn->prepare("SELECT * FROM pact._tag_restaurant WHERE idoffre=?");
            break;

        case '_spectacle':
            $stmt = $conn->prepare("SELECT * FROM pact._tag_spec WHERE idoffre=?");
            break;

        case '_parcattraction':
            $stmt = $conn->prepare("SELECT * FROM pact._tag_parc WHERE idoffre=?");
            break;

        case '_visite':
            $stmt = $conn->prepare("SELECT * FROM pact._tag_visite WHERE idoffre=?");
            break;

        case '_activite':
            $stmt = $conn->prepare("SELECT * FROM pact._tag_act WHERE idoffre=?");
            break;
        
        default:
            # code...
            break;
    }

    //Si une offre existe, on lui charge ses tags pour les afficher et qu'on puisse les supprimer et en ajouter d'autres
    $loadedTags = [];
    $stmt->execute([$idOffre]);
    while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $loadedTags[] = $result["nomtag"];
    }

    //On lui charge également ses images pour la même raison
    $loadedImg = [];
    $stmt = $conn->prepare("SELECT * FROM pact._illustre WHERE idoffre=?");
    $stmt->execute([$idOffre]);

    while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $loadedImg[] = $result["url"];
    }

}

else{    
    $loadedTags = [];
}

//Récupération de tous les tags pour leur sélection dans l'input
$stmt = $conn->prepare("SELECT * FROM pact._tag");
$stmt->execute();

$listeTags = [];


while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $listeTags[] = str_replace("_", " ",$result["nomtag"]);
}


?>
<form id="detailsOffer" action="enregOffer.php" method="post" enctype="multipart/form-data">
    <article id="artDetailOffer">
        <div>

            <label for="nom">Nom de votre offre*</label>
            <input type="text" id="nom" name="nom" placeholder="Nom" value="<?php echo $titre; ?>" required>

            <label for="resume">Résumé de l'offre</label>
            <textarea id="resume" name="resume" placeholder="Courte descrition, 100 caractères maximum" maxlength=99><?php echo $resume;?></textarea>

            <label for="description">Description de votre offre*</label>
            <textarea id="description" name="description" placeholder="Description détaillée, 1000 caractères maximum" maxlength=999 required><?php echo $description; ?></textarea>
        </div>

        <div>

            <div id="choixCategorie">
                <label>Catégorie de l'offre*  <span id="msgCategorie" class="msgError"></span></label>   

                <input type="radio" name="categorie" id="radioRestaurant" value="restaurant" required 
                <?php echo $categorie["_restauration"] ? "checked" : "" ?> 
                <?php echo $disableCategorie && !$categorie["_restauration"] ? "disabled" : "" ?>> 
                <label for="radioRestaurant">Restaurant</label>

                <input type="radio" name="categorie" id="radioParc" value="parc" 
                <?php echo $categorie["_parcattraction"] ? "checked" : "" ?>
                <?php echo $disableCategorie && !$categorie["_parcattraction"] ? "disabled" : "" ?>> 
                <label for="radioParc">Parc d'attraction</label>

                <input type="radio" name="categorie" id="radioActivite" value="activite" 
                <?php echo $categorie["_activite"] ? "checked" : "" ?>
                <?php echo $disableCategorie && !$categorie["_activite"] ? "disabled" : "" ?>> 
                <label for="radioActivite" >Activite</label>

                <input type="radio" name="categorie" id="radioSpectacle" value="spectacle" 
                <?php echo $categorie["_spectacle"] ? "checked" : "" ?>
                <?php echo $disableCategorie && !$categorie["_spectacle"] ? "disabled" : "" ?>> 
                <label for="radioSpectacle">Spectacle</label>

                <input type="radio" name="categorie" id="radioVisite" value="visite" 
                <?php echo $categorie["_visite"] ? "checked" : "" ?>
                <?php echo $disableCategorie && !$categorie["_visite"] ? "disabled" : "" ?>>
                <label for="radioVisite">Visite</label>
            </div>


            

            <div id="inputAutoComplete">
                <label for="inputTag">Tags supplémentaires </label>
                <input type="text" id="inputTag" name="inputTag" placeholder="Entrez & selectionnez un tag correspondant à votre activité">
                <!--v<button type="button" id="ajoutTag" value = ajoutTag class="buttonDetailOffer blueBtnOffer">Ajouter</button> -->
                <ul id="autocompletion"></ul>
            </div>
            
            <section id="sectionTag">
                <!-- Les tags ajoutés apparaîtront ici -->
            </section>

            <p>
                Vous pouvez entrer jusqu'à 6 tags
            </p>



            <div id="choixImage">
                <label>Photos de votre offre*  <span id="msgImage" class="msgError"></span></label>
                <p>
                    Vous pouvez insérer jusqu'à 10 photos<br>
                    Cliquez sur une image pour la supprimer
                </p>
            </div>
            <label for="ajoutPhoto" class="buttonDetailOffer blueBtnOffer">Ajouter</label>
            <input type="file" id="ajoutPhoto" name="ajoutPhoto[]" accept="image/PNG, image/JPG, image/JPEG, image/WEBP, image/GIF" method="post" multiple>
            <div id="afficheImages"></div>


        </div>
    </article>
    
    <article id="specialOffer"> <!-- id pour pouvoir le modifier separement dans le css -->

        <?php
            $source = "details/";
            require_once $source . "detailsRestaurant.php";
            require_once $source . "detailsPark.php";
            require_once $source . "detailsVisit.php";
            require_once $source . "detailsShow.php";
            require_once $source . "detailsActivity.php";
            
        ?>

    </article>
    
    <script>
        const maxTags = 6;
        const maxImages = 10;

        //On récupère en JS la liste des tags pour le script 
        const listeTags = <?php echo json_encode($listeTags) ?>;

        //Récupération des tags déjà présents sur l'offre puis affichage (semblable a la fonction ajouTag())
        const loadedTags = <?php echo json_encode($loadedTags) ?>;

         // Variables de sélection des éléments
        const sectionTag = document.getElementById("sectionTag");
        const pTag = document.querySelector("#sectionTag + p");
        let tags = []; // Tableau pour stocker les tags, comprenant les tags déjà présents

        loadedTags.forEach(valeurTag => {
            ajoutTag(valeurTag);
        });

        function ajoutTag(valeurTag) {

            if (valeurTag && !tags.includes(valeurTag) && tags.length < maxTags) {

                tags.push(valeurTag); // Ajoute le tag dans le tableau

                // Crée l'élément visuel pour afficher le tag
                const elementTag = document.createElement("span");
                elementTag.classList.add("tag");
                elementTag.textContent = valeurTag;

                //On créé une image pour guider l'utilisateur sur le suppression du tag
                let imgCroix = document.createElement("img");
                imgCroix.setAttribute("src", "../img/icone/croix.png");

                // Crée l'input caché pour soumettre le tag avec le formulaire
                const hiddenInputTag = document.createElement("input");
                hiddenInputTag.type = "hidden";
                hiddenInputTag.value = valeurTag;
                hiddenInputTag.name = "tags[]"; // Utilise un tableau pour les tags

                // Ajoute un événement pour supprimer le tag au clic
                elementTag.addEventListener("click", function () {
                    tags.splice(tags.indexOf(valeurTag), 1); // Retire le tag du tableau
                    sectionTag.removeChild(hiddenInputTag); // Supprime l'input caché
                    sectionTag.removeChild(elementTag); // Supprime l'élément visuel du tag
                    pTag.style.color = "black"; // Remet la couleur par défaut si besoin
                });

                // Ajoute l'élément visuel et l'input caché au à la section, et l'image à l'élément visuel
                elementTag.appendChild(imgCroix);
                sectionTag.appendChild(elementTag); 
                sectionTag.appendChild(hiddenInputTag);

                // Réinitialise l'input
                inputTag.value = "";
            } else if (tags.length >= maxTags) {
                pTag.style.color = "red"; // Change la couleur du texte pour signaler la limite atteinte
            } else if (tags.includes(valeurTag)) {
                alert("Ce tag a déjà été ajouté !");
            }
        }


        

        //Affichage des images a leur selection
        const pImage = document.querySelector("#choixImage > p");
        const conteneur = document.getElementById("afficheImages");
        document.getElementById("ajoutPhoto").addEventListener("change", afficheImage);
        
        const loadedImg = <?php echo json_encode($loadedImg) ?>;

        loadedImg.forEach(img => {
            configImage(img, "", "");
        });


        function afficheImage(event) {
            const images = event.target.files;

            Array.from(images).forEach((file) => {
                const reader = new FileReader();
                reader.onload = function(e){
                    configImage("", e.target.result, file);
                }
                reader.readAsDataURL(file);
            });
        }


        function configImage(urlAncien, urlNouveau, file){
            if (conteneur.childElementCount < maxImages) {
                const figureImg = document.createElement("figure");
                figureImg.classList.add("imageOffre");
                if(urlAncien != ""){
                    figureImg.innerHTML = `<img src="${urlAncien}" alt="Photo sélectionnée" title="Cliquez pour supprimer">`
                    const hiddenInputImg = document.createElement("input");
                    hiddenInputImg.type = "hidden";
                    hiddenInputImg.value = urlAncien;
                    hiddenInputImg.name = "imageExistante[]"; 
                    figureImg.appendChild(hiddenInputImg);
                }else{
                    figureImg.innerHTML = `<img src="${urlNouveau}" alt="Photo sélectionnée" title="Cliquez pour supprimer">`;
                }
                conteneur.appendChild(figureImg);

                figureImg.addEventListener("click", function () {
                    if (confirm("Voulez-vous vraiment supprimer cette image ?")) {
                        figureImg.remove(); // Supprime l'élément image et son conteneur
                        pImage.style.color = "black"; //on remet la couleur par défaut au cas où c'etait en rouge
                    }
                });
            } else {
                pImage.style.color = "red"; //On met le txte en rouge pour signaler que la limite des 10 images est atteinte
            }
        }




        const radBtnRestaurant = document.querySelector("#radioRestaurant");
        const radBtnParc = document.querySelector("#radioParc");
        const radBtnActivite = document.querySelector("#radioActivite");
        const radBtnSpectacle = document.querySelector("#radioSpectacle");
        const radBtnVisite = document.querySelector("#radioVisite");

        const divImg = document.querySelector("#afficheImages");

        const msgCategorie = document.querySelector("#msgCategorie");
        const msgImage = document.querySelector("#msgImage");

        radBtnRestaurant.addEventListener("click", removeMsgCategorie);
        radBtnParc.addEventListener("click", removeMsgCategorie);
        radBtnActivite.addEventListener("click", removeMsgCategorie);
        radBtnSpectacle.addEventListener("click", removeMsgCategorie);
        radBtnVisite.addEventListener("click", removeMsgCategorie);

        /**
         * Vérifie si les input sont conforme pour être enregistrer
         * @returns {boolean} - Renvoie true si tous les input sont conformes aux données. False sinon
         */
        function checkOfferValidity(event) {
            let rabBtnCategorie = checkCategorie();
            let img = checkImg();
            return rabBtnCategorie && img;
        }

        /**
         * Vérifie si une catégorie à été tapé 
         * @returns {boolean} - Renvoie true si l'input est conforme. False sinon.
         */
        function checkCategorie() {
            let res = radBtnRestaurant.checked || radBtnParc.checked || radBtnActivite.checked || radBtnSpectacle.checked || radBtnVisite.checked;
            if (!res) {
                msgCategorie.textContent = 
                    "Sélectionner une catégorie";
            } else {
                msgCategorie.textContent = "";
            }
            return res;
        }

        function removeMsgCategorie() {
            msgCategorie.textContent = "";
        }

        function checkImg() {
            let res = true;
            console.log(divImg.childElementCount);
            if (divImg.childElementCount == 0) {
                msgImage.textContent = 
                    "Ajouter une image";
                res = false;
            } else {
                msgImage.textContent = "";
            }
            return res;
        }
    </script>


    