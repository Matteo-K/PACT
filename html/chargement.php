<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Chargement...</title>
    <style>
        /* Styles du loader */
        body {
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: white;
        } 
    </style>
</head>
<body>

    <img id="logo" src="../img/logo.png" title="logo du site" class="chargementActif">
    <div>
        <h1 id="logoText">PACT</h1>
    </div>





    <script>
        // Créer une iframe invisible pour précharger accueil.php
        let iframe = document.createElement("iframe");
        iframe.src = "accueil.php";
        iframe.style.display = "none";
        document.body.appendChild(iframe);

        // Vérifier quand l'iframe est chargée et rediriger
        iframe.onload = function() {
            window.location.href = "index.php";
        };

        // Sécurité : Si au bout de 10 secondes ce n'est pas chargé, forcer la redirection
        setTimeout(() => {
            window.location.href = "index.php";
        }, 10);
    </script>
</body>
</html>
