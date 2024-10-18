<?php
include('./connectionBDD/connect_params.php');
    $conn = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    foreach($conn->query('SELECT u.idU, u.password 
                                FROM pact._admin a 
                                JOIN pact._utilisateur u ON a.idU = u.idU 
                                WHERE a.login = admin1', PDO::FETCH_ASSOC) as $row) {
        echo "<pre>";
        print_r("uhdduiuf");
        echo "</pre>";
    }
    $conn = null;
?>