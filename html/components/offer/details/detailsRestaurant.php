<!-- Restaurant -->
<?php
// Initialisation des données à vide
$gamme = [
  "€" => true,
  "€€" => false,
  "€€€" => false
];

// Si le restaurant était déjà existante, on récupère les données
if ($categorie["_restauration"]) {
  $stmt = $conn->prepare("SELECT * from pact._restauration where idoffre=?");
  $stmt->execute([$idOffre]);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($result) {
    $gamme["€"] = false;
    $gamme[$result["gammedeprix"]] = true;
  }
}

?>
<section id="restaurant">
  <h4>Gamme de prix : </h4>
  <div>
    <input type="radio" name="gamme_prix" id="€" value="€" <?php echo $gamme["€"] ? "checked" : "" ?>>
    <label for="€">&euro; (menu à moins de 25€)</label>
  </div>
  <div>
    <input type="radio" name="gamme_prix" id="€€" value="€€" <?php echo $gamme["€€"] ? "checked" : "" ?>>
    <label for="€€">&euro;&euro; (menu de 25€ à 40€)</label>
  </div>
  <div>
    <input type="radio" name="gamme_prix" id="€€€" value="€€€" <?php echo $gamme["€€€"] ? "checked" : "" ?>>
    <label for="€€€">&euro;&euro;&euro; (menu à plus de 40€)</label>
  </div>


  <div id="photosR">
                <label>Photos du menu*  <span id="msgImage" class="msgError"></span></label>
                <p>
                    Vous pouvez insérer jusqu'à 5 photos<br>
                    Cliquez sur une image pour la supprimer
                </p>
            </div>
            <label for="ajoutPhotoMenu" class="buttonDetailOffer blueBtnOffer">Ajouter</label>
            <input type="file" id="ajoutPhotoMenu" name="ajoutPhotoMenu[]" accept="image/PNG, image/JPG, image/JPEG, image/WEBP, image/GIF" method="post" multiple>
            <div id="afficheImages"></div>
</section>



<script>

const maxImages1 = 5;
          //Affichage des images a leur selection
          const pImage = document.querySelector("#choixImage > p");
        const conteneur = document.getElementById("photosR");
        document.getElementById("ajoutPhoto").addEventListener("change", photosR);
        
        const loadedImg = <?php echo json_encode($loadedImg) ?>;

        const photosSelect = []; // Stocker les fichiers sélectionnés

        loadedImg.forEach(img => {
            configImage(img, "", "");
        });


        function afficheImage(event) {
            let compteurImgMax = conteneur.childElementCount;
            const images = event.target.files;

            console.log(images);

            Array.from(images).forEach((file) => {
                alert(compteurImgMax);
                if (compteurImgMax >= maxImages1) {
                    pImage.style.color = "red";
                    alert("Vous ne pouvez pas ajouter plus d'images");
                }
                else{
                    compteurImgMax++;
                    const reader = new FileReader();
                    reader.onload = function(e){
                        photosSelect.push(file);
                        configImage("", e.target.result, file);
                    }
                    reader.readAsDataURL(file);
                }
            });
        }

        function configImage(urlAncien, urlNouveau, file) {
            if (conteneur.childElementCount < maxImages1) {
                const figureImg = document.createElement("figure");
                figureImg.classList.add("imageOffre");
                if (urlAncien != "") {
                    figureImg.innerHTML = `<img src="${urlAncien}" alt="Photo sélectionnée" title="Cliquez pour supprimer">`;
                    const hiddenInputImg = document.createElement("input");
                    hiddenInputImg.type = "hidden";
                    hiddenInputImg.value = urlAncien;
                    hiddenInputImg.name = "imageExistante[]";
                    figureImg.appendChild(hiddenInputImg);
                } else {
                    figureImg.innerHTML = `<img src="${urlNouveau}" alt="Photo sélectionnée" title="Cliquez pour supprimer">`;
                }
                conteneur.appendChild(figureImg);

                figureImg.addEventListener("click", function () {
                    if (confirm("Voulez-vous vraiment supprimer cette image ?")) {
                        figureImg.remove();
                        photosSelect.splice(photosSelect.indexOf(file), 1); // Supprimer le fichier de la liste
                        pImage.style.color = "black"; // Remettre la couleur par défaut
                    }
                });
            } else {
                pImage.style.color = "red";
            }
        }
    
</script>