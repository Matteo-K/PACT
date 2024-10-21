<?php
session_start();
include 'dbLocalKylian.php'; // Fichier de configuration pour la connexion à la BDD

if(isset($_SESSION["idUser"])){
    header("location: index.php");
}

// On vérifie si le formulaire a été soumis
if (isset($_POST['submit'])) {
    $login = trim($_POST['login']);
    $password = htmlspecialchars($_POST['password']);

    if (!empty($login) && !empty($password)) {
        // On prépare la requête pour vérifier si l'utilisateur est un admin
        $stmt = $conn->prepare("SELECT u.idU, u.password 
                                FROM pact._admin a 
                                JOIN pact._utilisateur u ON a.idU = u.idU 
                                WHERE a.login = ?");
        $stmt->execute([$login]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch result as an associative array

        // Vérification du mot de passe pour l'admin
        if ($admin && $password == $admin['password']) { // Remplacez par password_verify si les mots de passe sont hashés
            $_SESSION['idUser'] = $admin['idU'];
            $_SESSION['user_type'] = 'admin';
            header("Location: detailsOffer.php"); // Redirection vers la page admin
            exit(); // S'assurer que le script s'arrête après la redirection
        } else {
            // Si ce n'est pas un admin, on vérifie si c'est un membre non admin
            $stmt = $conn->prepare("SELECT u.idU, u.password 
                                    FROM pact._membre m 
                                    JOIN pact._utilisateur u ON m.idU = u.idU 
                                    WHERE m.pseudo = ?");
            $stmt->execute([$login]);
            $membre = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch result as an associative array

            // Vérification du mot de passe pour le membre
            if ($membre && $password == $membre['password']) { // Remplacez par password_verify si les mots de passe sont hashés
                $_SESSION['idUser'] = $membre['idU'];
                $_SESSION['user_type'] = 'membre';
                header("Location: membre_dashboard.php"); // Redirection vers la page membre
                exit(); // S'assurer que le script s'arrête après la redirection
            } else {
                $error = "Identifiants incorrects";
            }
        }
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        form {
            display: flex;
            flex-direction: column;
            width: 300px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        input {
            margin-bottom: 10px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            padding: 10px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
            font-size: 14px;
        }
        h2 {
            text-align: center;
        }
    </style>
</head>
<body>
    <form action="connexionKylian.php" method="POST">
        <h2>Connexion</h2>
        <?php if (isset($error)) { echo '<p class="error">' . $error . '</p>'; } ?>
        <input type="text" name="login" placeholder="Login" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit" name="submit">Se connecter</button>
    </form>
</body>
</html>
