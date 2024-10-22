<?php
session_start();
$isLoggedIn = isset($_SESSION["idUser"]);
$typeUser = $_SESSION["typeUser"];
require_once "db.php";
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
            if($typeUser === "admin"){
                $stmt = $conn -> prepare("SELECT * FROM pact.admin WHERE idU = ?");

            } else if ($typeUser === "membre"){
                $stmt = $conn -> prepare("SELECT * FROM pact.membre WHERE idU = ?");

            } else if($typeUser === "pro_public"){
                $stmt = $conn -> prepare("SELECT * FROM pact.proPublic WHERE idU = ?");

            } else if($typeUser === "pro_prive"){
                $stmt = $conn -> prepare("SELECT * FROM pact.proPrive WHERE idU = ?");
            }
            $stmt -> execute([$_SESSION["idUser"]]);
            $result = $stmt -> fetch(PDO :: FETCH_ASSOC);
            ?>
           
            <img src="<?php echo $result["url"] ?>" title="Photo de profile utilisateur">
            <?php

        } else{
            ?>
            <a href="../login.php"><div id="btnConn">Connexion</div></a>
        <?php
        }
        ?>
    </div>
</header>
