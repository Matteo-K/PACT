<?php 
require_once "config.php";
if($isLoggedIn){
    if($typeUser == "admin"){
        phpinfo();
    }   else{
        header('location: index.php');
    }
} else{
    header('location: index.php');
}

?>