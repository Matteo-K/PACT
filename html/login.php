<?php
// Démarrer la session
session_start();

// fichier de connexion à la BDD
require_once 'db.php';

if(isset($_POST['idOffre'])){
    $idOffre = $_POST['idOffre'];
}else{
    $idOffre = null;
}
// Vérification de la connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login']) && isset($_POST['motdepasseConnexion'])) {
    $login = $_POST['login'];
    $password = $_POST['motdepasseConnexion'];

    try {
        // Vérification admin
        $stmt = $conn->prepare("SELECT * FROM pact._admin a JOIN pact._utilisateur u ON a.idU = u.idU WHERE a.login = ?");
        $stmt->execute([$login]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && password_verify($password, $result['password'])) {
            // Connexion réussie
            $_SESSION['idUser'] = $result['idu'];
            $_SESSION['typeUser'] = 'admin';
            // Préparer la redirection avec POST vers detailsOffer.php si idOffre est présent
            
            if ($idOffre) {
                ?>
                <form id='redirectForm' method='POST' action='detailsOffer.php'>
                    <input type='hidden' name='idOffre' value='<?php echo $idOffre = null
 ?>'>
                </form>
                <script>
                    document.getElementById('redirectForm').submit();
                </script>
                <?php
                exit();
            } else {
                // Gestion d'erreur si l'idOffre n'est pas défini
                header("Location: index.php");
                exit();
            }
            
            exit();
        }

        // Vérification pour le compte privé
        $stmt = $conn->prepare('SELECT * FROM pact.proprive WHERE mail = ?');
        $stmt->execute([$login]);
        $proUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($proUser && password_verify($password, $proUser['password'])) {
            // Connexion réussie
            $_SESSION['idUser'] = $proUser['idu'];
            $_SESSION['typeUser'] = 'pro_prive';
            // Préparer la redirection avec POST vers detailsOffer.php si idOffre est présent
            if ($idOffre) {
                ?>
                <form id='redirectForm' method='POST' action='detailsOffer.php'>
                    <input type='hidden' name='idOffre' value='<?php echo $idOffre ?>'>
                </form>
                <script>
                    document.getElementById('redirectForm').submit();
                </script>
                <?php
                exit();
            } else {
                // Gestion d'erreur si l'idOffre n'est pas défini
                header("Location: index.php");
                exit();
            }
            
            exit();
        }

        // Vérification pour le compte public
        $stmt = $conn->prepare('SELECT * FROM pact.propublic WHERE mail = ?');
        $stmt->execute([$login]);
        $proUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($proUser && password_verify($password, $proUser['password'])) {
            // Connexion réussie
            $_SESSION['idUser'] = $proUser['idu'];
            $_SESSION['typeUser'] = 'pro_public';
            // Préparer la redirection avec POST vers detailsOffer.php si idOffre est présent
            if ($idOffre) {
                ?>
                <form id='redirectForm' method='POST' action='detailsOffer.php'>
                    <input type='hidden' name='idOffre' value='<?php echo $idOffre ?>'>
                </form>
                <script>
                    document.getElementById('redirectForm').submit();
                </script>
                <?php
                exit();
            } else {
                // Gestion d'erreur si l'idOffre n'est pas défini
                header("Location: index.php");
                exit();
            }
            
            exit();
        }

        // Vérification pour le compte membre
        $stmt = $conn->prepare("SELECT * FROM pact.membre WHERE pseudo = ? OR mail = ?");
        $stmt->execute([$login, $login]);
        $member = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($member && password_verify($password, $member['password'])) {
            // Connexion réussie
            $_SESSION['idUser'] = $member['idu'];
            $_SESSION['typeUser'] = 'membre';
            // Préparer la redirection avec POST vers detailsOffer.php si idOffre est présent
            if ($idOffre) {
                ?>
                <form id='redirectForm' method='POST' action='detailsOffer.php'>
                    <input type='hidden' name='idOffre' value='<?php echo $idOffre ?>'>
                </form>
                <script>
                    document.getElementById('redirectForm').submit();
                </script>
                <?php
                exit();
            } else {
                // Gestion d'erreur si l'idOffre n'est pas défini
                header("Location: index.php");
                exit();
            }
            
            exit();
        } else {
            $error = "Identifiant ou mot de passe incorrect.";
        }
    } catch (Exception $e) {
        $error = "Une erreur est survenue : " . $e->getMessage();
    }
}
?>

<!-- Le reste de votre HTML pour le formulaire de connexion -->



<!DOCTYPE html>
<html lang="fr">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="img/logo.png" type="image/x-icon">
    <title>Connexion</title>
</head>
<body id="body_connexion" class="connexion-compte">
    <aside id="asideRetour">
        <button id="retour" onclick="window.history.back();">
            <img src="img/logo.png" alt="Logo" title="Retour page précédente"/>
            Retour
        </button>
    </aside>

    <main id="mainConnexion">
        <h1 id="connexionTitre">Connectez-vous à votre compte</h1>

        <?php if (!empty($error)): ?>
            <div id="messageErreur" class="messageErreur"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form id="formConnexion" action="login.php" method="post" enctype="multipart/form-data">
            <div class="ligne1">
                <input type="text" placeholder="Identifiant/adresse mail" id="login" name="login" required>
            </div>
    
            <div class="ligne2">
                <input type="password" placeholder="Mot de passe" id="motdepasseConnexion" name="motdepasseConnexion" required>
            </div>
    
            <button id="boutonConnexion">Connexion</button>

            <a id="lienMotDePasseOublie" href="#"> Mot de passe oublié ?</a>
        <?php
        if($idOffre){
        ?>
            <input type="hidden" name="idOffre" value="<?= $idOffre?>">
        <?php
        }
        ?>
        </form>
        
        <h1 id="pasDeCompteTitre">Vous n'avez pas de compte ? Créez-en un !</h1>
        
        <div id="lienBouton">
            <div>
                <a href="accountMember.php" id="boutonLienMembre">Compte membre</a>
                <p id="legendeBoutonLienMembre">Compte gratuit pour une utilisation classique de la plateforme</p>
            </div>
            
            <div>
                <a href="accountPro.php" id="boutonLienPro">Compte professionnel</a>
                <p id="legendeBoutonLienPro">Compte destiné aux professionels voulant promouvoir une offre de leur entreprise</p>
            </div>
        </div>
    </main>
</body>
<script src="js/validationFormInscription.js"></script>
</html>
