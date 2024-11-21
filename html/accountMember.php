<?php
    // Démarrer la session
    session_start();
    
    // fichier de connexion à la BDD
    require_once "db.php";
    
    // Initialisation du tableau pour stocker les erreurs
    $errors = []; 

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
            $stmt = $conn->prepare("SELECT COUNT(*) FROM pact.proPublic WHERE pseudo = ? UNION SELECT COUNT(*) FROM pact.proPrive WHERE denomination = ?");
            $stmt->execute([$denomination, $denomination]);
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                $errors[] = "Le pseudo existe déjà.";
            }
        } 
        
        catch (Exception $e) {
            // $errors[] = "Erreur lors de la vérification: " . htmlspecialchars($e->getMessage());
        }


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
    <body id ="body_creation_compte" class="creation-compte">
        <aside id="asideRetour">
            <button id="retour">
                <img src="img/logo.png" alt="Logo" title="Retour page précédente"/>
                Retour
            </button>
        </aside>
        
        <h1 id="inscriptionTitre">Inscription membre</h1>

        <div id="messageErreur" class="messageErreur"></div>

        <form id = "formMember" action="accountMember.php" method="post" enctype="multipart/form-data">
            <div class="ligne1">
                <label for="nomMembre">Nom*:</label>
                <input type="text" placeholder = "Jean" id="nomMembre" name="nomMembre" required>
                <label for="prenomMembre">Prénom*:</label>
                
                <!-- Saisi du nom -->

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
                <input type="text" placeholder = "123 Rue de Brest" id="adresse" name="adresse" required>
                <br>
            </div>


            
            <div class="ligne4"> 
                <label for="code">Code postal*:</label>
                <label for="ville">Ville*:</label>
                
                <!-- Saisi du code postale -->
                <input type="text" placeholder = "29200" id="code" name="code" required>

                <!-- Saisi de la ville -->
                <input type="text" placeholder = "Brest" id="ville" name="ville" required>
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
            
            <button type="submit" id="boutonInscription">S'inscrire</button>

            <h2>Vous avez déjà un compte ? <a id="lienConnexion" href="login.php">Se connecter</a></h2>
        </form>
    </body>
    <script src="js/validationFormInscription.js"></script>
</html>