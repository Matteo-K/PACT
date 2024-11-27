<?php 
require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    header("Location: ../index.php");
    exit;
}


print_r($_POST);