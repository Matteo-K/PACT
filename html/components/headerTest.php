
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>PACT</title>
    <style>
        /* Style général du menu de profil */
        #profileMenu {
            color: #fff; /* Couleur du texte */
            margin: 0;
            position: fixed;
            top: 0;
            right: 0;
            height: 100%; /* Prend toute la hauteur de la page */
            width: 300px; /* Largeur du menu */
            background: linear-gradient(to bottom, #034d7c, #1ca4ed); /* Dégradé du haut vers le bas */
            display: flex;
            flex-direction: column; /* Aligne les éléments verticalement */
            z-index: 1000;
            transition: transform 0.3s ease, visibility 0.3s ease, opacity 0.3s ease; /* Transition pour le mouvement */
            transform: translateX(100%); /* Commence en dehors de l'écran à droite */
            visibility: hidden; /* Caché par défaut */
            opacity: 0; /* Invisible par défaut */
        }

        #profileMenu.show {
            visibility: visible; /* Rendre l'élément visible */
            opacity: 1; /* Complètement opaque */
            transform: translateX(0); /* Ramène le menu dans la vue */
        }

        #profileMenu.hide {
            visibility: visible; /* Garder visible pendant la sortie */
            opacity: 0; /* Rendre invisible */
            transform: translateX(100%); /* Retourne à la position initiale */
        }

        /* Conteneur pour l'image et le pseudo */
        #imagProfil {
            margin-top: 4em;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        /* Styles pour l'image de profil dans le menu */
        #imagProfil img {
            margin: 0;
            width: 10em; /* Ajustez à la taille désirée, par exemple 100px */
            border-radius: 50%; /* Pour rendre l'image ronde */
        }

        /* Styles pour le pseudo ou le nom */
        #imagProfil figcaption {
            text-shadow: black -2px 2px;
            text-align: center; /* Centre le texte sous l'image */
            font-size: 20px; /* Taille de la police */
            font-weight: bold; /* Texte en gras */
            margin: 0; /* Supprime la marge par défaut */
            color: #fff; /* Couleur du texte pour meilleure visibilité */
        }

        #backButton {
            margin: 0;
            font-size: 12px;
            cursor: pointer; /* Pointeur pour indiquer un élément cliquable */
            margin-bottom: 2em; /* Espacement en bas */
            position: absolute; /* Position absolue pour le placer dans le coin */
            left: 10px;
            top: 0;
        }

        /* Styles pour les liens du menu */
        #profileMenu ul {
            list-style-type: none; /* Supprime les puces */
            padding: 0; /* Supprime le padding */
            flex-grow: 1; /* Permet à la liste de s'étendre */
            display: flex;
            flex-direction: column; /* Aligne les éléments verticalement */
            justify-content: center; /* Centre les éléments verticalement */
        }

        #profileMenu ul li {
            margin: 10px 0; /* Espacement entre les éléments */
        }

        #profileMenu a {
            color: #fff; /* Couleur des liens */
            text-decoration: none; /* Supprime le soulignement */
            transition: color 0.3s; /* Animation de couleur */
            padding: 10px; /* Ajoute du padding */
            text-align: center; /* Centre le texte */
            border-radius: 5px; /* Coins arrondis */
        }

        #profileMenu ul a:hover {
            background-color: rgba(255, 255, 255, 0.1); /* Couleur de fond au survol */
        }

        header ul + div{
            width: 100%;
            flex-direction: column;
            margin-bottom: 3em; /* Espacement en bas */
            gap:10px;
        }
        .buttonMenu {
            display: flex;
            align-items: center;
            justify-content: center; /* Centre horizontalement et verticalement */
            width: 75%; /* Largeur des boutons */
            padding: 10px; /* Ajoute un peu de padding */
            border-radius: 0.5em;
            text-align: center;
            margin: 0 auto; /* Centre le bouton horizontalement dans son conteneur */
            text-decoration: none; /* Supprime le soulignement */
            }

        
        #changeAccount{
            background-color: #034d7ccc;
        }
        #changeAccount:hover{
            background-color: #034d7c;
        }

        #logoutButton {
            background-color: #ff0000cc;
            margin-bottom: 2em; /* Espacement en bas */
        }

        #logoutButton:hover{
            background-color: #ff0000; 
        }
        
    </style>
</head>
<body>
<header theme="light">
    <div>
        <a href="../index.php">
            <img id="logo" src="../img/logo.png" title="logo du site">
            <div>
                <h1 id="logoText">PACT</h1>
                <?php
                if($isLoggedIn){
                    if($_SESSION["typeUser"] == "pro_public" || $_SESSION["typeUser"] === "pro_prive"){
                ?>
                <h3 id="pro">PRO</h3>
                <?php
                    }
                }
                ?>
            <div>
        </a>
    </div>
    <form method="post" action="search.php" id="formHeader">
        <input type="text" placeholder="Rechercher :">
        <button type="submit"><img src="../img/icone/loupe.png" title="icone de recherche"></button>
    </form>

    <div id="auth">
        <?php
        if ($isLoggedIn) {
            // Sélection de l'utilisateur en fonction de son type
            if ($typeUser === "admin") {
                $stmt = $conn->prepare("SELECT * FROM pact.admin WHERE idU = ?");
            } else if ($typeUser === "membre") {
                $stmt = $conn->prepare("SELECT * FROM pact.membre WHERE idU = ?");
            } else if ($typeUser === "pro_public") {
                $stmt = $conn->prepare("SELECT * FROM pact.proPublic WHERE idU = ?");
            } else if ($typeUser === "pro_prive") {
                $stmt = $conn->prepare("SELECT * FROM pact.proPrive WHERE idU = ?");
            }
            $stmt->execute([$_SESSION["idUser"]]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            ?>
           
            <img id="profilePic" src="/docker/sae/data/html/<?php echo $result["url"] ?>" title="Photo de profil utilisateur">

            <!-- Menu caché intégré dans le header -->
            <div id="profileMenu" class="hidden">
                <div id="backButton">
                    <span id="backText">< Retour</span>
                </div>
                <figure id="imagProfil">
                    <img src="<?php echo $result["url"] ?>" title="photo de profil utilisateur" id="menuProfilePic">
                    <figcaption>
                        <?php
                        if ($typeUser === "admin") {
                            echo $result["login"];
                        } else if ($typeUser === "pro_public" || $typeUser === "pro_prive") {
                            echo $result["denomination"];
                        } else if ($typeUser === "membre") {
                            echo $result["pseudo"];
                        }
                        ?>
                    </figcaption>
                </figure>
                <ul>
                    <li><a href="profile.php">Mon Profil</a></li>
                    <li><a href="settings.php">Paramètres</a></li>
                </ul>
                <div>
                    <a id="changeAccount" class="buttonMenu" href="logout.php?change=true">Changer de compte</a>
                    <a id="logoutButton" class="buttonMenu"  href="logout.php">Déconnexion</a>
                </div>
            </div>

            <?php
        } else {
            ?>
            <a href="../connexion.php"><div id="btnConn">Connexion</div></a>
        <?php
        }
        ?>
    </div>
</header>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const profilePic = document.getElementById("profilePic");
    const profileMenu = document.getElementById("profileMenu");
    const backButton = document.getElementById("backButton");

    // Fonction pour afficher/cacher le menu
    function toggleMenu() {
        if (profileMenu.classList.contains("show")) {
            profileMenu.classList.remove("show");
            profileMenu.classList.add("hide");

            // Retirer la classe "hide" après la transition
            setTimeout(() => {
                profileMenu.classList.remove("hide");
            }, 300); // Temps de la transition en ms
        } else {
            profileMenu.classList.remove("hide");
            profileMenu.classList.add("show");
        }
    }

    // Écouteur pour afficher le menu au clic sur l'image de profil
    if (profilePic) {
        profilePic.addEventListener("click", toggleMenu);
    }

    // Écouteur pour fermer le menu au clic sur le bouton "Retour"
    if (backButton) {
        backButton.addEventListener("click", toggleMenu);
    }

    // Écouteur pour fermer le menu en cliquant en dehors
    document.addEventListener("click", function(event) {
        if (!profileMenu.contains(event.target) && !profilePic.contains(event.target)) {
            if (profileMenu.classList.contains("show")) {
                toggleMenu();
            }
        }
    });
});
</script>
</body>
</html>
