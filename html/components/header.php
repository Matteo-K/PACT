<header theme="light">
    <!-- Menu burger pour mobile -->
    <div id="menuBurger">
        <img src="./img/icone/burger-bar.png" alt="menu burger">
    </div>

    <!-- Logo du site -->
    <div class>
        <a href="./index.php">
            <img id="logo" src="./img/logo.png" title="logo du site">
            <h1 id="logoText">PACT</h1>
        </a>
    </div>

    <!-- Formulaire de recherche (sera déplacé en bas pour mobile) -->
    <form method="post" action="search.php" id="formHeader">
        <input type="text" placeholder="Rechercher :">
        <button type="submit"><img src="./img/icone/loupe.png" title="icone de recherche"></button>
    </form>

    <!-- Bouton de connexion -->
    <div id="auth">
        <a href="./profile.php">
            <div id="btnConn">Connexion</div>
        </a>
    </div>
    <div id="auth2">
        <a href="./profile.php">
            <img id="profilePicture" src="./img/profile_picture/default.svg" alt="Photo de profil">
        </a>
    </div>
</header>
