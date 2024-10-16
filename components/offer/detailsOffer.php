

<form action="suivant.php">

    <div>

        <label for="nom">Nom de votre offre</label>
        <input type="text" id="nom" name="nom" placeholder="Nom" required>

        <label for="resume">Résumé de l'offre</label>
        <input type="textarea" id="resume" name="resume" placeholder="Courte descrition, 255 caractères maximum" required>

        <label for="description">Description de votre offre</label>
        <input type="textarea" id="description" name="description" placeholder="Description détaillée" required>


    
    </div>


    <div>

        <label for="photos">Photos de votre offre</label>
        <p>Vous pouvez insérer jusqu'à 10 photos</p>
        <input type="file" id="photos" name="photos" required>

        <div>
            <label for="nom">Catégorie</label>   
            <button type="button" value = restaurant>Restaurant</button>
            <button type="button" value = parc>Parc d'attraction</button>
            <button type="button" value = activite>Activité</button>
            <button type="button" value = spectacle>Spectacle</button>
            <button type="button" value = visite>Visite</button>
        </div>

        <label for="tag">Tags : </label>
        <input type="text" id="tag" name="tag">
        <button type="button" value = ajoutTag>Ajouter</button>
        <section>
            
        </section>
        <p>
            Cliquez sur un tag pour le supprimer <br>
            Vous pouvez entrer jusqu'à 6 tags
        </p>

        <label for="tag">Photos de votre offre</label>
        <input type="file" id="photos" name="photos" required>

    </div>

    
</form>
