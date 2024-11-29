<?php
    session_start();

    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['idU'])) {
        header("Location: login.php");
        exit();
    }

    require_once 'db.php';

    // Récupérer l'ID de l'utilisateur connecté
    $userId = $_SESSION['idU'];

    // Vérifier si les données ont été envoyées via le formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupérer et sécuriser les données du formulaire
        $denomination = isset($_POST['denomination']) ? htmlspecialchars($_POST['denomination']) : '';
        $telephone = isset($_POST['telephone']) ? htmlspecialchars($_POST['telephone']) : '';
        $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
        $adresse = isset($_POST['adresse']) ? htmlspecialchars($_POST['adresse']) : '';
        $code = isset($_POST['code']) ? htmlspecialchars($_POST['code']) : '';
        $ville = isset($_POST['ville']) ? htmlspecialchars($_POST['ville']) : '';

        // Vérifier si toutes les informations obligatoires sont présentes
        if (empty($denomination) || empty($telephone) || empty($email) || empty($adresse) || empty($code) || empty($ville)) {
            $_SESSION['errors'][] = "Tous les champs sont obligatoires.";
            header("Location: changeAccountPro.php");
            exit();
        }

        try {
            // Préparer la requête SQL pour mettre à jour les informations de l'utilisateur
            $stmt = $conn->prepare("UPDATE pact._pro SET denomination = ?, telephone = ?, mail = ?, adresse = ?, codePostal = ?, ville = ? WHERE id = ?");
            $stmt->execute([$denomination, $telephone, $email, $adresse, $code, $ville, $userId]);

            // Rediriger vers une page de confirmation ou une autre page après succès
            $_SESSION['success'][] = "Vos informations ont été mises à jour avec succès.";
            header("Location: profil.php");
            exit();

        } catch (PDOException $e) {
            // Si une erreur se produit lors de l'exécution de la requête
            $_SESSION['errors'][] = "Erreur de mise à jour : " . $e->getMessage();
            header("Location: changeAccountPro.php");
            exit();
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