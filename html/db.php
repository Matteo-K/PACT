<?php
include('./connectionBDD/connect_params.php');
    $conn = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
    foreach($conn->query('SELECT u.idU, u.password, m.pseudo FROM pact._membre m JOIN pact._utilisateur u ON m.idU = u.idU ', PDO::FETCH_ASSOC) as $row) {
        echo "<pre>";
        print_r($row);
        echo "</pre>";
    }
    $conn = null;
?>