<?php

print_r($_POST);
$date = $_POST["dates"];
$dates = $date[1]["trip-start"];

$formattedDate = date("l Y-m-d", strtotime($dates));
$jour = explode(" ",$formattedDate);

print_r($jour);


?>