<?php
// Démarrer la session
session_start();

// fichier de connexion à la BDD
require_once 'db.php';

// Vérifier si l'utilisateur est déjà connecté
if (isset($_SESSION['idUser'])) {
    header("Location: index.php");
    exit();
}

// Vérification de la méthode POST et récupération de l'ID de l'offre
$idOffre = $_POST['idOffre'] ?? null; // On récupère l'ID de l'offre si elle est présente dans le POST

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
            // Si idOffre existe, rediriger vers detailsOffer.php, sinon vers index.php
            $redirectUrl = isset($idOffre) ? "detailsOffer.php" : "index.php";
            // Créer un formulaire caché et l'envoyer automatiquement
            echo "<form id='redirectForm' method='POST' action='$redirectUrl'>
                    <input type='hidden' name='idoffre' value='$idOffre'>
                  </form>
                  <script>
                    document.getElementById('redirectForm').submit();
                  </script>";
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
            // Si idOffre existe, rediriger vers detailsOffer.php, sinon vers index.php
            $redirectUrl = isset($idOffre) ? "detailsOffer.php" : "index.php";
            // Créer un formulaire caché et l'envoyer automatiquement
            echo "<form id='redirectForm' method='POST' action='$redirectUrl'>
                    <input type='hidden' name='idoffre' value='$idOffre'>
                  </form>
                  <script>
                    document.getElementById('redirectForm').submit();
                  </script>";
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
            // Si idOffre existe, rediriger vers detailsOffer.php, sinon vers index.php
            $redirectUrl = isset($idOffre) ? "detailsOffer.php" : "index.php";
            // Créer un formulaire caché et l'envoyer automatiquement
            echo "<form id='redirectForm' method='POST' action='$redirectUrl'>
                    <input type='hidden' name='idoffre' value='$idOffre'>
                  </form>
                  <script>
                    document.getElementById('redirectForm').submit();
                  </script>";
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
            // Si idOffre existe, rediriger vers detailsOffer.php, sinon vers index.php
            $redirectUrl = isset($idOffre) ? "detailsOffer.php" : "index.php";
            // Créer un formulaire caché et l'envoyer automatiquement
            echo "<form id='redirectForm' method='POST' action='$redirectUrl'>
                    <input type='hidden' name='idoffre' value='$idOffre'>
                  </form>
                  <script>
                    document.getElementById('redirectForm').submit();
                  </script>";
            exit();
        } else {
            $error = "Identifiant ou mot de passe incorrect.";
        }
    } catch (Exception $e) {
        $error = "Une erreur est survenue : " . $e->getMessage();
    }
}
?>

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
