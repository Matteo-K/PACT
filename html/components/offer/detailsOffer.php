

<form id="detailsOffer" action="enregOffer.php" method="post" enctype="multipart/form-data">
    <article id="artDetailOffer">
        <div>

            <label for="nom">Nom de votre offre*</label>
            <input type="text" id="nom" name="nom" placeholder="Nom" required>

            <label for="resume">Résumé de l'offre</label>
            <textarea id="resume" name="resume" placeholder="Courte descrition, 100 caractères maximum" maxlength=99></textarea>

            <label for="description">Description de votre offre*</label>
            <textarea id="description" name="description" placeholder="Description détaillée, 1000 caractères maximum" maxlength=999 required></textarea>
        </div>

        <div>
            <div id="choixImage">
                <label for="photosTitre">Photos de votre offre*</label>
                <p>
                    Vous pouvez insérer jusqu'à 10 photos<br>
                    Cliquez sur une image pour la supprimer
                </p>
            </div>
            <label for="photos" id="labelAjoutPhoto" class="buttonDetailOffer blueBtnOffer">Ajouter</label>
            <input type="file" id="photos" name="photos" accept="image/PNG, image/JPG, image/JPEG, image/WEBP, image/GIF" multiple>
            <div id="afficheImages"></div>
            <div id="choixCategorie">
                <label for="categorie">Catégorie de l'offre*</label>   
                <input type="radio" name="categorie" id="radioRestaurant" value=restaurant required> <label for="radioRestaurant">Restaurant</label>
                <input type="radio" name="categorie" id="radioParc" value=parc> <label for="radioParc">Parc d'attraction</label>
                <input type="radio" name="categorie" id="radioActivite" value=activite> <label for="radioActivite">Activite</label>
                <input type="radio" name="categorie" id="radioSpectacle" value=spectacle> <label for="radioSpectacle">Spectacle</label>
                <input type="radio" name="categorie" id="radioVisite" value=visite> <label for="radioVisite">Visite</label>
            </div>

            <label for="inputTag">Tags supplémentaires </label>
            <input type="text" id="inputTag" name="inputTag" placeholder="Entrez un tag décrivant votre activité / établissement">
            <button type="button" id="ajoutTag" value = ajoutTag class="buttonDetailOffer blueBtnOffer">Ajouter</button>

            <section id="sectionTag">
                <!-- Les tags ajoutés apparaîtront ici -->
            </section>

            <p>
                Vous pouvez entrer jusqu'à 6 tags <br>
                Cliquez sur un tag pour le supprimer
            </p>


        </div>
    </article>
    
    <article id="specialOffer"> <!-- id pour pouvoir le modifier separement dans le css -->

    <?php
        require_once "details/detailsPark.php";
        require_once "details/detailsVisit.php";
        require_once "details/detailsShow.php";
        require_once "details/detailsActivity.php";
        
    ?>
 