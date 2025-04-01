<?php
session_start();
require_once "db.php";
require_once "class/ArrayOffer.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

$isLoggedIn = isset($_SESSION["idUser"]);
if($isLoggedIn){
    
    $idUser = $_SESSION["idUser"];
    $typeUser = $_SESSION["typeUser"];
}else{
    $typeUser = "visiteur";
    $idUser = null;
}
?>