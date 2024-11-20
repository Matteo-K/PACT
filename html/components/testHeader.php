<?php require_once __DIR__ . "/../config.php";?>

<style>
    header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 20px; /* Ajustez selon vos besoins */
}

header > div,
header > form,
header > div#auth {
    flex: 1;
}

#formHeader {
    display: flex;
    justify-content: center;
}

#formHeader input {
    width: 70%; /* Ajustez pour la largeur du champ */
    padding: 5px;
}

#auth {
    display: flex;
    justify-content: flex-end;
    align-items: center;
}

#auth img#profilePic {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    cursor: pointer;
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
