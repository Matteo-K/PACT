<?php
    // Démarrer la session
    session_start();

    // Fichier de connexion à la BDD
    require_once 'db.php';

    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['idUser'])) {
        header("Location: login.php"); // Rediriger vers la page de connexion si non connecté
        exit();
    }

    // Récupérer l'ID de l'utilisateur connecté
    $userId = $_SESSION['idUser'];
?>

<!DOCTYPE html>
<html lang="en">
    <head> 
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
        <link rel="icon" href="img/logo.png" type="image/x-icon">
        <title>Modifier des informations</title>
    </head>
    <body id ="body_creation_compte" class="creation-compte">
        <aside id="asideRetour">
            <button id="retour">
                <img src="img/logo.png" alt="Logo" title="Retour page précédente"/>
                Retour
            </button>
        </aside>
        
        <h1 id="changerInfoTitre">Modifier des informations</h1>

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

        <form id = "formMember" action="chnageAccountMember.php" method="post" enctype="multipart/form-data">
            <div class="ligne1">
                <label  id="labelPrenom" for="prenomMembre">Prénom*:</label>
                <label id="labelNom" for="nomMembre">Nom*:</label>
                    
                <!-- Saisi du prénom -->
                <input type="text" placeholder="Jean" id="prenomMembre" name="prenomMembre" value="<?= isset($user['prenomMembre']) ? htmlspecialchars($user['prenomMembre']) : '' ?>" required>

                <!-- Saisi du nom -->
                <input type="text" placeholder="Dupont" id="nomMembre" name="nomMembre" value="<?= isset($user['nomMembre']) ? htmlspecialchars($user['nomMembre']) : '' ?>" required>
    
            </div>
    
            <div class="ligne1_1">
                <label id="labelPseudo" for="pseudoMembre">Pseudonyme*:</label>
                <label id="labelTelephone" for="telephoneMembre">Téléphone*:</label>
                    
                <!-- Saisi du pseudo -->
                <input type="text" placeholder="Jean29" id="pseudoMembre" name="pseudoMembre" value="<?= isset($user['pseudoMembre']) ? htmlspecialchars($user['pseudoMembre']) : '' ?>" required>

                <!-- Saisi du numéro de téléphone -->
                <input type="tel" placeholder="06 01 02 03 04" id="telephoneMembre" name="telephoneMembre" value="<?= isset($user['telephoneMembre']) ? htmlspecialchars($_POST['telephoneMembre']) : '' ?>" required>
            </div>
    
    
    
            <div class="ligne2">
                <!-- Saisi de l'adresse mail -->
                <label for="email">Adresse mail*:</label>
                <input type="email" placeholder="exemple@gmail.com" id="email" name="email" value="<?= isset($user['email']) ? htmlspecialchars($user['email']) : '' ?>" required>
            </div>


            
            <div class="ligne3">
                <!-- Saisi de l'adresse postale -->
                <label for="adresse">Adresse postale*:</label>
                <input type="text" placeholder="123 Rue de Brest" id="adresse" name="adresse" value="<?= isset($user['adresse']) ? htmlspecialchars($user['adresse']) : '' ?>" required>
            </div>


            
            <div class="ligne4"> 
                <label id="labelCode" for="code">Code postal*:</label>
                <label id="labelVille" for="ville">Ville*:</label>
                
                <!-- Saisi du code postale -->
                <input type="text" placeholder="29200" id="code" name="code" value="<?= isset($user['code']) ? htmlspecialchars($user['code']) : '' ?>" required>

                <!-- Saisi de la ville -->
                <input type="text" placeholder="Brest" id="ville" name="ville" value="<?= isset($user['ville']) ? htmlspecialchars($user['ville']) : '' ?>" required>
            </div>

            <button type="submit" id="boutonInscription">Valider</button>

        </form>
    </body>
    <script src="js/validationFormInscription.js"></script>
</html>