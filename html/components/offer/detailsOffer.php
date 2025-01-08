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
<form id="detailsOffer" action="enregOffer.php" method="post" enctype="multipart/form-data">
    <article id="artDetailOffer">
        <div id="aboutOffer">
            <div>
                <label for="nom">Nom de votre offre*</label>
                <input type="text" id="nom" name="nom" placeholder="Nom" maxlength=35 value="<?php echo $titre; ?>" required>
            </div>
            <div>
                <label for="resume">Résumé de l'offre</label>
                <input type="text" id="resume" name="resume" placeholder="Accroche de l'offre, 50 caractères maximum" maxlength=50 value="<?php echo $resume;?>">
            </div>
            <div>
                <label for="description">Description de votre offre*</label>
                <textarea id="description" name="description" placeholder="Description détaillée, 900 caractères maximum" maxlength=900 required><?php echo $description; ?></textarea>
            </div>
            <div id="tagsOffer">
                <div id="inputAutoComplete">
                    <label for="inputTag">Tags supplémentaires </label>
                    <input type="text" id="inputTag" name="inputTag" placeholder="Entrez & selectionnez un tag correspondant à votre activité">
                    <!--<button type="button" id="ajoutTag" value = ajoutTag class="buttonDetailOffer blueBtnOffer">Ajouter</button> -->
                    <ul id="autocompletion"></ul>
                </div>
                <section id="sectionTag">
                    <!-- Les tags ajoutés apparaîtront ici -->
                </section>
                <p>
                    Vous pouvez entrer jusqu'à 6 tags
                </p>
            </div>
        </div>
        <div id="blcImg">
            <div id="choixImage">
                <label>Photos de votre offre*  <span id="msgImage" class="msgError"></span></label>
                <p>
                    Vous pouvez insérer jusqu'à 10 photos<br>
                    Cliquez sur une image pour la supprimer
                </p>
            </div>
            <label for="ajoutPhoto" class="buttonDetailOffer blueBtnOffer">Ajouter</label>
            <!-- <input type="file" id="ajoutPhoto" name="ajoutPhoto[]" accept="image/PNG, image/JPG, image/JPEG, image/WEBP, image/GIF" method="post" multiple>  je teste-->
            <!-- <div id="afficheImages"></div> Gabriel je teste avec mon truc ewen  -->
            <input 
                type="file" 
                id="ajoutPhoto" 
                name="images[]" 
                accept="image/PNG, image/JPG, image/JPEG, image/WEBP, image/GIF"
                multiple 
                onchange="handleFiles(this)"
            />
            <div id="afficheImages"></div>
            <div>
                <div>
                    <figure class="bigImgOffer"></figure>
                </div>
                <div>
                    <figure class="imgOffer"></figure>
                    <figure class="imgOffer"></figure>
                    <figure class="imgOffer"></figure>
                    <figure class="imgOffer"></figure>
                    <figure class="imgOffer"></figure>
                    <figure class="imgOffer"></figure>
                    <figure class="imgOffer"></figure>
                    <figure class="imgOffer"></figure>
                    <figure class="imgOffer"></figure>
                    <figure class="imgOffer"></figure>
                </div>
            </div>
        </div>
    </article>
    
    <article id="specialOffer"> <!-- id pour pouvoir le modifier separement dans le css -->
        <span id="msgCategorie" class="msgError"></span>
        <section id="choixCategorie">
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
    
    <script>
        const maxTags = 6;
        const maxImages = 10;

        //On récupère en JS la liste des tags pour le script 
        const listeTags = <?php echo json_encode($listeTags) ?>;

        //Récupération des tags déjà présents sur l'offre puis affichage (semblable a la fonction ajouTag())
        const loadedTags = <?php echo json_encode($loadedTags) ?>;

        /**
         * Gestion des TAGS
         */

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


        // js de gabriel pour les images test

        /**
         * Gestion des images
         */

        let existingImagesCount = 0; // Compteur des images existantes sur le serveur
        const idOffre = <?php echo $idOffre ?>; // ID de l'offre, à remplacer par une valeur dynamique si nécessaire

        // Fonction pour charger les images existantes
        function loadExistingImages() {
            fetch('upload.php?idOffre=' + idOffre)
                .then(response => response.json())
                .then(data => {
                    const existingPreview = document.getElementById('afficheImages');
                    existingPreview.innerHTML = ""; // Réinitialise la liste
                    data.images.forEach((image, index) => {
                        const img = document.createElement('img');
                        img.src = `img/imageOffre/${idOffre}/${image}`;
                        img.alt = image;
                        img.title = `Cliquez pour supprimer ${image}`;
                        img.style.cursor = 'pointer';
                    
                        // Ajouter un attribut data-index pour garder une référence unique de l'image
                        img.setAttribute('data-index', index);
                    
                        // Ajoute un gestionnaire de clic pour supprimer l'image
                        img.onclick = () => {
                            if (confirm("Êtes-vous sûr de vouloir supprimer cette image ?")) {
                                deleteImage(image, img, index); // Supprime l'image si l'utilisateur confirme
                            }
                        };
                    
                        existingPreview.appendChild(img);
                    });
                    existingImagesCount = data.images.length; // Met à jour le compteur d'images existantes
                })
                .catch(error => console.error('Erreur de chargement des images:', error));
        }

        // Fonction pour supprimer une image existante
        function deleteImage(fileName, imgElement, index) {
            // Supprimer l'image du DOM immédiatement
            imgElement.remove();

            // Faire la requête de suppression côté serveur
            fetch('upload.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=delete&fileName=${encodeURIComponent(fileName)}&idOffre=${idOffre}`,
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    // En cas d'erreur côté serveur, restaurer l'image dans le DOM et afficher un message
                    alert('Erreur lors de la suppression de l\'image sur le serveur : ' + data.error);
                    // Recharger la liste des images pour restaurer l'état correct
                    loadExistingImages();
                } 
            })
            .catch(error => {
                // En cas de problème avec la requête, restaurer l'image et afficher un message d'erreur
                alert('Erreur lors de la suppression de l\'image. Veuillez réessayer.');
                console.log(error);
                // Recharger la liste des images pour restaurer l'état
                loadExistingImages();
            });
        }





        // Fonction pour gérer les fichiers sélectionnés
        function handleFiles(input) {
            const maxFiles = 10;

            // Vérifie la limite d'images
            const totalSelected = existingImagesCount + input.files.length;

            if (totalSelected > maxFiles) { 
                alert(
                    `Erreur : Vous ne pouvez importer que ${maxFiles} photos maximum.\n` +
                    `${existingImagesCount} sont déjà sur le serveur, et vous avez sélectionné ${input.files.length}.`
                );
                input.value = ""; // Réinitialise la sélection
                return;
            }

            // Ajoute les nouvelles images sélectionnées
            Array.from(input.files).forEach(file => {
                if (existingImagesCount < maxFiles) {
                    if (file.type.startsWith("image/")) {
                        // Envoyer directement chaque fichier pour importation
                        uploadFile(file);
                    }
                }
            });

            input.value = ""; // Réinitialise l'input
        }

        // Fonction pour envoyer un fichier au serveur
        function uploadFile(file) {
            const formData = new FormData();
            formData.append('images[]', file);
            formData.append('action', 'upload');
            formData.append('idOffre', idOffre);

            fetch('upload.php', {
                method: 'POST',
                body: formData,
            })
                .then(response => response.json())
                .then(data => {
                    const errors = data.filter(result => result.error);
                    if (errors.length > 0) {
                        alert(`Erreur lors du téléchargement de "${file.name}" :\n${errors.map(e => e.error).join('\n')}`);
                    } else {
                        loadExistingImages(); // Recharge la liste des images existantes
                    }
                })
                .catch(error => console.error('Erreur lors de l\'envoi du fichier:', error));
        }

        // Charger les images existantes au chargement de la page
        window.onload = loadExistingImages();

        // Rafraîchir la liste manuellement

        // Gestion des fichiers sélectionnés (liaison de l'événement onchange)
        document.getElementById('ajoutPhoto').addEventListener('change', function () {
            handleFiles(this);
        });

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


    