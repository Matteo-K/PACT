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
        $stmt = $conn->prepare("SELECT * FROM pact.membre WHERE idU = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifier si les données sont trouvées
        if (!$user) {
            $_SESSION['errors'][] = "Utilisateur introuvable.";
            header("Location: index.php");
            exit();
        }


        // Récupérer la photo de profil de la table _photo_profil
        $stmtPhoto = $conn->prepare("SELECT * FROM pact._photo_profil WHERE idU = ?");
        $stmtPhoto->execute([$userId]);
        $photoProfil = $stmtPhoto->fetch(PDO::FETCH_ASSOC);

        if ($photoProfil) {
            $photoPath = $photoProfil['url'];  // Le chemin de l'image
        } 
        
        else {
            // Si aucune photo n'est trouvée, utiliser une image par défaut
            $photoPath = './img/profile_picture/default.svg';
        }
    } 
    
    catch (Exception $e) {
        $_SESSION['errors'][] = "Erreur de connexion à la base de données: " . $e->getMessage();
        header("Location: index.php");
        exit();
    }

    
    // Vérifier si le formulaire a été soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupérer les nouvelles données du formulaire
        $nom = trim($_POST['nomMembre']);
        $prenom = trim($_POST['prenomMembre']);
        $pseudo = trim($_POST['pseudoMembre']);
        $telephone = trim($_POST['telephone']);
        $mail = trim($_POST['email']);
        $adresse = trim($_POST['adresse']);
        $code = trim($_POST['code']);
        $ville = trim($_POST['ville']);

        // Photo de profil
        $file = $_FILES['profile-pic'];

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($file['type'], $allowedTypes)) {
            $targetDir = 'uploads/';
            $targetFile = $targetDir . basename($file['name']);

            if (move_uploaded_file($file['tmp_name'], $targetFile)) {
                try {
                    $stmt = $conn->prepare("UPDATE pact._photo_profil SET url = ? WHERE idU = ?");
                    $stmt->execute([$targetFile, $userId]);

                    $_SESSION['success'] = "Photo de profil mise à jour avec succès.";
                    header("Location: changeAccountPro.php");
                    exit();
                } 

                catch (Exception $e) {
                    $_SESSION['errors'][] = "Erreur lors de la mise à jour de la photo : " . $e->getMessage();
                }
            } 

            else {
                $_SESSION['errors'][] = "Échec du téléchargement de l'image.";
            }
        }

        else {
            $_SESSION['errors'][] = "Seules les images JPG, PNG ou GIF sont autorisées.";
        }

        // // Si l'adresse mail a été modifié, vérifier si elle existe déjà
        if ($mail !== $user['mail']) {
            try {
                $stmt = $conn->prepare("SELECT * FROM pact._nonadmin WHERE mail = ?");
                $stmt->execute([$mail]);
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if ($result) {
                    $_SESSION['errors'][] = "L'adresse email existe déjà.";
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
                $stmt = $conn->prepare("UPDATE pact.membre SET pseudo = ?, nom = ?, prenom = ?, numeroRue = ?, rue = ?, ville = ?, pays = ?, codePostal = ?, telephone = ?, mail = ? WHERE idU = ?");
                $stmt->execute([$pseudo, $nom, $prenom, $numeroRue, $rue, $ville, 'France', $code, $telephone, $mail, $userId]);

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

        <form id = "formMember" action="changeAccountMember.php" method="post" enctype="multipart/form-data">

            <div id="divPFPactuelle">
                <img src="<?= $photoPath ?>" alt="Photo de Profil" id="current-profile-pic">
            </div>

            <div id="divNewPFP">
                <input type="file" id="profile-pic" name="profile-pic" accept="image/*">
            </div>


            <div class="ligne1">
                <label  id="labelPrenom" for="prenomMembre">Prénom*:</label>
                <label id="labelNom" for="nomMembre">Nom*:</label>
                    
                <!-- Saisi du prénom -->
                <input type="text" placeholder="Jean" id="prenomMembre" name="prenomMembre" value="<?= isset($user['prenom']) ? htmlspecialchars($user['prenom']) : '' ?>" required>

                <!-- Saisi du nom -->
                <input type="text" placeholder="Dupont" id="nomMembre" name="nomMembre" value="<?= isset($user['nom']) ? htmlspecialchars($user['nom']) : '' ?>" required>
    
            </div>
    
            <div class="ligne1_1">
                <label id="labelPseudo" for="pseudoMembre">Pseudonyme*:</label>
                <label id="labelTelephone" for="telephoneMembre">Téléphone*:</label>
                    
                <!-- Saisi du pseudo -->
                <input type="text" placeholder="Jean29" id="pseudoMembre" name="pseudoMembre" value="<?= isset($user['pseudo']) ? htmlspecialchars($user['pseudo']) : '' ?>" required>

                <!-- Saisi du numéro de téléphone -->
                <input type="tel" placeholder="06 01 02 03 04" id="telephoneMembre" name="telephone" value="<?= isset($user['telephone']) ? htmlspecialchars($user['telephone']) : '' ?>" required>
            </div>
    
    
    
            <div class="ligne2">
                <!-- Saisi de l'adresse mail -->
                <label for="email">Adresse mail*:</label>
                <input type="email" placeholder="exemple@gmail.com" id="email" name="email" value="<?= isset($user['mail']) ? htmlspecialchars($user['mail']) : '' ?>" required>
            </div>


            
            <div class="ligne3">
                <!-- Saisi de l'adresse postale -->
                <label for="adresse">Adresse postale*:</label>
                <input type="text" placeholder="123 Rue de Brest" id="adresse" name="adresse" value="<?= isset($user['numerorue']) && isset($user['rue']) ? htmlspecialchars($user['numerorue']) . ' ' . htmlspecialchars($user['rue']) : '' ?>" required>
            </div>


            
            <div class="ligne4"> 
                <label id="labelCode" for="code">Code postal*:</label>
                <label id="labelVille" for="ville">Ville*:</label>
                
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