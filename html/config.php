<?php
session_start();
require_once "db.php";

$isLoggedIn = isset($_SESSION["idUser"]);

?>