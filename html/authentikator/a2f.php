<?php
require "../config.php"; // Assurez-vous d'avoir inclus la configuration

// Si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
if (!isset($_SESSION['idUser'])) {
    header("Location: ../login.php");
    exit;
}

// Stocker temporairement les informations de session avant réinitialisation
$tempSessionData = [
    'idUser' => $_SESSION['idUser'],
    'typeUser' => isset($_SESSION['typeUser']) ? $_SESSION['typeUser'] : null
];

// Réinitialiser la session à chaque arrivée sur la page de vérification 2FA
session_unset(); // Vider la session
session_regenerate_id(true); // Générer un nouvel ID de session pour éviter les attaques par fixation de session

// Si 3 tentatives échouées, détruire la session et rediriger vers index.php
if (!isset($_SESSION['attempts'])) {
    $_SESSION['attempts'] = 0;
}

// Vérifier si le code 2FA a été soumis
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['code_2fa'])) {
    $code = htmlspecialchars($_POST['code_2fa']);

    // Vérifier la présence du secret dans la session
    $stmt = $conn->prepare("SELECT * FROM pact._utilisateur WHERE idu = ?");
    $stmt->execute([$tempSessionData['idUser']]); // Utiliser l'ID utilisateur stocké temporairement

    $user = $stmt->fetch();
    if (!$user) {
        // L'utilisateur n'existe pas ou la session a été expirée
        session_unset();
        session_destroy();
        header("Location: ../login.php");
        exit;
    }

    $secret = $user["secret_a2f"];
    $totp = OTPHP\TOTP::create($secret);

    // Vérification du code envoyé
    $currentCode = $totp->at(time());
    $previousCode = $totp->at(time() - 30); // Vérification du code précédent

    if ($code === $currentCode || $code === $previousCode) {
        // Réinitialiser la session avec les données de l'utilisateur
        $_SESSION['idUser'] = $user['idu']; // Remplir la session avec l'ID utilisateur
        $_SESSION['typeUser'] = 'admin'; // Définir le type utilisateur (ici "admin")

        unset($_SESSION['attempts']); // Réinitialiser les tentatives
        header('Location: ../index.php'); // Rediriger vers la page principale après succès
        exit;
    } else {
        $_SESSION['attempts'] += 1;
        echo "<span style='color: red;'>Code invalide. Vous avez " . (3 - $_SESSION['attempts']) . " tentatives restantes.</span>";
    }
}

// Si l'utilisateur a atteint 3 tentatives, détruire la session et rediriger
if ($_SESSION['attempts'] >= 3) {
    session_unset();
    session_destroy();
    header('Location: ../index.php');
    exit;
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
