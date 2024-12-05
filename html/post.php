<?php

print_r($_POST);
// Définir la locale en français
setlocale(LC_TIME, 'fr_FR.UTF-8');

// Supposons que `$dates` contient la date en format "Y-m-d"
$date = $_POST["dates"];
$dates = $date[1]["trip-start"]; // Par exemple : "2024-12-05"

// Formater la date en français
$timestamp = strtotime($dates);
$formattedDate = strftime("%A %Y-%m-%d", $timestamp); // Format : "jeudi 2024-12-05"

// Découper en jour de la semaine et date
$jour = explode(" ", $formattedDate);

print_r($jour);



?>