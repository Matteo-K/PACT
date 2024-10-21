<?php
require_once 'connectionBDD/connect_params_localKylian.php';
$conn = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);

?>
