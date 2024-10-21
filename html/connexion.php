<?php 
    // Démarrer la session
    session_start();
    require_once 'dbLocalKylian.php';

    if(isset($_SESSION['idUser'])){
        header("Location: index.php");
        exit();
    }

    // Vérification du formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $login = $_POST['login'];
        $password = $_POST['motdepasseConnexion']; // Nom mis à jour pour correspondre au formulaire

        // Vérification admin
        $stmt = $conn->prepare("SELECT * FROM pact._admin a JOIN pact._utilisateur u ON a.idU = u.idU WHERE a.login = ?");
        $stmt->execute([$login]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($admin && $password == $admin['password']) {
            print_r($admin);
            // Connexion réussie
            $_SESSION['idUser'] = $admin['idU'];
            $_SESSION['typeUser'] = 'admin';

            header('Location: index.php'); // Rediriger vers une page protégée
            exit();
        } else {
            // Vérification pro
            $stmt = $conn->prepare("SELECT mail, idU, motdepasse, siren FROM proPublic WHERE mail = ? UNION SELECT mail, idU, motdepasse, siren FROM proPrive WHERE mail = ?;");
            $stmt->execute([$login, $login]);
            $proUser = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($proUser && password_verify($password, $proUser['motdepasse'])) {
                // Connexion réussie
                $_SESSION['idUser'] = $proUser['idU'];
                $_SESSION['typeUser'] = $proUser['siren'] ? 'pro_prive' : 'pro_public'; // Détermine le type
    
                header("Location: index.php"); // Rediriger vers une page protégée
                exit();
            } else {
                // Vérification membre
                $stmt = $conn->prepare("SELECT * FROM membre WHERE pseudo = ? OR mail = ?");
                $stmt->execute([$login, $login]);
                $member = $stmt->fetch(PDO::FETCH_ASSOC);
    
                if ($member && password_verify($password, $member['motdepasse'])) {
                    // Connexion réussie
                    $_SESSION['idUser'] = $member['idU'];
                    $_SESSION['typeUser'] = 'membre';

                    header("Location: index.php"); // Rediriger vers une page protégée
                    exit();
                } else {
                    $error = "Identifiant ou mot de passe incorrect.";
                }
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_connexion.css">
    <link rel="icon" href="logo.png" type="image/x-icon">
    <title>Connexion</title>
</head>
<body id ="body_connexion" class="connexion-compte">
    <aside id="asideRetour">
        <button id="retour" onclick="history.back();">
            <img src="logo.png" alt="Logo" title="Retour page précédente"/>
            Retour
        </button>
    </aside>

    <main id="mainConnexion">
        <h1 id="connexionTitre">Connectez-vous à votre compte</h1>
        <form id="formConnexion" action="connexion.php" method="post">
            <div class="ligne1">
                <!-- Saisie du login -->
                <input type="text" placeholder="Identifiant/adresse mail" id="login" name="login" required>
            </div>
    
            <div class="ligne2">
                <!-- Saisie du mot de passe -->
                <input type="password" placeholder="Mot de passe" id="motdepasseConnexion" name="motdepasseConnexion" required>
            </div>
    
    
            <button onclick = "validationFormConnexion()" id="boutonConnexion">Connexion</button>
            <script src = validationFormInscription.js></script>

            <a id="lienMotDePasseOublie" href=""> Mot de passe oublié ?</a>
        </form>
        
        <h1 id="pasDeCompteTitre">Vous n'avez pas de compte ? Créez-en un !</h1>
        
        <div id="lienBouton">
            <div>
                <a href="" id="boutonLienMembre">Compte membre</a>
                <p id="legendeBoutonLienMembre">Compte gratuit pour une utilisation classique de la plateforme</p>
            </div>
            
            <div>
                <a href="creation_compte_pro.php" id="boutonLienPro">Compte professionnel</a>
                <p id="legendeBoutonLienPro">Compte destiné aux professionels voulant promouvoir une offre de leur entreprise</p>
            </div>
        </div>
    </main>
    <script src = validationFormConnexion.js></script>
</body>
</html>
