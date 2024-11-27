<?php

require_once "../config.php";

$stmt = $conn->prepare("SELECT * FROM pact._historiquestatut where dureeenligne is null");
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

print_r($results);
?>