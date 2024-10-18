<?php
session_start();
if(isset($_SESSION["idUser"])){
    $_SESSION = [];
    session_destroy();
} 
header("location: index.php");
exit;
?>