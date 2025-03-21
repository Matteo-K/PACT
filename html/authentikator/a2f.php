<?php
require "../config.php"; 

if (!isset($_COOKIE['attempts'])) {
    setcookie('attempts', 0, time() + 3600, "/"); 
}

$tempSessionData = [
    'idUser' => (isset($_SESSION['idUser']))?( $_SESSION['idUser']) : (isset($_POST['idu'])? $_POST['idu'] : null),
    'typeUser' => isset($_SESSION['typeUser']) ? $_SESSION['typeUser'] : (isset($_POST['idu'])? $_POST['type'] : null)
];

if (isset($_SESSION['idUser'])) unset($_SESSION['idUser']);
if (isset($_SESSION['typeUser'])) unset($_SESSION['typeUser']);

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['code_2fa'])) {
    $code = htmlspecialchars($_POST['code_2fa']);

    $stmt = $conn->prepare("SELECT * FROM pact._utilisateur WHERE idu = ?");
    $stmt->execute([$tempSessionData['idUser']]); // Utiliser l'ID utilisateur stocké temporairement

    $user = $stmt->fetch();

    $secret = $user["secret_a2f"];
    $totp = OTPHP\TOTP::create($secret);

    // Vérification du code envoyé
    $currentCode = $totp->at(time());
    $previousCode = $totp->at(time() - 30); // Vérification du code précédent

    if ($code === $currentCode || $code === $previousCode) {

        $_SESSION['idUser'] = $tempSessionData['idUser'];
        $_SESSION['typeUser'] = $tempSessionData['typeUser'];

        setcookie('attempts', 0, time() - 3600, "/");

        header('Location: ../index.php'); 
        exit;
    } else {
        $attempts = (int)$_COOKIE['attempts'] + 1;
        setcookie('attempts', $attempts, time() + 3600, "/");

        if ($attempts >= 3) {
            session_unset();
            session_destroy();
            setcookie('attempts', '', time() - 3600, "/"); 
            header('Location: ../index.php');
            exit;
        }

        echo "<span style='color: red;'>Code invalide. Vous avez " . (3 - $attempts) . " tentatives restantes.</span>";
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
        <input type="hidden" name="idu" value="<?php echo $tempSessionData['idUser'] ?>">
        <input type="hidden" name="type" value="<?php echo $tempSessionData['typeUser'] ?>">
        <button type="submit">Vérifier</button>
    </form>

    <p id="status"></p>
</body>
</html>
