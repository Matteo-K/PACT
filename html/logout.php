<?php
    session_start(); // ouvre une session

    // si l'utilisateur est connecté on detruit sa session pour le deconnecter
    if(isset($_SESSION["idUser"])){ 
        $_SESSION = [];
        session_destroy();
    }

    // changement de compte
    if(isset($_GET["change"])) {
        header("location: login.php");
        exit();
    }

    // retour sur la page d'accueil
    header("location: index.php"); 
    exit();
?>