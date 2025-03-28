<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>PACT - Chargement...</title>
</head>
<body id="#chargement">

    <div>
        <img id="logo" src="../img/logo.png" title="logo du site" class="chargementActif">
        <h1 id="logoText">PACT</h1>
    </div>
    <section>
        <p>
            Texte textuel bientôt différent du texte actuel
        </p>
    </section>


    <script>
        //On récupère l'url (paramètres GET + localisation) en cas de recherche effectuée sur une page pour recréer la bonne url
        let params = window.location.search; 
        let localisation = window.location.hash;
        let url = "pact.php" + params + localisation;

        //iframe invisible pour charger la page d'accueil
        let iframe = document.createElement("iframe");
        iframe.src = url;
        iframe.style.display = "none";
        document.body.appendChild(iframe);

        //Vérifier quand l'iframe est chargée et rediriger
        iframe.onload = function() {
            window.location.href = url;
        };

        // Si au bout de 10 secondes on est toujours sur la page, on redirige quand même
        setTimeout(() => {
            window.location.href = url;
        }, 10000);
    </script>
</body>
</html>
