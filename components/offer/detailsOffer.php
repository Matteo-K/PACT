
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="styleCreationOffre.css"  rel="stylesheet">
    <title>Document</title>
</head>
<body>
    
    <form action="suivant.php">

        <div>

            <label for="nom">Nom de votre offre</label>
            <input type="text" id="nom" name="nom" placeholder="Nom" required>

            <label for="description">Résumé de l'offre</label>
            <input type="textarea" id="description" name="nom" placeholder="Courte descrition, 255 caractères maximum" required>

            <label for="description">Description de votre offre</label>
            <input type="textarea" id="description" name="nom" placeholder="Description détaillée" required>


        
        </div>


        <div>

            <label for="photos">Photos de votre offre</label>
            <p>Vous pouvez insérer jusqu'à 10 photos</p>
            <input type="file" id="photos" name="photos" required>

            <label for="nom">Catégorie</label>   
            <button type="button" value = restaurant>Restaurant</button>
            <button type="button" value = parc>Parc d'attraction</button>
            <button type="button" value = activite>Activité</button>
            <button type="button" value = spectacle>Spectacle</button>
            <button type="button" value = visite>Visite</button>


            <label for="tag">Tags : </label>
            <input type="text" id="description" name="nom" required>
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
</body>
</html>