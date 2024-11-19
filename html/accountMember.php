<!DOCTYPE html>
<html lang="en">
    <head> 
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
        <link rel="icon" href="img/logo.png" type="image/x-icon">
        <title>Créer un compte</title>
    </head>
    <body id ="body_creation_compte_membre" class="creation-compte">
        <aside id="asideRetour">
            <button id="retour">
                <img src="img/logo.png" alt="Logo" title="Retour page précédente"/>
                Retour
            </button>
        </aside>
        
        <h1 id="inscriptionTitre">Inscription membre</h1>

        <?php
            if (!empty($errors)) {
                // Afficher les erreurs à l'utilisateur
                echo "<div class='messageErreur'>";
                foreach ($errors as $error) {
                    echo "<li>" . htmlspecialchars($error) . "</li>";
                }
                echo "</div>";
            }
        ?>

        <form id = "formMember" action="accountMember.php" method="post" enctype="multipart/form-data">
            <div class="ligne1">
                <label for="nomMembre">Nom*:</label>
                <label for="prenomMembre">Prénom*:</label>
                
                <!-- Saisi du nom -->
                <input type="text" placeholder = "Jean" id="nomMembre" name="nomMembre" required>

                <!-- Saisi du prénom -->
                <input type="text" placeholder = "Dupont" id = "prenomMembre" name="prenomMembre" required>
            </div>

            <div class="ligne1_1">
                <label for="pseudoMembre">Pseudonyme*:</label>
                <label for="telephoneMembre">Téléphone*:</label>
                
                <!-- Saisi du pseudo -->
                <input type="text" placeholder = "Jean29" id="pseudoMembre" name="pseudoMembre" required>

                <!-- Saisi du numéro de téléphone -->
                <input type="tel" placeholder = "06 01 02 03 04" id = "telephoneMembre" name="telephoneMembre" required>
            </div>



            <div class="ligne2">
                <!-- Saisi de l'adresse mail -->
                <label for="email">Adresse mail*:</label>
                <input type="email" placeholder = "exemple@gmail.com" id="email" name="email" required>
            </div>


            
            <div class="ligne3">
                <!-- Saisi de l'adresse postale -->
                <label for="adresse">Adresse postale*:</label>
                <input type="text" placeholder = "123 Rue de Paris" id="adresse" name="adresse" required>
                <br>
            </div>


            
            <div class="ligne4"> 
                <label for="code">Code postal*:</label>
                <label for="ville">Ville*:</label>
                
                <!-- Saisi du code postale -->
                <input type="text" placeholder = "75000" id="code" name="code" required>

                <!-- Saisi de la ville -->
                <input type="text" placeholder = "Paris" id="ville" name="ville" required>
            </div>


            <div class="ligne6">
                <label for="motdepasse">Mot de passe*:</label>
                <label for="confirmer">Confirmer le mot de passe*:</label>
                
                <!-- Saisi du mot de passe -->
                <input type="password" placeholder = "Mot de passe" id="motdepasse" name="motdepasse" required>

                <!-- Saisi de confirmation du mot de passe -->
                <input type="password" placeholder = "Confirmer le mot de passe" id="confirmer" name="confirmer" required>

                <p>Le mot de passe doit contenit au moins 10 caractères dont une majuscule, une minuscule et un chiffre.</p>
            </div>

            

            <div class="ligne7">
                <!-- Checkbox des CGU -->
                <input type="checkbox" id="cgu" name="cgu" value="cgu" />
                <label for="cgu">J’accepte les <a id="lienCGU" href= "cgu.html">conditions générales d’utilisation</a> de la PACT</label>
            </div>
            
            
            <button onclick = "validationForm()" id="boutonInscriptionMembre">S'inscrire</button>

            <h2>Vous avez déjà un compte ? <a id="lienConnexion" href="login.php">Se connecter</a></h2>
        </form>
    </body>
    <script src="js/validationFormInscription.js"></script>
</html>