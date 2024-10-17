<?php
include('./connectionBDD/connect_params.php');
    $conn = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    foreach($conn->query('SELECT * from pact._utilisateur', PDO::FETCH_ASSOC) as $row) {
        echo "<pre>";
        print_r($row);
        echo "</pre>";
    }
    $conn = null;
?>