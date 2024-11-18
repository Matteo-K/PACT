<?php
include('../SECURE/connect_paramsServer.php');

try {
    $conn = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Display error message if connection fails
    echo "Database connection failed: " . $e->getMessage();
    exit(); // Stop further execution if the connection fails
}
?>
