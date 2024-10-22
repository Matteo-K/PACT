<?php
session_start(); //ouvre une session
if(isset($_SESSION["idUser"])){ //si l'utilisateur est connecté on detruit sa session pour le deconnecter
    $_SESSION = [];
    session_destroy();
} 
header("location: index.php"); // retour sur la page d'accueil
exit;
?>