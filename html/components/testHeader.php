<?php require_once __DIR__ . "/../config.php";?>

<style>
    /* ### Header ### */

header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: var(--bloc) 2px solid;
  
  flex-direction: row;
  
  
  gap: 12em;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: var(--header-size);
  z-index: 10;
  background-color: var(--background);
}

header > div,
header > form,
header > div#auth {
    flex: 1;
}

header div {
  display: flex;
  flex-direction: row;
  align-items: center;
  margin: 0.5em 0.8em;
  height: 3em;
}

header div>a {
  display: flex;
  flex-direction: row;
  text-decoration: none;
  align-items: center;
  /* Alignement vertical de l'image et du texte */
}

header h1 {
  font-size: 48px;
  color: var(--bloc);
}

header div>a>img,
#auth>img {
  width: 5em;
  height: 5em;
  margin-right: 0.5em;
  cursor: pointer;
}

#auth {
  display: flex;
  justify-content: flex-end;
  align-items: center;
}

/* Formulaire de recherche */
#formHeader {
  display: flex;
  justify-content: center;
  align-items: center;
  border: var(--bloc) solid 3px;
  border-radius: 2em;
  height: 3em;
  width: 35em;
  border-radius: 2em;
  /* Largeur du formulaire */
}

#formHeader input[type="text"],
#formHeader button,
#formHeader img {
  margin: 0;
  padding: 0;
  border: none;
  outline: none;
}

#formHeader input[type="text"] {
  width: 85%;
  padding-left: 1em;
  height: 100%;
  height: 100%;
  font-size: 16px;
  border-radius: 2em 0 0 2em;
}

#formHeader button {
  flex: 1;
  padding: 0 1.7em;
  background-color: var(--primary);
  border-radius: 0 2em 2em 0;
  height: 100%;
  cursor: pointer;
}

#formHeader img {
  width: 2em;
}

#auth2 {
  display: none;
}

#menuBurger {
  display: none;
}

#logoText {
  font-size: 48px;
  color: var(--bloc);
  text-align: center;
  /* Centrer le texte horizontalement */
}

#pro {
  font-family: "Lato";
  font-size: 16px;
  /* Ajuste la taille du texte "PRO" */
  color: var(--text-dark);
  text-align: center;
  /* Centrer le texte horizontalement */
  margin-top: -10px;
  /* Ajuste l'espacement entre "PACT" et "PRO" */
}

header>div>a>div {
  display: flex;
  flex-direction: column;
}

@media (max-width: 756px) {
  #copyrightFooter {
    display: none;
  }

  header {
    height: 200px;
    flex-direction: column;
    /* Passer les éléments en colonne */
    align-items: center;
    gap: 1em;
    padding: 1em;
  }

  #auth {
    display: none;
  }

  /* Afficher le menu burger */
  #menuBurger {
    display: block;
    /* Montrer l'icône du menu burger */
    position: absolute;
    left: 1em;
    top: 1em;
  }

  /* Placer le bouton de connexion à droite */
  #auth2 {
    display: block;
    position: absolute;
    right: 1em;
    top: 1em;
  }

  /* La barre de recherche se place sous le logo */
  #formHeader {
    width: 90%;
    /* Largeur à 90% de l'écran */
    margin-top: 1em;
    margin-bottom: 25px;
  }

  #profilePicture {
    width: 5em;
    /* Taille légèrement réduite pour mobile */
    height: 5em;
  }
}

#btnConn {
  display: flex;
  color: #000;
  font-size: 25px;
  border-radius: 10px;
  height: 2em;
  width: 200px;
  background-color: var(--accent);
  text-align: center;
  justify-content: center;
}

/* Style général du menu de profil */
#profileMenu {
  color: #fff;
  /* Couleur du texte */
  margin: 0;
  position: fixed;
  top: 0;
  right: 0;
  height: 100%;
  /* Prend toute la hauteur de la page */
  width: 300px;
  /* Largeur du menu */
  background: linear-gradient(to bottom,
      #034d7c,
      #1ca4ed);
  /* Dégradé du haut vers le bas */
  display: flex;
  flex-direction: column;
  /* Aligne les éléments verticalement */
  transition: transform 0.7s ease, visibility 0.7s ease, opacity 0.7s ease;
  /* Transition pour le mouvement */
  transform: translateX(100%);
  /* Commence en dehors de l'écran à droite */
  visibility: hidden;
  /* Caché par défaut */
  opacity: 0;
  /* Invisible par défaut */
}

#profileMenu.show {
  visibility: visible;
  /* Rendre l'élément visible */
  opacity: 1;
  /* Complètement opaque */
  transform: translateX(0);
  /* Ramène le menu dans la vue */
}

#profileMenu.hide {
  visibility: visible;
  /* Garder visible pendant la sortie */
  opacity: 0;
  /* Rendre invisible */
  transform: translateX(100%);
  /* Retourne à la position initiale */
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
  width: 10em;
  border-radius: 50%;
}

/* Styles pour le pseudo ou le nom */
#imagProfil figcaption {
  text-shadow: black -2px 2px;
  text-align: center;
  /* Centre le texte sous l'image */
  font-size: 20px;
  /* Taille de la police */
  font-weight: bold;
  /* Texte en gras */
  margin: 0;
  /* Supprime la marge par défaut */
  color: #fff;
  /* Couleur du texte pour meilleure visibilité */
}

#backButton {
  margin: 0;
  font-size: 20px;
  cursor: pointer;
  /* Pointeur pour indiquer un élément cliquable */
  margin-bottom: 2em;
  /* Espacement en bas */
  position: absolute;
  /* Position absolue pour le placer dans le coin */
  left: 10px;
  top: 0;
}

/* Styles pour les liens du menu */
#profileMenu ul {
  list-style-type: none;
  /* Supprime les puces */
  padding: 0;
  /* Supprime le padding */
  flex-grow: 1;
  /* Permet à la liste de s'étendre */
  display: flex;
  flex-direction: column;
  /* Aligne les éléments verticalement */
  justify-content: center;
  /* Centre les éléments verticalement */
  gap: 20px;
}

#profileMenu ul li {
  margin: 20px 0;
  /* Espacement entre les éléments */
  font-size: 30px;
  font-family: "Belanosima", sans-serif;
  font-weight: bold;
}

#profileMenu a {
  color: #fff;
  /* Couleur des liens */
  text-decoration: none;
  /* Supprime le soulignement */
  transition: color 0.5s;
  /* Animation de couleur */
  padding: 10px;
  /* Ajoute du padding */
  text-align: center;
  /* Centre le texte */
  border-radius: 10px;
  /* Coins arrondis */
}

#profileMenu ul a:hover {
  background-color: rgba(255, 255, 255, 0.2);
  /* Couleur de fond au survol */
}

header ul+div {
  width: 100%;
  flex-direction: column;
  margin-bottom: 3em;
  /* Espacement en bas */
  gap: 10px;
}

.buttonMenu {
  display: flex;
  align-items: center;
  justify-content: center;
  /* Centre horizontalement et verticalement */
  width: 75%;
  /* Largeur des boutons */
  padding: 10px;
  /* Ajoute un peu de padding */
  border-radius: 0.5em;
  text-align: center;
  margin: 0 auto;
  /* Centre le bouton horizontalement dans son conteneur */
  text-decoration: none;
  /* Supprime le soulignement */
  font-size: 20 px;
}

#changeAccount {
  background-color: #034d7ccc;
}

#changeAccount:hover {
  background-color: #034d7c;
}

#logoutButton {
  background-color: #ff0000cc;
  margin-bottom: 2em;
  /* Espacement en bas */
}

#logoutButton:hover {
  background-color: #ff0000;
}



</style>
<header>

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
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            ?>
           
            <img id="profilePic" src="<?php echo $user["url"] ?>" title="Photo de profil utilisateur">

            <!-- Menu caché intégré dans le header -->
            <div id="profileMenu" class="hidden">
                <span id="backButton">< Retour</span>
                <figure id="imagProfil">
                    <img src="<?php echo $user["url"] ?>" title="photo de profil utilisateur" id="menuProfilePic">
                    <figcaption>
                        <?php
                        if ($typeUser === "admin") {
                            echo $user["login"];
                        } else if ($typeUser === "pro_public" || $typeUser === "pro_prive") {
                            echo $user["denomination"];
                        } else if ($typeUser === "membre") {
                            echo $user["pseudo"];
                        }
                        ?>
                    </figcaption>
                </figure>
                <ul>
                    <li><a href="search.php">Mes offres</a></li>
                    <li><a href="manageOffer.php">Créer une offre</a></li>
                </ul>
                <div>
                    <a id="changeAccount" class="buttonMenu" href="logout.php?change=true">Changer de compte</a>
                    <a id="logoutButton" class="buttonMenu"  href="logout.php">Déconnexion</a>
                </div>
            </div>

            <?php
        } else {
            ?>
            <a href="../login.php"><div id="btnConn">Connexion</div></a>
        <?php
        }
        ?>
    </div>

    <div id="auth2">
        <a href="./profile.php">
            <img id="profilePicture" src="./img/profile_picture/default.svg" alt="Photo de profil">
        </a>
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
