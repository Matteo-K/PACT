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

        <form id = "formPro" action="changeAccountPro.php" method="post" enctype="multipart/form-data">
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
            
            <button type="submit" id="boutonInscription">Valider</button>
            
        </form>
    </body>
    <script src="js/validationFormInscription.js"></script>
</html>