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
$limitImgDtls = 10;

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
        $loadedTags[] = str_replace("_", " ",$result["nomtag"]);
    }

    //On lui charge également ses images pour la même raison
    $stmt = $conn->prepare("SELECT * FROM pact._illustre WHERE idoffre=?");
    $stmt->execute([$idOffre]);

    while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $loadedImg[] = $result["url"];
    }
}

else{    
    $loadedTags = [];
    $loadedImg = [];
}

//Récupération de tous les tags pour leur sélection dans l'input
$stmt = $conn->prepare("SELECT * FROM pact._tag ORDER BY nomtag asc");
$stmt->execute();

$listeTags = [];

while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $listeTags[] = str_replace("_", " ",$result["nomtag"]);
}
?>
<script>
    // Durée des activitées
    function minutesToHours(minutesInput, hoursInput) {
        const totalMinutes = parseInt(minutesInput.value) || 0;
        const hours = Math.floor(totalMinutes / 60);
        const minutes = totalMinutes % 60;

        hoursInput.value = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}`;
    }

    function hoursToMinutes(minutesInput, hoursInput) {
        const timeParts = hoursInput.value.split(":");
        const hours = parseInt(timeParts[0]) || 0; // Récupérer les heures
        const minutes = parseInt(timeParts[1]) || 0; // Si minutes non saisies, elles valent 0

        const totalMinutes = hours * 60 + minutes;

        minutesInput.value = totalMinutes;
    }
</script>
<form id="detailsOffer" action="enregOffer.php" method="post" enctype="multipart/form-data">
    <article id="artDetailOffer">
        <div id="aboutOffer">
            <div>
                <label for="nom" class="labelTitre">Nom de votre offre* <span id="msgNomOffre" class="msgError"></span></label>
                <input type="text" id="nom" name="nom" placeholder="Nom" maxlength=35 value="<?php echo $titre; ?>" required>
            </div>
            <div>
                <label for="resume" class="labelTitre">Résumé de l'offre</label>
                <input type="text" id="resume" name="resume" placeholder="Accroche de l'offre, 50 caractères maximum" maxlength=50 value="<?php echo $resume;?>">
            </div>
        </div>
        <div id="blcDescription">
            <label for="description" class="labelTitre">Description de votre offre* <span id="msgDescription" class="msgError"></span></label>
            <textarea id="description" name="description" placeholder="Description détaillée, 900 caractères maximum" maxlength=900 required><?php echo $description; ?></textarea>
        </div>
        <div id="blcTagImg">
            <div id="tagsOffer">
                <div id="inputAutoComplete">
                    <label for="inputTag" class="labelTitre">Tags supplémentaires<span id="msgTag" class="msgError"></span></label>
                    <input type="text" id="inputTag" name="inputTag" placeholder="Entrez & selectionnez un tag correspondant à votre activité">
                    <ul id="autocompletion"></ul>
                </div>
                <section id="sectionTag">
                    <!-- Les tags ajoutés apparaîtront ici -->
                </section>
                <label class="labelSousTitre">
                    Vous pouvez entrer jusqu'à 6 tags
                </label>
            </div>
            <div id="blcImg">
                <div id="insereImg">
                    <label class="labelTitre">Photos de votre offre*<span id="msgImage" class="msgError"></span></label>
                    <label for="ajoutPhoto" class="modifierBut">Ajouter</label>
                </div>
                <div id="afficheImages">
                    <!-- Les images ajoutés apparaîtront ici -->
                </div> 
                <label class="labelSousTitre">Vous pouvez insérer jusqu'à <?= $limitImgDtls ?> photos<br></label> <!-- Indication pour l'utilisateur -->
                <label class="labelSousTitre"> Cliquez sur l'image pour la supprimer</label>
                <!-- <input type="file" id="ajoutPhoto" name="ajoutPhoto[]" accept="image/PNG, image/JPG, image/JPEG, image/WEBP, image/GIF" method="post" multiple>  je teste-->
                <!-- <div id="afficheImages"></div> Gabriel je teste avec mon truc ewen  -->
                <input 
                    type="file" 
                    id="ajoutPhoto" 
                    name="images[]" 
                    accept="image/PNG, image/JPG, image/JPEG, image/WEBP, image/GIF"
                    multiple 
                />
            </div>
        </div>
    </article>
    
    <article id="specialOffer"> <!-- id pour pouvoir le modifier separement dans le css -->
        <span id="msgCategorie" class="msgError"></span>
        <section id="choixCategorie">
            <label for="page-select" class="labelTitre">Sélectionnez une catégorie</label>
            <select name="categorie" id="selectCategorie" class="<?= $disableCategorie ? "disabledSelect" : "" ?>">
                <option value="restaurant"
                <?php echo $categorie["_restauration"] ? "selected" : "" ?>>
                    Restaurant
                </option>
                <option value="parc"
                <?php echo $categorie["_parcattraction"] ? "selected" : "" ?>>
                    Parc d'attraction
                </option>
                <option value="activite"
                <?php echo $categorie["_activite"] ? "selected" : "" ?>> 
                    Activite
                </option>
                <option value="spectacle"
                <?php echo $categorie["_spectacle"] ? "selected" : "" ?>> 
                    Spectacle
                </option>
                <option value="visite"
                <?php echo $categorie["_visite"] ? "selected" : "" ?>>
                    Visite
                </option>
            </select>
        </section>
        <?php
            $source = "details/";
            require_once $source . "detailsRestaurant.php";
            require_once $source . "detailsPark.php";
            require_once $source . "detailsVisit.php";
            require_once $source . "detailsShow.php";
            require_once $source . "detailsActivity.php";
            
        ?>
    </article>
    <a href="#specialOffer" class="scrollDown">
        <img alt="scroll" src="../../img/icone/scroll.gif">
    </a>
    <script>
        // CATEGORIES DE L'OFFRE
        /* Affichage pour un type d'offre particulier */
        // Sélection des éléments du formulaire et des radios
        const select = document.getElementById("selectCategorie");
        
        const RestaurantOffer = document.getElementById("restaurant");
        const ParkOffer = document.getElementById("park");
        const ActiviteOffer = document.getElementById("activity");
        const SpectacleOffer = document.getElementById("show");
        const VisiteOffer = document.getElementById("visit");
        
        function hidenOffer() {
            RestaurantOffer.style.display = "none";
            ParkOffer.style.display = "none";
            ActiviteOffer.style.display = "none";
            SpectacleOffer.style.display = "none";
            VisiteOffer.style.display = "none";
        }
        
        // Fonction pour afficher ou masquer la div des require_once
        function toggleSpecialOffer() {
            hidenOffer();
            if (select.value == "parc") {
                ParkOffer.style.display = "flex";
            } else if (select.value == "activite") {
                ActiviteOffer.style.display = "flex";
            } else if (select.value == "spectacle") {
                SpectacleOffer.style.display = "flex";
            } else if (select.value == "visite") {
                VisiteOffer.style.display = "block";
            } else if (select.value == "restaurant") {
                RestaurantOffer.style.display = "flex";
            }
        }
        
        // Associe la fonction de toggle au clic sur tous les boutons radio
        select.addEventListener("input", toggleSpecialOffer);
        
        // Appel initial de la fonction pour vérifier l'état initial
        toggleSpecialOffer();

        // TAGS
        const maxTags = 6;
        const maxImages = <?= $limitImgDtls ?>;
        const msgTag = document.getElementById("msgTag");

        //On récupère en JS la liste des tags pour le script 
        const listeTags = <?php echo json_encode($listeTags) ?>;

        //Récupération des tags déjà présents sur l'offre puis affichage (semblable a la fonction ajouTag())
        const loadedTags = <?php echo json_encode($loadedTags) ?>;

        const indexTag = createAutoCompletion(
            document.getElementById("inputTag"),
            "autocompletion",
            msgTag,
            listeTags,
            ajoutElement,
            document.getElementById("inputTag"), //-- paramètres de la fonction ajoutElement
            document.getElementById("sectionTag"),
            msgTag,
            'tags[]',
            maxTags,
            "span",
            ["tag"]
        );

         // Variables de sélection des éléments
        const sectionTag = document.getElementById("sectionTag");

        loadedTags.forEach(valeurTag => {
            ajoutElement(valeurTag,
                indexTag,
                document.getElementById("inputTag"),
                document.getElementById("sectionTag"),
                msgTag,
                'tags[]',
                maxTags,
                "span",
                ["tag"]
            );
        });

        // Chargement pour les images de l'offre
        loadEventLoadImg(
            document.getElementById('ajoutPhoto'),
            'img/imageOffre/',
            document.getElementById('afficheImages'),
            <?= $limitImgDtls ?>,
            <?= $idOffre ?>
        );

        //Affichage des images a leur selection
        // const pImage = document.querySelector("#choixImage > p");
        // const conteneur = document.getElementById("afficheImages");
        // let inputFile = document.getElementById("ajoutPhoto"); 
        // inputFile.addEventListener("change", afficheImage);

        // const photosSelect = []; // Stocker les fichiers sélectionnés

        // let fichiersDerniereRequete = []; // Stocke uniquement les photos de la dernière requête


        // const loadedImg = <?php //echo json_encode($loadedImg) ?>;

        // loadedImg.forEach(img => {
        //     configImage(img, "", null);
        // });


        // function afficheImage(event) {
        //     const images = Array.from(event.target.files); // On convertit la liste des fichiers de l'input en tableau
        //     let compteurImgMax = conteneur.childElementCount;
        //     alert("afficheImages");

        //     images.forEach((file) => {
        //         if (compteurImgMax >= maxImages) {
        //             pImage.style.color = "red";
        //             alert("C'est plein");
        //             return;
        //         }
        //         else{
        //             compteurImgMax++;
        //             const reader = new FileReader();
        //             reader.onload = function(e){
        //                 photosSelect.push(file);
        //                 fichiersDerniereRequete.push(file);
        //                 configImage("", e.target.result, file);
        //             };
        //             reader.readAsDataURL(file);
        //         }
        //     });
        //     alert("envoyer Images ----->");
        //     envoyerImages();
        // }

        // function configImage(urlAncien, urlNouveau, file) {
        //     if (conteneur.childElementCount < maxImages) {
        //         //On créé la balise notamment pour l'affichage
        //         const figureImg = document.createElement("figure");
        //         figureImg.classList.add("imageOffre");

        //         if (urlAncien != "") {
        //             //Traitement des anciennes images chargées
        //             figureImg.innerHTML = `<img src="${urlAncien}" alt="Photo sélectionnée" title="Cliquez pour supprimer">`;
        //             const hiddenInputImg = document.createElement("input"); // L'input caché passe l'url des anciennes images au enregOffer.php
        //             hiddenInputImg.type = "hidden";
        //             hiddenInputImg.value = urlAncien;
        //             hiddenInputImg.name = "imageExistante[]";
        //             figureImg.appendChild(hiddenInputImg);
        //         } else {
        //             //Traitement des nouvelles images
        //             figureImg.innerHTML = `<img src="${urlNouveau}" alt="Photo sélectionnée" title="Cliquez pour supprimer">`;
        //         }

        //         //On ajoute la balise a la section pour l'affichage
        //         conteneur.appendChild(figureImg);

        //         figureImg.addEventListener("click", function () {
        //             if (confirm("Voulez-vous vraiment supprimer cette image ?")) {
        //                 figureImg.remove();
        //                 photosSelect.splice(photosSelect.indexOf(file), 1); // Supprimer le fichier de la liste
        //                 pImage.style.color = "black"; // Remettre la couleur par défaut
        //                 fichiersDerniereRequete.splice(fichiersDerniereRequete.indexOf(file), 1); // Supprime du tableau temporaire
        //             }
        //         });
        //     } else {
        //         pImage.style.color = "red";
        //     }
        // }


        // function envoyerPhotos() {
        //     if (fichiersDerniereRequete.length === 0) {
        //         alert("Aucune nouvelle photo à envoyer.");
        //         return;
        //     }

        //     // Préparer les données à envoyer
        //     const formData = new FormData();
        //     fichiersDerniereRequete.forEach((file) => formData.append("images[]", file));

        //     console.log("Fichiers envoyés :", fichiersDerniereRequete); // Debug

        //     fetch("enregOffer.php", {
        //         method: "POST",
        //         body: formData,
        //     })
        //         .then((response) => {
        //             if (response.ok) {
        //                 alert("Photos envoyées avec succès !");
        //                 fichiersDerniereRequete = []; // Réinitialiser les fichiers envoyés
        //             } else {
        //                 alert("Erreur lors de l'envoi des photos.");
        //             }
        //         })
        //         .catch((error) => {
        //             console.error("Erreur lors de l'envoi :", error);
        //         });
        // }


        const nom = document.querySelector("#nom");
        const description = document.querySelector("#description");
        const divImg = document.querySelector("#afficheImages");
        const inputFile = document.querySelector("#ajoutPhoto");
        
        const msgNom = document.querySelector("#msgNomOffre");
        const msgDescription = document.querySelector("#msgDescription");
        const msgImage = document.querySelector("#msgImage");

        /**
         * Vérifie si les input sont conforme pour être enregistrer
         * @returns {boolean} - Renvoie true si tous les input sont conformes aux données. False sinon
         */
        function checkOfferValidity(event) {
            msgTag.textContent = "";
            let nomCheck = checkNom();
            let descriptionCheck = checkDescription();
            let imgCheck = checkImg();

            // Info catégorie
            let categorie;
            switch (select.value) {
                case "parc":
                    categorie = checkPark();
                    break;
                case "activite":
                    categorie = checkActivity();
                    break;
                case "spectacle":
                    categorie = checkSpectacle();
                    break;
                case "visite":
                    categorie = checkVisit();
                    break;
                case "restaurant":
                    categorie = true;
                    break;
            
                default:
                    categorie = false;
                    break;
            }

            return nomCheck && descriptionCheck && imgCheck && categorie;
        }

        /**
         * Vérifie si le nom de l'offre est correct
         * @returns {boolean} - Renvoie true si le nom est correcte. false sinon
         */
        function checkNom() {
            let res = true;
            if (nom.value == "") {
                msgNom.textContent = 
                    "Ajouter un nom à l'offre";
                res = false;
                nom.classList.add("inputErreur");
            }
            return res;
        }

        nom.addEventListener("blur", () => checkNom());
        nom.addEventListener("focus", () => {
            msgNom.textContent = "";
            nom.classList.remove("inputErreur");
        });
        
        /**
         * Vérifie si la description de l'offre est correct
         * @returns {boolean} - Renvoie true si la description est correcte. false sinon
         */
        function checkDescription() {
            let res = true;
            if (description.value == "") {
                msgDescription.textContent = 
                    "Ajouter une description";
                res = false;
                description.classList.add("inputErreur");
            }
            return res;
        }

        description.addEventListener("blur", () => checkDescription());
        description.addEventListener("focus", () => {
            msgDescription.textContent = "";
            description.classList.remove("inputErreur");
        });
        
        /**
         * Vérifie si l'offre contient au moins une image
         * @returns {boolean} - Renvoie true si l'offre contient au moins une image. false sinon
         */
        function checkImg() {
            let res = true;
            if (divImg.childElementCount == 0) {
                msgImage.textContent = 
                "Ajouter une image";
                res = false;
            } else {
                msgImage.textContent = "";
            }
            return res;
        }
        
        inputFile.addEventListener("input", () => {
            msgImage.textContent = "";
        });
    </script>


    