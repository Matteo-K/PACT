<?php
session_start();
require_once "db.php";

$isLoggedIn = isset($_SESSION["idUser"]);
if($isLoggedIn){
    
    $idUser = $_SESSION["idUser"];
    $typeUser = $_SESSION["typeUser"];
}else{
    $typeUser = "visiteur";
}
?>