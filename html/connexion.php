<?php
    // Démarrer la session
    session_start();

    // fichier de connexion à la BDD
    require_once 'db.php';

    if(isset($_SESSION['idUser'])){
        header("Location: index.php");
        exit();
    }

    // Vérification du formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $login = $_POST['login'];
        $password = $_POST['motdepasseConnexion'];

        // Vérification admin
        $stmt = $conn->prepare("SELECT * FROM pact._admin a JOIN pact._utilisateur u ON a.idU = u.idU WHERE a.login = ?");
        $stmt->execute([$login]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch a single row

        if ($result && $password == $result['password']) {
            // Connexion réussie
            $_SESSION['idUser'] = $result['idu'];
            $_SESSION['typeUser'] = 'admin';

            header("Location: index.php");
             // Rediriger vers une page protégée
            exit();

        } else {
            // Vérification pro
            $stmt = $conn->prepare('SELECT * FROM pact.proprive WHERE mail = ?');
            $stmt->execute([$login]);
            $proUser = $stmt->fetch(PDO::FETCH_ASSOC);

            print_r($proUser);
    
            if ($proUser && password_verify($password, $proUser['password'])) {
                // Connexion réussie
                $_SESSION['idUser'] = $proUser['idu'];
                $_SESSION['typeUser'] = $proUser['siren'] ? 'pro_prive' : 'pro_public'; // Détermine le type
                header("Location: index.php");
                exit();

            } else {
                // Vérification membre
                $stmt = $conn->prepare("SELECT * FROM membre WHERE pseudo = ? OR mail = ?");
                $stmt->execute([$login, $login]);
                $member = $stmt->fetch(PDO::FETCH_ASSOC);
    
                if ($member && password_verify($password, $member['motdepasse'])) {
                    // Connexion réussie
                    $_SESSION['idUser'] = $member['idu'];
                    $_SESSION['typeUser'] = 'membre';
                    header("Location: index.php");

                    exit();
                } else {
                    $error = "Identifiant ou mot de passe incorrect.";
                }
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
    <title>Connexion</title>
</head>
<body id ="body_connexion" class="connexion-compte">
    <aside id="asideRetour">
        <button id="retour">
            <img src="img/logo.png" alt="Logo" title="Retour page précédente"/>
            Retour
        </button>
    </aside>

    <main id="mainConnexion">
        <h1 id="connexionTitre">Connectez-vous à votre compte</h1>
        <form id = "formConnexion" action="connexion.php" method="post" enctype="multipart/form-data">
            <div class="ligne1">
                <!-- Saisi du login -->
                <input type="text" placeholder = "Identifiant/adresse mail" id="login" name="login" required>
            </div>
    
    
            <div class="ligne2">
                <!-- Saisi du mot de passe -->
                <input type="password" placeholder = "Mot de passe" id="motdepasseConnexion" name="motdepasseConnexion" required>
            </div>
    
    
            <button onclick = "validationFormConnexion()" id="boutonConnexion">Connexion</button>

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
</body>
<script src = "js/validationFormConnexion.js"></script>
</html>