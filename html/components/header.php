<?php
require_once "../db2.php";
$isLoggedIn = isset($_SESSION["idUser"]);
if($isLoggedIn){
    $stmt = $conn->prepare("SELECT * FROM pact._utilisateur WHERE idu = ?");
    $stmt -> execute([$_SESSION["idUser"]]);
}

?>

<link rel="stylesheet" href="../style.css">
<header theme="light">
    <div>
        <a href="../index.php">
            <img id="logo" src="../img/logo.png" title="logo du site">
            <h1>PACT</h1>
        </a>
    </div>
    <form method="post" action="search.php" id="formHeader">
        <input type="text" placeholder="Rechercher :">
        <button type="submit"><img src="../img/icone/loupe.png" title="icone de recherche"></button>
    </form>

    <div id="auth">
        <?php
        if($isLoggedIn){
            $photo -> $conn -> prepare("SELECT img.url FROM _photo_profil pp JOIN _image img on pp.url = img.url WHERE pp.idU = ?");
            $photo -> execute([$_SESSION["idUser"]]);
        ?>

        <?php
        }else{
        ?>
            <a href="../login.php"><div id="btnConn">Connexion</div></a>
        <?php
        }
        ?>
        
    </div>
</header>