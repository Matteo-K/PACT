<?php
session_start();
$isLoggedIn = isset($_SESSION["idUser"]);
?>

<link rel="stylesheet" href="../style.css">
<header theme="light">
    <div>
        <a href="../index.php">
            <img id="logo" src="../img/logo.png" title="logo du site">
            <h1 id="logoText">PACT</h1>
        </a>
    </div>
    <form method="post" action="search.php" id="formHeader">
        <input type="text" placeholder="Rechercher :">
        <button type="submit"><img src="../img/icone/loupe.png" title="icone de recherche"></button>
    </form>

    <div id="auth">
        <?php
        if($isLoggedIn){
            echo "test";

        } else{
            ?>
            <a href="../login.php"><div id="btnConn">Connexion</div></a>
        <?php
        }
        ?>
    </div>
</header>
