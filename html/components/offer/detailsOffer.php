

<form id="detailsOffer" action="enregOffer.php" method="post">

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

            <script>
            document.getElementById('imageInput').addEventListener('change', function (event) {
            const files = event.target.files;
            const previewContainer = document.getElementById('imagePreview');
            previewContainer.innerHTML = ''; // Clear previous previews

            Array.from(files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const imgDiv = document.createElement('div');
                    imgDiv.classList.add('image-preview');
                    imgDiv.innerHTML = `<img src="${e.target.result}" alt="Image Preview">`;
                    previewContainer.appendChild(imgDiv);
                }
                reader.readAsDataURL(file);
            });
            });

            // Code pour envoyer les images au serveur 
            // const formData = new FormData();
            // Array.from(files).forEach(file => {
            //     formData.append('images[]', file);
            // });
            // fetch('YOUR_SERVER_URL', {
            //     method: 'POST',
            //     body: formData
            // }).then(response => {
            //     console.log('Images envoyées avec succès!');
            // }).catch(error => {
            //     console.error('Erreur lors de l\'envoi:', error);
            // });
            </script>

        

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
    <?php
        require_once "./details/detailsRestaurant.php";
        //require_once "./details/detailsActivity.php";
        //require_once "./details/detailsVisit.php";
        //require_once "./details/detailsPark.php";
        //require_once "./details/detailsShow.php";
    ?>