<?php
    // Démarrer la session
    session_start();
    
    // fichier de connexion à la BDD
    require_once "db.php";
    
    // Initialisation du tableau pour stocker les erreurs
    $errors = []; 

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["denomination"])) {
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

        $authentikator = isset($_POST['authentikator']) ? true : false; // Vérifier si la checkbox est cochée
        $longueur = strlen(trim($_POST['code_2fa'])); 
        $secret = isset($_SESSION['secret_a2f'])&& $authentikator ? $_SESSION['secret_a2f'] : null;
        $confirmationA2f = isset($_SESSION['a2f_verifier'])&& $authentikator && $longueur == 6? true : false;

        // Vérifier si la dénomination existe déjà dans la base de données
        try {
            $stmt = $conn->prepare("SELECT * FROM pact._pro WHERE denomination = ?");
            $stmt->execute([$denomination]);
            $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if ($result) {
                $errors[] = "La dénomination existe déjà.";
            }
        } 
        
        catch (Exception $e) {
            // $errors[] = "Erreur lors de la vérification de la dénomination : " . htmlspecialchars($e->getMessage());
        }



        // Vérifier si le numéro de Siren existe déjà dans la base de données
        try {
            $stmt = $conn->prepare("SELECT * FROM pact.proPrive WHERE siren = ?");
            $stmt->execute([$siren]);
            $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if ($result) {
                $errors[] = "Le numéro de SIREN existe déjà.";
            }
        } 
        
        catch (Exception $e) {
            // $errors[] = "Erreur lors de la vérification du SIREN.";
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
            // Préparer la requête d'insertion en fonction du secteur
            if ($secteur == 'public') {
                if ($authentikator) {
                    $stmt = $conn->prepare("INSERT INTO pact.proPublic (denomination, password, telephone, mail, numeroRue, rue, ville, pays, codePostal, url, secret_a2f, confirm_a2f) VALUES ('$denomination', '$hashedPassword', '$telephone', '$mail', '$numeroRue', '$rue', '$ville', '$pays', '$code', '$photo', '$secret', '$confirmationA2f')");
                    $stmt->execute();
                } else {
                    $stmt = $conn->prepare("INSERT INTO pact.proPublic (denomination, password, telephone, mail, numeroRue, rue, ville, pays, codePostal, url) VALUES ('$denomination', '$hashedPassword', '$telephone', '$mail', '$numeroRue', '$rue', '$ville', '$pays', '$code', '$photo')");
                    $stmt->execute();
                }
            } 
            
            else { 
                if ($authentikator) {
                    $stmt = $conn->prepare("INSERT INTO pact.proPrive (denomination, siren, password, telephone, mail, numeroRue, rue, ville, pays, codePostal, url, secret_a2f, confirm_a2f) VALUES ('$denomination', '$siren', '$hashedPassword', '$telephone', '$mail', '$numeroRue', '$rue', '$ville', '$pays', '$code', '$photo', '$secret', '$confirmationA2f')");
                    $stmt->execute();
                } else {
                    $stmt = $conn->prepare("INSERT INTO pact.proPrive (denomination, siren, password, telephone, mail, numeroRue, rue, ville, pays, codePostal, url) VALUES ('$denomination', '$siren', '$hashedPassword', '$telephone', '$mail', '$numeroRue', '$rue', '$ville', '$pays', '$code', '$photo')");
                    $stmt->execute();
                }
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
    <body id ="body_creation_compte" class="creation-compte">
        <aside id="asideRetour">
            <a id="retour" href="login.php">
                <img src="img/logo.png" alt="Logo" title="Retour page précédente"/>
                Retour
            </a>
        </aside>
        
        <h1 id="inscriptionTitre">Inscription professionnel</h1>

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

        <div id="messageErreur" class="messageErreur"></div>

        <form id = "formPro" action="accountPro.php" method="post" enctype="multipart/form-data">
            <div class="ligne1">
                <label for="denomination">Dénomination*:</label>
                <label for="telephone">Numéro de téléphone*:</label>
                
                <!-- Saisi de la dénomination -->
                <input type="text" placeholder="MonEntreprise" id="denomination" name="denomination" value="<?= isset($_POST['denomination']) ? htmlspecialchars($_POST['denomination']) : '' ?>" required>

                <!-- Saisi du numéro de téléphone -->
                <input type="tel" placeholder="06 01 02 03 04" id="telephone" name="telephone" value="<?= isset($_POST['telephone']) ? htmlspecialchars($_POST['telephone']) : '' ?>" required>
            </div>



            <div class="ligne2">
                <!-- Saisi de l'adresse mail -->
                <label for="email">Adresse mail*:</label>
                <input type="email" placeholder="exemple@gmail.com" id="email" name="email" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" required>
            </div>


            
            <div class="ligne3">
                <!-- Saisi de l'adresse postale -->
                <label for="adresse">Adresse postale*:</label>
                <input type="text" placeholder ="123 Rue de Brest" id="adresse" name="adresse" value="<?= isset($_POST['adresse']) ? htmlspecialchars($_POST['adresse']) : '' ?>" required>
                <br>
            </div>


            
            <div class="ligne4"> 
                <label for="code">Code postal*:</label>
                <label for="ville">Ville*:</label>
                
                <!-- Saisi du code postale -->
                <input type="text" placeholder="29200" id="code" name="code" value="<?= isset($_POST['code']) ? htmlspecialchars($_POST['code']) : '' ?>" required>

                <!-- Saisi de la ville -->
                <input type="text" placeholder="Brest" id="ville" name="ville" value="<?= isset($_POST['ville']) ? htmlspecialchars($_POST['ville']) : '' ?>" required>
            </div>

            

            <div class="ligne5">
                <div class="ligne5_1">
                    <!-- Radio bouton public -->
                    <label for="radioPublic">
                        <input type="radio" id="radioPublic" name="secteur" value="public">
                        <span class="checkmark"></span>
                        Public
                    </label>
            
                    <!-- Radio bouton privée -->
                    <label for="radioPrive">
                        <input type="radio" id="radioPrive" name="secteur" value="prive" checked>
                        <span class="checkmark"></span>
                        Privé
                    </label>
                </div>

                <div class="ligne5_2">
                    <!-- Saisi du numéro de SIREN -->
                    <label for="siren">N° SIREN*:</label>
                    <input type="text" placeholder="123 456 789" id="siren" name="siren" value="<?= isset($_POST['siren']) ? htmlspecialchars($_POST['siren']) : '' ?>">
                </div>
            </div>

            

            <div class="ligne6">
                <label for="motdepasse">Mot de passe*:</label>
                <label for="confirmer">Confirmer le mot de passe*:</label>
                
                <!-- Saisi du mot de passe -->
                <input type="password" placeholder="Mot de passe" id="motdepasse" name="motdepasse" value="<?= isset($_POST['motdepasse']) ? htmlspecialchars($_POST['motdepasse']) : '' ?>" required>

                <!-- Saisi de confirmation du mot de passe -->
                <input type="password" placeholder="Confirmer le mot de passe" id="confirmer" name="confirmer" value="<?= isset($_POST['confirmer']) ? htmlspecialchars($_POST['confirmer']) : '' ?>"required>

                <p>Le mot de passe doit contenit au moins 10 caractères dont une majuscule, une minuscule et un chiffre.</p>
            </div>

            <div class="authentikator">
                <!-- Checkbox de A2F -->
                <label for="authentikator">
                    <input type="checkbox" id="authentikator" name="authentikator" hidden/>
                    <span class="checkmark" id="qrcode"></span>
                    J’utilise l'authentification à deux facteurs
                </label>
                <div  id="divAuthent">
                    <label>Entrez le code à 6 chiffres :</label>
                    <input type="text" id="code_2fa" name="code_2fa" maxlength="6">
                    <div id="status"></div>
                </div>
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

            <h2>Vous avez déjà un compte ? <a id="lienConnexion" href="login.php">Se connecter</a></h2>
            <script src="authentikator/authentikator.js"></script>
        </form>
    </body>
    <script src="js/validationFormInscription.js"></script>
    <script src="js/setColor.js"></script>
</html>