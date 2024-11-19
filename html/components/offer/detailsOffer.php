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
            <div id="choixImage">
                <label>Photos de votre offre*</label>
                <p>
                    Vous pouvez insérer jusqu'à 10 photos<br>
                    Cliquez sur une image pour la supprimer
                </p>
            </div>
            <label for="ajoutPhoto" class="buttonDetailOffer blueBtnOffer">Ajouter</label>
            <input type="file" id="ajoutPhoto" name="ajoutPhoto[]" accept="image/PNG, image/JPG, image/JPEG, image/WEBP, image/GIF" method="post" multiple required>
            <div id="afficheImages"></div>

            <div id="choixCategorie">
                <label>Catégorie de l'offre*</label>   
                <input type="radio" name="categorie" id="radioRestaurant" value="restaurant" required> <label for="radioRestaurant">Restaurant</label>
                <input type="radio" name="categorie" id="radioParc" value="parc"> <label for="radioParc">Parc d'attraction</label>
                <input type="radio" name="categorie" id="radioActivite" value="activite"> <label for="radioActivite" >Activite</label>
                <input type="radio" name="categorie" id="radioSpectacle" value="spectacle"> <label for="radioSpectacle">Spectacle</label>
                <input type="radio" name="categorie" id="radioVisite" value="visite"> <label for="radioVisite">Visite</label>
            </div>

            <label for="inputTag">Tags supplémentaires </label>
            <select name="tags" id="tags" multiple>
                <!-- Tags généraux -->
                <optgroup label="Général">
                    <option value="local">Local</option>
                    <option value="international">International</option>
                    <option value="insolite">Insolite</option>
                    <option value="populaire">Populaire</option>
                    <option value="exclusif">Exclusif</option>
                    <option value="authentique">Authentique</option>
                </optgroup>

                <!-- Ambiance -->
                <optgroup label="Ambiance">
                    <option value="romantique">Romantique</option>
                    <option value="festif">Festif</option>
                    <option value="familial">Familial</option>
                    <option value="calme">Calme</option>
                    <option value="traditionnel">Traditionnel</option>
                    <option value="contemporain">Contemporain</option>
                    <option value="convivial">Convivial</option>
                </optgroup>

                <!-- Type de lieu -->
                <optgroup label="Lieu">
                    <option value="en-exterieur">En extérieur</option>
                    <option value="en-interieur">En intérieur</option>
                    <option value="urbain">Urbain</option>
                    <option value="rural">Rural</option>
                    <option value="bord-de-mer">En bord de mer</option>
                    <option value="montagne">Montagne</option>
                    <option value="patrimonial">Patrimonial</option>
                </optgroup>

                <!-- Thématique ou époque -->
                <optgroup label="Thématique">
                    <option value="historique">Historique</option>
                    <option value="culturel">Culturel</option>
                    <option value="moderne">Moderne</option>
                    <option value="medieval">Médiéval</option>
                    <option value="naturel">Naturel</option>
                    <option value="industriel">Industriel</option>
                    <option value="feerique">Féérique</option>
                </optgroup>

                <!-- Temps et horaires -->
                <optgroup label="Temps et horaires">
                    <option value="nocturne">Nocturne</option>
                    <option value="diurne">Diurne</option>
                    <option value="weekend">En continu</option>
                    <option value="weekend">Week-end</option>
                    <option value="vacances-scolaires">Vacances scolaires</option>
                    <option value="estival">Estival</option>
                    <option value="hivernal">Hivernal</option>
                    <option value="saisonnier">Saisonnier</option>
                </optgroup>

                <!-- Public -->
                <optgroup label="Public">
                    <option value="couple">Couple</option>
                    <option value="enfants">Enfants</option>
                    <option value="adolescents">Adolescents</option>
                    <option value="seniors">Seniors</option>
                    <option value="groupes">Groupes</option>
                    <option value="solo">Solo</option>
                    <option value="sensations">Amateurs de sensations</option>
                    <option value="pmr">Accessible PMR</option>
                </optgroup>

                <!-- Restauration -->
                <optgroup label="Restauration">
                    <option value="cuisine-locale">Cuisine locale</option>
                    <option value="cuisine-gastronomique">Cuisine gastronomique</option>
                    <option value="street-food">Street food</option>
                    <option value="brunch">Brunch</option>
                    <option value="vegetarien">Végétarien</option>
                    <option value="vegan">Vegan</option>
                    <option value="bistronomique">Bistronomique</option>
                    <option value="a-theme">À thème</option>
                </optgroup>

                <!-- Spectacles -->
                <optgroup label="Spectacles">
                    <option value="theatre">Théâtre</option>
                    <option value="musique-live">Musique live</option>
                    <option value="cirque">Cirque</option>
                    <option value="comedie">Comédie</option>
                    <option value="danse">Danse</option>
                    <option value="magie">Magie</option>
                    <option value="stand-up">Stand-up</option>
                </optgroup>

                <!-- Activités -->
                <optgroup label="Activités">
                    <option value="sport-nautique">Sport nautique</option>
                    <option value="randonnée">Randonnée</option>
                    <option value="atelier-creatif">Atelier créatif</option>
                    <option value="activite-immersive">Activité immersive</option>
                    <option value="escape-game">Escape game</option>
                    <option value="jeux-equipe">Jeux d’équipe</option>
                    <option value="decouverte-sportive">Découverte sportive</option>
                </optgroup>

                <!-- Parcs d'attraction -->
                <optgroup label="Parcs d'attraction">
                    <option value="sensations-fortes">Sensations fortes</option>
                    <option value="familial">Familial</option>
                    <option value="animaux">Animaux</option>
                    <option value="spectacles-inclus">Spectacles inclus</option>
                    <option value="thematique">Thématique</option>
                    <option value="aquatique">Aquatique</option>
                    <option value="interactif">Interactif</option>
                </optgroup>

                <!-- Visites -->
                <optgroup label="Visites">
                    <option value="guidee">Guidée</option>
                    <option value="autonome">Autonome</option>
                    <option value="musee">Musée</option>
                    <option value="lieu-insolite">Lieu insolite</option>
                    <option value="monument">Monument</option>
                    <option value="panoramique">Panoramique</option>
                    <option value="educative">Éducative</option>
                </optgroup>
            </select>

            
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
        $source = "details/";
        require_once $source . "detailsRestaurant.php";
        require_once $source . "detailsPark.php";
        require_once $source . "detailsVisit.php";
        require_once $source . "detailsShow.php";
        require_once $source . "detailsActivity.php";
        
    ?>

    </article>


    