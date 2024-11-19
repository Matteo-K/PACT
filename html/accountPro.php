<?php
    // Démarrer la session
    session_start();
    
    // fichier de connexion à la BDD
    require_once "db.php";
    
    // Initialisation du tableau pour stocker les erreurs
    $errors = []; 

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupérer les données du formulaire
        $denomination = trim($_POST['denomination']);
        $telephone = trim($_POST['telephone']);
        $mail = trim($_POST['email']);
        $adresse = trim($_POST['adresse']);
        $code = trim($_POST['code']);
        $ville = trim($_POST['ville']);
        $secteur = $_POST['secteur'];
        $siren = trim($_POST['siren']);
        $motdepasse = $_POST['motdepasse'];

        // Séparer le numéro et le nom de la rue
        $adresseExplode = explode(' ', $adresse, 2); 
        $numeroRue = isset($adresseExplode[0]) ? $adresseExplode[0] : '';
        $rue = isset($adresseExplode[1]) ? $adresseExplode[1] : '';
        $pays = "France";
        $photo = "./img/profile_picture/default.svg";

        // Hashage du mot de passe
        $hashedPassword = password_hash($motdepasse, PASSWORD_DEFAULT);

        // Vérifier si la dénomination existe déjà dans la base de données
        try {
            $stmt = $conn->prepare("SELECT COUNT(*) FROM pact.proPublic WHERE denomination = ? UNION SELECT COUNT(*) FROM pact.proPrive WHERE denomination = ?");
            $stmt->execute([$denomination, $denomination]);
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                $errors[] = "La dénomination existe déjà.";
            }
        } 
        
        catch (Exception $e) {
            // $errors[] = "Erreur lors de la vérification: " . htmlspecialchars($e->getMessage());
        }



        // Vérifier si le numéro de SIREN existe déjà dans la base de données
        try {
            if ($secteur == 'prive') {
                $stmt = $conn->prepare("SELECT COUNT(*) FROM pact.proPrive WHERE siren = ?");
                $stmt->execute([$siren]);
                if ($stmt->fetchColumn() > 0) {
                    $errors[] = "Le numéro de SIREN existe déjà.";
                }
            }
        } 
        
        catch (Exception $e) {
            // $errors[] = "Erreur lors de la vérification: " . htmlspecialchars($e->getMessage());
        }



        // Vérifier si l'adresse existe déjà dans la base de données
        // try {
        //     $stmt = $conn->prepare("SELECT COUNT(*) FROM pact.proPublic WHERE numeroRue = ? AND rue = ? AND codePostal = ? AND ville = ? 
        //                             UNION SELECT COUNT(*) FROM pact.proPrive WHERE numeroRue = ? AND rue = ? AND codePostal = ? AND ville = ?");
        //     $stmt->execute([$numeroRue, $rue, $code, $ville, $numeroRue, $rue, $code, $ville]);
        //     $addressCount = $stmt->fetchColumn();

        //     if ($addressCount > 0) {
        //         $errors[] = "L'adresse postale existe déjà.";
        //     }
        // } 
        
        // catch (Exception $e) {
            // $errors[] = "Erreur lors de la vérification: " . htmlspecialchars($e->getMessage());
        // }



        // Si des erreurs ont été trouvées, ne pas continuer avec l'insertion
        if(empty($errors)) {
            // Préparer la requête d'insertion en fonction du secteur
            if ($secteur == 'public') {
                $stmt = $conn->prepare("INSERT INTO pact.proPublic (denomination, password, telephone, mail, numeroRue, rue, ville, pays, codePostal, url) VALUES ('$denomination', '$hashedPassword', '$telephone', '$mail', '$numeroRue', '$rue', '$ville', '$pays', '$code', '$photo')");
                $stmt->execute();
            } 
            
            else { 
                $stmt = $conn->prepare("INSERT INTO pact.proPrive (denomination, siren, password, telephone, mail, numeroRue, rue, ville, pays, codePostal, url) VALUES ('$denomination', '$siren', '$hashedPassword', '$telephone', '$mail', '$numeroRue', '$rue', '$ville', '$pays', '$code', '$photo')");
                $stmt->execute();
            }

            // Redirection vers une page de succès
            header('Location: login.php');
            exit;
        }


        
    }
?>


<!DOCTYPE html>
<html lang="en">
    <head> 
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
        <link rel="icon" href="img/logo.png" type="image/x-icon">
        <title>Créer un compte</title>
    </head>
    <body id ="body_creation_compte_pro" class="creation-compte">
        <aside id="asideRetour">
            <button id="retour">
                <img src="img/logo.png" alt="Logo" title="Retour page précédente"/>
                Retour
            </button>
        </aside>
        
        <h1 id="inscriptionTitre">Inscription professionnel</h1>

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

        <form id = "formPro" action="accountPro.php" method="post" enctype="multipart/form-data">
            <div class="ligne1">
                <label for="denomination">Dénomination*:</label>
                <label for="telephone">Numéro de téléphone*:</label>
                
                <!-- Saisi de la dénomination -->
                <input type="text" placeholder = "MonEntreprise" id="denomination" name="denomination" required>

                <!-- Saisi du numéro de téléphone -->
                <input type="tel" placeholder = "06 01 02 03 04" id = "telephone" name="telephone" required>
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

            

            <div class="ligne5">
                <div class="ligne5_1">
                    <!-- Radio bouton public -->
                    <input type="radio" id="radioPublic" name="secteur" value="public">
                    <label for="public">Public</label>
            
                    <!-- Radio bouton privée -->
                    <input type="radio" id="radioPrive" name="secteur" value="prive" checked>
                    <label for="prive">Privé</label>
                </div>

                <div class="ligne5_2">
                    <!-- Saisi du numéro de SIREN -->
                    <label for="siren">N° SIREN*:</label>
                    <input type="text" placeholder = "123 456 789" id="siren" name="siren" required>
                </div>
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
            
            
            <button onclick = "validationFormPro()" id="boutonInscriptionPro">S'inscrire</button>

            <h2>Vous avez déjà un compte ? <a id="lienConnexion" href="login.php">Se connecter</a></h2>
        </form>
    </body>
    <script src="js/validationFormInscription.js"></script>
</html>