<?php
require "../config.php"; // Assurez-vous d'avoir inclus la configuration

// Si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
if (!isset($_SESSION['idUser'])) {
    header("Location: login.php");
    exit;
}

// Vérifier si l'utilisateur a tenté trop de fois
if (!isset($_SESSION['attempts'])) {
    $_SESSION['attempts'] = 0;
}

// Si 3 tentatives échouées, détruire la session et rediriger vers index.php
if ($_SESSION['attempts'] >= 3) {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit;
}

// Vérifier si le code 2FA a été soumis
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['code_2fa'])) {
    $code = htmlspecialchars($_POST['code_2fa']);

    // Vérifier la présence du secret dans la session
    if (!isset($_SESSION['secret_a2f'])) {
        echo "<span style='color: red;'>Erreur : aucun secret trouvé dans la session.</span>";
        exit;
    }

    $secret = $_SESSION['secret_a2f'];
    $totp = OTPHP\TOTP::create($secret);

    // Vérification du code envoyé
    $currentCode = $totp->at(time());
    $previousCode = $totp->at(time() - 30); // Vérification du code précédent

    if ($code === $currentCode || $code === $previousCode) {
        // Réinitialiser les tentatives en cas de succès
        $_SESSION['a2f_verifier'] = true;
        $_SESSION['attempts'] = 0; // Réinitialiser les tentatives

        // Rediriger vers la page d'accueil (index.php) ou tableau de bord
        header('Location: index.php');
        exit;
    } else {
        // Incrémenter le compteur d'essais en cas d'échec
        $_SESSION['attempts'] += 1;
        echo "<span style='color: red;'>Code invalide. Vous avez " . (3 - $_SESSION['attempts']) . " tentatives restantes.</span>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification 2FA</title>
</head>
<body>
    <h1>Veuillez entrer votre code à 6 chiffres</h1>

    <!-- Formulaire de saisie du code -->
    <form method="POST" action="a2f.php">
        <input type="text" id="code_2fa" name="code_2fa" maxlength="6" required>
        <button type="submit">Vérifier</button>
    </form>

    <p id="status"></p>
</body>
</html>
