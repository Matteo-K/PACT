<?php 
    // Démarrer la session
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_creation_compte_pro.css">
    <link rel="icon" href="logo.png" type="image/x-icon">
    <title>Créer un compte</title>
</head>
<body id ="body_creation_compte_pro" class="creation-compte">
    <aside id="asideRetour">
        <button id="retour">
            <img src="logo.png" alt="Logo" title="Retour page précédente"/>
            Retour
        </button>
    </aside>

    <h1 id="inscriptionTitre">Inscription</h1>
    <form id = "formPro" action="creation_compte_pro.php" method="post" enctype="multipart/form-data">
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
            <!-- Radio bouton public -->
            <input type="radio" id="public" name="secteur" value="public">
            <label for="public">Public</label>
    
            <!-- Radio bouton privée -->
            <input type="radio" id="prive" name="secteur" value="prive" checked>
            <label for="prive">Privé</label>

            <div class="ligne5_1">
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


        <button onclick = "validationFormInscription()" id="boutonInscriptionPro">S'inscrire</button>
        <script src = validationFormInscription.js></script>

        <h2>Vous avez déjà un compte ? <a id="lienConnexion" href="connexion.php">Se connecter</a></h2>
    </form>


    <?php
        // Configuration de la base de données
        $host = 'the-void.ventsdouest.dev'; // Hôte de ta base de données
        $db = 'sae'; // Nom de la base de données
        $user = 'sae'; // Nom d'utilisateur
        $pass = 'digital-costaRd-sc0uts'; // Mot de passe

        try {
            // Connexion à la base de données
            $pdo = new PDO("pgsql:host=$host;dbname=$db;charset=utf8", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
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


                // Hashage du mot de passe
                $hashedPassword = password_hash($motdepasse, PASSWORD_DEFAULT);

                // Préparer la requête d'insertion en fonction du secteur

                // pro public
                if ($secteur == 'public') {
                    $stmt = $pdo->prepare("INSERT INTO proPublic (denomination, password, telephone, mail, numeroRue, rue, ville, pays, codePostal, url) VALUES (?, ?, ?, ?, ?, ?, 'France', ?, 'img/profile_picture/default.svg')");
                    $stmt->execute([$denomination, $hashedPassword, $telephone, $mail, $adresse, $code, $ville]);
                } 

                // pro privé
                else { 
                    $stmt = $pdo->prepare("INSERT INTO proPrive (denomination, siren, password, telephone, mail, numeroRue, rue, ville, pays, codePostal, url) VALUES (?, ?, ?, ?, ?, ?, ?, 'France', ?, 'img/profile_picture/default.svg')");
                    $stmt->execute([$denomination, $siren, $hashedPassword, $telephone, $mail, $adresse, $code, $ville, ]);
                }
                
                $stmt->execute([$denomination, $telephone, $mail, $adresse, $code, $ville, $siren, $hashedPassword]);

                // Redirection vers une page de succès
                header('Location: success.html');
                exit;
            }
        } catch (PDOException $e) {
            echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
        }
    ?>
</body>
</html>