<?php
require "../config.php"; 
require '../vendor/autoload.php';

use OTPHP\TOTP;

if (!isset($_COOKIE['attempts'])) {
    setcookie('attempts', 0, time() + 3600, "/"); 
}

$tempSessionData = [
    'idUser' => (isset($_SESSION['idUser']))?( $_SESSION['idUser']) : (isset($_POST['idu'])? $_POST['idu'] : null),
    'typeUser' => isset($_SESSION['typeUser']) ? $_SESSION['typeUser'] : (isset($_POST['idu'])? $_POST['type'] : null)
];

if (isset($_SESSION['idUser'])) unset($_SESSION['idUser']);
if (isset($_SESSION['typeUser'])) unset($_SESSION['typeUser']);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $code = htmlspecialchars($_POST['code_2fa1'] + $_POST['code_2fa2'] + $_POST['code_2fa3'] + $_POST['code_2fa4'] + $_POST['code_2fa5'] + $_POST['code_2fa6']);

    $stmt = $conn->prepare("SELECT * FROM pact._utilisateur WHERE idu = ?");
    $stmt->execute([$tempSessionData['idUser']]); // Utiliser l'ID utilisateur stocké temporairement

    $user = $stmt->fetch();

    $secret = $user["secret_a2f"];
    $totp = TOTP::create($secret);

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
    <link rel="stylesheet" href="../style.css">
</head>
<body id="bodyA2f">
    <div id="a2f">
        <h1>Veuillez entrer votre code à 6 chiffres</h1>

        <form method="POST" action="a2f.php">
            <div>
                <input type="text" id="code_2fa1" name="code_2fa1" maxlength="1" required>
                <input type="text" id="code_2fa2" name="code_2fa2" maxlength="1" required>
                <input type="text" id="code_2fa3" name="code_2fa3" maxlength="1" required>
                <input type="text" id="code_2fa4" name="code_2fa4" maxlength="1" required>
                <input type="text" id="code_2fa5" name="code_2fa5" maxlength="1" required>
                <input type="text" id="code_2fa6" name="code_2fa6" maxlength="1" required>
            </div>
            <input type="hidden" name="idu" value="<?php echo $tempSessionData['idUser'] ?>">
            <input type="hidden" name="type" value="<?php echo $tempSessionData['typeUser'] ?>">
            <button type="submit" class="modifierBut" >Vérifier</button>
        </form>
    
        <p id="status"></p>
    </div>
</body>
</html>
