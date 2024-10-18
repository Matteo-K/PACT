<?php
require_once 'connectionBDD/connect_params2.php';
    $conn = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
?>