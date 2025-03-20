<?php
    // Démarrer la session
    session_start();
    
    // fichier de connexion à la BDD
    require_once "db.php";
    
    // Initialisation du tableau pour stocker les erreurs
    $errors = []; 

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["nomMembre"])) {
        // Récupérer les données du formulaire
        $nom = trim($_POST['nomMembre']);
        $prenom = trim($_POST['prenomMembre']);
        $pseudo = trim($_POST['pseudoMembre']);
        $telephone = trim($_POST['telephoneMembre']);
        $mail = trim($_POST['email']);
        $adresse = trim($_POST['adresse']);
        $code = trim($_POST['code']);
        $ville = trim($_POST['ville']);
        $motdepasse = $_POST['motdepasse'];

        // Séparer le numéro et le nom de la rue
        $adresseExplode = explode(' ', $adresse, 2); 
        $numeroRue = isset($adresseExplode[0]) ? $adresseExplode[0] : '';
        $rue = isset($adresseExplode[1]) ? $adresseExplode[1] : '';
        $pays = "France";
        $photo = "./img/profile_picture/default.svg";

        // Hashage du mot de passe
        $hashedPassword = password_hash($motdepasse, PASSWORD_DEFAULT);



        // Vérifier si le pseudo existe déjà dans la base de données
        try {
            $stmt = $conn->prepare("SELECT * FROM pact.membre WHERE pseudo = ?");
            $stmt->execute([$pseudo]);
            $result=$stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($result) {
                $errors[] = "Le pseudo existe déjà.";
            }
        }
        
        catch (Exception $e) {
            // $errors[] = "Erreur lors de la vérification du pseudo : " . htmlspecialchars($e->getMessage());
        }
        


        // Vérifier si l'adresse mail existe déjà dans la base de données
        try {
            $stmt = $conn->prepare("SELECT * FROM pact._nonadmin WHERE mail = ?");
            $stmt->execute([$mail]);
            $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if ($result) {
                $errors[] = "L'adresse mail existe déjà.";
            }
        } 
        
        catch (Exception $e) {
            // $errors[] = "Erreur lors de la vérification de l'adresse mail : " . htmlspecialchars($e->getMessage());
        }



        // Si des erreurs ont été trouvées, ne pas continuer avec l'insertion
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
        }



        // Si des erreurs ont été trouvées, ne pas continuer avec l'insertion
        if(empty($errors)) {
            // Préparer la requête d'insertion
            $stmt = $conn->prepare("INSERT INTO pact.membre (pseudo, nom, prenom, password, numeroRue, rue, ville, pays, codePostal, telephone, mail, url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            // Exécuter la requête en passant les paramètres
            $stmt->execute([$pseudo, $nom, $prenom, $hashedPassword, $numeroRue, $rue, $ville, $pays, $code, $telephone, $mail, $photo]);

            // Redirection vers une page de succès
            header('Location: login.php');
            exit;
        }
    }
?>

<!DOCTYPE html>
<html lang="fr">
    <head> 
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
        <link rel="icon" href="img/logo.png" type="image/x-icon">
        <title>Créer un compte</title>
        <script src="authentikator/authentikator.js"></script>
    </head>
    <body id ="body_creation_compte" class="creation-compte">
        <aside id="asideRetour">
            <a id="retour" href="login.php">
                <img src="img/logo.png" alt="Logo" title="Retour page précédente"/>
                Retour
            </a>
        </aside>
        
        <h1 id="inscriptionTitre">Inscription membre</h1>

        <div id="messageErreur" class="messageErreur"></div>

        <?php
            if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])) {
                echo '<div id="messageErreur" class="messageErreur">';
                
                foreach ($_SESSION['errors'] as $error) {
                    echo "<p>$error</p>";
                }
                
                echo '</div>';
                unset($_SESSION['errors']);
            }
        ?>

        <form id = "formMember" action="accountMember.php" method="post" enctype="multipart/form-data">
            <div class="ligne1">
                <label  id="labelPrenom" for="prenomMembre">Prénom*:</label>
                <label id="labelNom" for="nomMembre">Nom*:</label>
                    
                <!-- Saisi du prénom -->
                <input type="text" placeholder="Jean" id="prenomMembre" name="prenomMembre" value="<?= isset($_POST['prenomMembre']) ? htmlspecialchars($_POST['prenomMembre']) : '' ?>" required>

                <!-- Saisi du nom -->
                <input type="text" placeholder="Dupont" id="nomMembre" name="nomMembre" value="<?= isset($_POST['nomMembre']) ? htmlspecialchars($_POST['nomMembre']) : '' ?>" required>
    
            </div>
    
            <div class="ligne1_1">
                <label id="labelPseudo" for="pseudoMembre">Pseudonyme*:</label>
                <label id="labelTelephone" for="telephoneMembre">Téléphone*:</label>
                    
                <!-- Saisi du pseudo -->
                <input type="text" placeholder="Jean29" id="pseudoMembre" name="pseudoMembre" value="<?= isset($_POST['pseudoMembre']) ? htmlspecialchars($_POST['pseudoMembre']) : '' ?>" required>

                <!-- Saisi du numéro de téléphone -->
                <input type="tel" placeholder="06 01 02 03 04" id="telephoneMembre" name="telephoneMembre" value="<?= isset($_POST['telephoneMembre']) ? htmlspecialchars($_POST['telephoneMembre']) : '' ?>" required>
            </div>
    
    
    
            <div class="ligne2">
                <!-- Saisi de l'adresse mail -->
                <label for="email">Adresse mail*:</label>
                <input type="email" placeholder="exemple@gmail.com" id="email" name="email" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" required>
            </div>


            
            <div class="ligne3">
                <!-- Saisi de l'adresse postale -->
                <label for="adresse">Adresse postale*:</label>
                <input type="text" placeholder="123 Rue de Brest" id="adresse" name="adresse" value="<?= isset($_POST['adresse']) ? htmlspecialchars($_POST['adresse']) : '' ?>" required>
            </div>


            
            <div class="ligne4"> 
                <label id="labelCode" for="code">Code postal*:</label>
                <label id="labelVille" for="ville">Ville*:</label>
                
                <!-- Saisi du code postale -->
                <input type="text" placeholder="29200" id="code" name="code" value="<?= isset($_POST['code']) ? htmlspecialchars($_POST['code']) : '' ?>" required>

                <!-- Saisi de la ville -->
                <input type="text" placeholder="Brest" id="ville" name="ville" value="<?= isset($_POST['ville']) ? htmlspecialchars($_POST['ville']) : '' ?>" required>
            </div>

            

            <div class="ligne6">
                <label id="labelMotdepasse" for="motdepasse">Mot de passe*:</label>
                <label id="labelConfirmer" for="confirmer">Confirmer le mot de passe*:</label>
                
                <!-- Saisi du mot de passe -->
                <input type="password" placeholder="Mot de passe" id="motdepasse" name="motdepasse" value="<?= isset($_POST['motdepasse']) ? htmlspecialchars($_POST['motdepasse']) : '' ?>" required>

                <!-- Saisi de confirmation du mot de passe -->
                <input type="password" placeholder="Confirmer le mot de passe" id="confirmer" name="confirmer" value="<?= isset($_POST['confirmer']) ? htmlspecialchars($_POST['confirmer']) : '' ?>" required>

                <p id="conditionMotdepasse">Le mot de passe doit contenit au moins 10 caractères dont une majuscule, une minuscule et un chiffre.</p>
            </div>

            <div class="authentikator">
                <!-- Checkbox de A2F -->
                <label for="authentikator">
                    <input type="checkbox" id="authentikator" name="authentikator" hidden/>
                    <span class="checkmark" id="qrcode"></span>
                    J’utilise l'authentification à deux facteurs
                </label>
                <div  id="divAuthent"></div>
            </div>

            <div class="ligne7">
                <!-- Checkbox des CGU -->
                <label for="cgu">
                    <input type="checkbox" id="cgu" name="cgu" value="cgu" />
                    <span class="checkmark"></span>
                    J’accepte les <a id="lienCGU" href= "cgu.php" target="_blank">conditions générales d’utilisation</a> de la PACT
                </label>
            </div>
            
            <button type="submit" id="boutonInscription">S'inscrire</button>

            <h2 id="dejauncompte">Vous avez déjà un compte ? <a id="lienConnexion" href="login.php">Se connecter</a></h2>
        </form>
    </body>
    <script src="js/validationFormInscription.js"></script>
    <script src="js/setColor.js"></script>
</html>