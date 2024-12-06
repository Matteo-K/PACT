<?php
    // Démarrer la session
    session_start();

    // Connexion à la base de données
    require_once 'db.php';

    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['idUser'])) {
        header("Location: login.php");
        exit();
    }

    // Récupérer l'ID de l'utilisateur connecté
    $userId = $_SESSION['idUser'];

    // Récupérer les informations de l'utilisateur depuis la base de données
    try {
        $stmt = $conn->prepare("SELECT * FROM pact.propublic WHERE idU = ?");
        $stmt->execute([$userId]);
        $userPublic = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifier dans la table pro privé si pas trouvé dans pro public
        if (!$userPublic) {
            $stmt = $conn->prepare("SELECT * FROM pact.proprive WHERE idU = ?");
            $stmt->execute([$userId]);
            $userPrivate = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        // Fusionner les résultats de propublic et proprive, si les deux existent
        $user = $userPublic ?: $userPrivate;

        // Vérifier si les données sont trouvées
        if (!$user) {
            $_SESSION['errors'][] = "Utilisateur introuvable.";
            header("Location: login.php");
            exit();
        }
    } 
    
    catch (Exception $e) {
        $_SESSION['errors'][] = "Erreur de connexion à la base de données: " . $e->getMessage();
        header("Location: login.php");
        exit();
    }


    // Vérifier si le formulaire a été soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupérer les nouvelles données du formulaire
        $denomination = trim($_POST['denomination']);
        $telephone = trim($_POST['telephone']);
        $mail = trim($_POST['email']);
        $adresse = trim($_POST['adresse']);
        $code = trim($_POST['code']);
        $ville = trim($_POST['ville']);

        // Si l'adresse mail a été modifiée, vérifier si elle existe déjà
        if ($mail !== $user['mail']) {
            try {
                // Requête sur propublic
                $stmt = $conn->prepare("SELECT * FROM pact.propublic WHERE mail = ?");
                $stmt->execute([$mail]);
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Si pas trouvé, vérifier dans proprive
                if (!$result) {
                    $stmt = $conn->prepare("SELECT * FROM pact.proprive WHERE mail = ?");
                    $stmt->execute([$mail]);
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                }
            }

            catch (Exception $e) {
                $_SESSION['errors'][] = "Erreur lors de la vérification de l'adresse mail : " . htmlspecialchars($e->getMessage());
            }
        }

        // Si aucun problème, mettre à jour les informations
        if (empty($_SESSION['errors'])) {
            try {
                $adresseExplode = explode(' ', $adresse, 2);
                $numeroRue = isset($adresseExplode[0]) ? $adresseExplode[0] : '';
                $rue = isset($adresseExplode[1]) ? $adresseExplode[1] : '';

                // Mettre à jour les informations dans la base de données
                if ($userPublic) {
                    $stmt = $conn->prepare("UPDATE pact.propublic SET denomination = ?, telephone = ?, mail = ?, numeroRue = ?, rue = ?, codePostal = ?, ville = ? WHERE idU = ?");
                    $stmt->execute([$denomination, $telephone, $mail, $numeroRue, $rue, $code, $ville, $userId]);
                } 
                
                else {
                    $stmt = $conn->prepare("UPDATE pact.proprive SET denomination = ?, telephone = ?, mail = ?, numeroRue = ?, rue = ?, codePostal = ?, ville = ? WHERE idU = ?");
                    $stmt->execute([$denomination, $telephone, $mail, $numeroRue, $rue, $code, $ville, $userId]);
                }

                $_SESSION['success'] = "Informations mises à jour avec succès.";
                header("Location: search.php");
                exit();
            }

            catch (Exception $e) {
                $_SESSION['errors'][] = "Erreur lors de la mise à jour des informations : " . $e->getMessage();
            }
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

            <div id="divPFP">
                <img src="uploads/<?= htmlspecialchars($photoProfil) ?>" alt="Photo de Profil" id="current-profile-pic">
                <input type="file" id="profile-pic" name="profile-pic" accept="image/*" required>
            </div>


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
                <input type="text" placeholder ="123 Rue de Brest" id="adresse" name="adresse" value="<?= isset($user['numerorue']) && isset($user['rue']) ? htmlspecialchars($user['numerorue']) . ' ' . htmlspecialchars($user['rue']) : '' ?>" required>
                <br>
            </div>


            
            <div class="ligne4"> 
                <label for="code">Code postal*:</label>
                <label for="ville">Ville*:</label>
                
                <!-- Saisi du code postale -->
                <input type="text" placeholder="29200" id="code" name="code" value="<?= isset($user['codepostal']) ? htmlspecialchars($user['codepostal']) : '' ?>" required>

                <!-- Saisi de la ville -->
                <input type="text" placeholder="Brest" id="ville" name="ville" value="<?= isset($user['ville']) ? htmlspecialchars($user['ville']) : '' ?>" required>
            </div>
            
            <button type="submit" id="boutonInscription">Valider</button>
            
        </form>
    </body>
    <script src="js/validationFormInscription.js"></script>
</html>