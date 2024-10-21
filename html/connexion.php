<?php 
    // Démarrer la session
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_connexion.css">
    <link rel="icon" href="logo.png" type="image/x-icon">
    <title>Connexion</title>
</head>
<body id ="body_connexion">
    <aside id="asideRetour">
        <button id="retour">
            <img src="logo.png" alt="Logo" title="Retour page précédente"/>
            Retour
        </button>
    </aside>

    <main id="mainConnexion">
        <h1 id="connexionTitre">Connectez-vous à votre compte</h1>
        <form id = "formConnexion" action=".php" method="post" enctype="multipart/form-data">
            <div class="ligne1">
                <!-- Saisi du login -->
                <input type="text" placeholder = "Identifiant/adresse mail" id="login" name="login" required>
            </div>
    
    
            <div class="ligne2">
                <!-- Saisi du mot de passe -->
                <input type="password" placeholder = "Mot de passe" id = "motdepasseConnexion" name="mot de passe" required>
            </div>
    
    
            <button onclick = "validationFormConnexion()" id="boutonConnexion">Connexion</button>

            <a id="lienMotDePasseOublie" href=""> Mot de passe oublié ?</a>
            <script src = validationFormConnexion.js></script>
        </form>
    
        <h1 id="pasDeCompteTitre">Vous n'avez pas de compte ? Créez-en un !</h1>

        <div id="lienBouton">
            <div>
                <button onclick = "" id="boutonLienMembre">Compte membre</button>
                <p id="legendeBoutonLienMembre">Compte gratuit pour une utilisation classique de la plateforme</p>
            </div>

            <div>
                <button onclick = "creation_compte_pro.php" id="boutonLienPro">Compte professionnel</button>
                <p id="legendeBoutonLienPro">Compte destiné aux professionels voulant promouvoir une offre de leur entreprise</p>
            </div>
        </div>
    </main>
    <?php
        require_once 'dbLocalKylian.php';

        // Vérification du formulaire
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $login = $_POST['login'];
            $password = $_POST['motdepasseConnexion']; // Assurez-vous que le nom correspond à celui dans le formulaire

            // Requête pour vérifier si l'email existe
            $stmt = $conn->prepare("SELECT * FROM pact._admin a JOIN pact._utilisateur u  ON a.idU = u.idU WHERE a.login = ?");
            $stmt->execute([$login]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);
            


            // Vérification admin
            if ($admin && $password == $admin['password']) {
                // Connexion réussie
                $_SESSION['idUser'] = $admin['id'];
                $_SESSION['typeUser'] = 'admin';

                header("Location: index.php"); // Rediriger vers une page protégée
                exit();
            } 
            
            else {
                    $error = "Mot de passe incorrect.";
                }
            }



            // Vérification pro
            else {
                $stmt = $conn->prepare("SELECT mail, idU FROM proPublic WHERE mail = ? UNION SELECT mail, idU FROM proPrive WHERE mail = ?;");
                $stmt->execute([$login, $login]);
                $proUser = $stmt->fetch(PDO::FETCH_ASSOC);
        
                if ($proUser) {
                    // Vérification du mot de passe
                    if (password_verify($password, $proUser['motdepasse'])) {
                        // Connexion réussie
                        $_SESSION['idUser'] = $proUser['id'];
                        $_SESSION['typeUser'] = $proUser['siren'] ? 'pro_prive' : 'pro_public'; // Détermine le type
        
                        header("Location: index.php"); // Rediriger vers une page protégée
                        exit();
                    } 
                    
                    else {
                        $error = "Mot de passe incorrect.";
                    }
                } 



            // Vérification membre
            else {
                $stmt = $conn->prepare("SELECT * FROM membre WHERE pseudo = ? OR mail = ?");
                $stmt->execute([$login, $login]);
                $member = $stmt->fetch(PDO::FETCH_ASSOC);
    
                if ($member) {
                    // Vérification du mot de passe
                    if (password_verify($password, $member['motdepasse'])) {
                        // Connexion réussie
                        $_SESSION['idUser'] = $member['id'];
                        $_SESSION['typeUser'] = 'membre';
    
                        header("Location: index.php"); // Rediriger vers une page protégée
                        exit();
                    } 
                    
                    else {
                        $error = "Mot de passe incorrect.";
                    }
                } 
                
                else {
                    $error = "Aucun utilisateur trouvé avec cet e-mail.";
                }
            }
        }
    ?>

</body>
</html>