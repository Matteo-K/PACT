

<form id="detailsOffer" action="suivant.php">

    <div>

        <label for="nom">Nom de votre offre*</label>
        <input type="text" id="nom" name="nom" placeholder="Nom" required>

        <label for="resume">Résumé de l'offre</label>
        <textarea id="resume" name="resume" placeholder="Courte descrition, 100 caractères maximum" maxlength=99></textarea>

        <label for="description">Description de votre offre*</label>
        <textarea id="description" name="description" placeholder="Description détaillée, 1000 caractères maximum" maxlength=999 required></textarea>
    
    </div>


    <div>

        <label for="photos">Photos de votre offre*</label>
        <p>Vous pouvez insérer jusqu'à 10 photos</p>
        <input type="file" id="photos" name="photos" required>


        <div id="affichePhotos">
            <input type="file" id="imageInput" accept="image/*" multiple>
            <div id="imagePreview"></div>
        </div>

        

        <div>
            <label for="categorie">Catégorie de l'offre*</label>   
            <input type="radio" name="categorie" id="radioRestaurant" value=restaurant> <label for="radioRestaurant">Restaurant</label>
            <input type="radio" name="categorie" id="radioParc" value=parc> <label for="radioParc">Parc d'attraction</label>
            <input type="radio" name="categorie" id="radioActivite" value=activite> <label for="radioActivite">Activite</label>
            <input type="radio" name="categorie" id="radioSpectacle" value=spectacle> <label for="radioSpectacle">Spectacle</label>
            <input type="radio" name="categorie" id="radioVisite" value=visite> <label for="radioVisite">Visite</label>
        </div>

        <label for="tag">Tags supplémentaires </label>
        <input type="text" id="tag" name="tag" placeholder="Entrez un tag décrivant votre activité / établissement">
        <button type="button" value = ajoutTag>Ajouter</button>
        <section>
            
        </section>
        <p>
            Cliquez sur un tag pour le supprimer <br>
            Vous pouvez entrer jusqu'à 6 tags
        </p>


    </div>

    
</form>
