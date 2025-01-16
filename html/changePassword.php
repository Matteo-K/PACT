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

    // Initialiser une variable d'erreur vide
    $error = '';

    // Vérification du formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupérer les données du formulaire
        $ancienMDP = $_POST['ancienMDP'];
        $nouveauMDP = $_POST['nouveauMDP'];
        $confirmerNouveauMDP = $_POST['confirmerNouveauMDP'];

        // Vérification que le nouveau mot de passe et la confirmation sont identiques
        if ($nouveauMDP !== $confirmerNouveauMDP) {
            $error = "Les mots de passe ne correspondent pas.";
        }

        // Vérification si l'ancien mot de passe est identique au nouveau mot de passe
        else if ($ancienMDP === $nouveauMDP) {
            $error = "L'ancien mot de passe et le nouveau mot de passe ne peuvent pas être identiques.";
        }

        // Vérification du format du nouveau mot de passe
        else if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\w\d\s]).{10,}$/', $nouveauMDP)) {
            $error = "Le mot de passe doit contenir au moins 10 caractères, dont une majuscule, une minuscule, un chiffre et un caractère spécial.";
        } 
        
        else {
            // Récupérer le mot de passe actuel de l'utilisateur depuis la base de données
            $stmt = $conn->prepare("SELECT password FROM pact._utilisateur WHERE idU = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($ancienMDP, $user['password'])) {
                // L'ancien mot de passe est correct, on peut mettre à jour le mot de passe
                $newPasswordHash = password_hash($nouveauMDP, PASSWORD_BCRYPT); // Hasher le nouveau mot de passe

                // Mettre à jour le mot de passe dans la base de données
                $stmt = $conn->prepare("UPDATE pact._utilisateur SET password = ? WHERE idU = ?");
                $stmt->execute([$newPasswordHash, $userId]);

                // Rediriger l'utilisateur avec un message de succès
                header("Location: index.php");
                exit();
            } 
            
            else {
                // Si l'ancien mot de passe ne correspond pas
                $error = "L'ancien mot de passe est incorrect.";
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
    <title>Changer MDP</title>
</head>
<body id ="body_connexion" class="connexion-compte">
    <aside id="asideRetour">
        <button id="retour">
            <img src="img/logo.png" alt="Logo" title="Retour page précédente"/>
            Retour
        </button>
    </aside>

    <main id="mainChangerMDP">
        <h1 id="changerMDPTitre">Changer de mot de passe</h1>

        <?php if (!empty($error)): ?>
            <div id="messageErreur" class="messageErreur"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form id = "formConnexion" action="changePassword.php" method="post" enctype="multipart/form-data">
            <div class="ligne1">
                <!-- Saisi de l'ancien mot de passe -->
                <input type="password" placeholder="Ancien mot de passe" id="ancienMDP" name="ancienMDP" required>
            </div>
    
    
            <div class="ligne2">
                <!-- Saisi du nouveau mot de passe -->
                <input type="password" placeholder="Nouveau mot de passe" id="nouveauMDP" name="nouveauMDP" required>
            </div>


            <div class="ligne2">
                <!-- Confirmation du mot de passe -->
                <input type="password" placeholder="Confirmer le mot de passe" id="confirmerNouveauMDP" name="confirmerNouveauMDP" required>
            </div>
    
            <button onclick = "validationFormConnexion()" id="boutonChangerMDP">Valider</button>
        </form>
    </main>
</body>
<script src = "js/validationFormInscription.js"></script>
<script src="js/setColor.js"></script>

</html>