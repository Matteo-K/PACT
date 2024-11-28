<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="img/logo.png" type="image/x-icon">
    <title>Changer de mot de passe</title>
</head>
<body id ="body_connexion" class="connexion-compte">
    <aside id="asideRetour">
        <button id="retour">
            <img src="img/logo.png" alt="Logo" title="Retour page précédente"/>
            Retour
        </button>
    </aside>

    <main id="mainConnexion">
        <h1 id="connexionTitre">Changer de mot de passe</h1>

        <?php if (!empty($error)): ?>
            <div id="messageErreur" class="messageErreur"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form id = "formConnexion" action="login.php" method="post" enctype="multipart/form-data">
            <div class="ligne1">
                <!-- Saisi du login -->
                <input type="password" placeholder="Ancien mot de passe" id="ancienMDP" name="ancienMDP" required>
            </div>
    
    
            <div class="ligne2">
                <!-- Saisi du mot de passe -->
                <input type="password" placeholder="Nouveau mot de passe" id="nouveauMDP" name="nouveauMDP" required>
            </div>


            <div class="ligne2">
                <!-- Saisi du mot de passe -->
                <input type="password" placeholder="Confirmer le nouveau mot de passe" id="confirmerNouveauMDP" name="confimerNouveauMDP" required>
            </div>
    
            <button onclick = "validationFormConnexion()" id="boutonChangerMDP">Valider</button>
        </form>
    </main>
</body>
<script src = "js/validationFormInscription.js"></script>
</html>