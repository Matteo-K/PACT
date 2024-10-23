<?php
session_start(); // ouvre une session
if(isset($_SESSION["idUser"])){ //si l'utilisateur est connecté on detruit sa session pour le deconnecter
    $_SESSION = [];
    session_destroy();
}

if(isset($_GET["change"])) {
    header("location: connexion.php");
    exit();
}

header("location: index.php"); // retour sur la page d'accueil
exit();
?>