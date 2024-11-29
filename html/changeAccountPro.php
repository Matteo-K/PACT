<?php
    // Démarrer la session
    session_start();

    // Connexion à la base de données
    require_once 'db.php';

    var_dump($_SESSION['idUser']);

    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['idUser'])) {
        // L'utilisateur n'est pas connecté, redirigez-le vers la page de connexion
        header("Location: login.php");
        exit();
    }

    // Récupérer l'ID de l'utilisateur connecté
    $userId = $_SESSION['idUser'];


    try {
        // Tenter de récupérer les informations de l'utilisateur dans la base de données
        $stmt = $conn->prepare("SELECT * FROM pact._pro WHERE idU = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifier si l'utilisateur existe dans la base de données
        if (!$user) {
            $_SESSION['errors'][] = "Utilisateur introuvable.";
            header("Location: login.php");
            exit();
        }
    } 
    
    catch (PDOException $e) {
        // Si une erreur de connexion à la BDD se produit, affichez un message et redirigez
        $_SESSION['errors'][] = "Erreur de connexion à la base de données: " . $e->getMessage();
        header("Location: login.php");  // Vous pouvez rediriger vers une autre page de votre choix en cas d'erreur
        exit();
    }
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
                <input type="text" placeholder="MonEntreprise" id="denomination" name="denomination" value="<?= isset($user['denomination']) ? htmlspecialchars($user['denomination']) : '' ?>" required>

                <!-- Saisi du numéro de téléphone -->
                <input type="tel" placeholder="06 01 02 03 04" id="telephone" name="telephone" value="<?= isset($user['telephone']) ? htmlspecialchars($user['telephone']) : '' ?>" required>
            </div>



            <div class="ligne2">
                <!-- Saisi de l'adresse mail -->
                <label for="email">Adresse mail*:</label>
                <input type="email" placeholder="exemple@gmail.com" id="email" name="email" value="<?= isset($user['mail']) ? htmlspecialchars($user['mail']) : '' ?>" required>
            </div>


            
            <div class="ligne3">
                <!-- Saisi de l'adresse postale -->
                <label for="adresse">Adresse postale*:</label>
                <input type="text" placeholder ="123 Rue de Brest" id="adresse" name="adresse" value="<?= isset($user['numeroRue']) && isset($user['rue']) ? htmlspecialchars($user['numeroRue']) . '' . htmlspecialchars($user['rue']): '' ?>" required>
                <br>
            </div>


            
            <div class="ligne4"> 
                <label for="code">Code postal*:</label>
                <label for="ville">Ville*:</label>
                
                <!-- Saisi du code postale -->
                <input type="text" placeholder="29200" id="code" name="code" value="<?= isset($user['codePostal']) ? htmlspecialchars($user['codePostal']) : '' ?>" required>

                <!-- Saisi de la ville -->
                <input type="text" placeholder="Brest" id="ville" name="ville" value="<?= isset($user['ville']) ? htmlspecialchars($user['ville']) : '' ?>" required>
            </div>
            
            <button type="submit" id="boutonInscription">Valider</button>
            
        </form>
    </body>
    <script src="js/validationFormInscription.js"></script>
</html>