
<header theme="light">

    <div id="menuBurger">
        <img src="./img/icone/burger-bar.png" alt="menu burger">
    </div>

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


