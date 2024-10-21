<?php
include('./connectionBDD/connect_params.php');
    $conn = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    $conn = null;
?>