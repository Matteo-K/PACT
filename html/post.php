<?php

print_r($_POST);

$dateJour = [
  "Monday"    => "Lundi",
  "Tuesday"   => "Mardi",
  "Wednesday" => "Mercredi",
  "Thursday"  => "Jeudi",
  "Friday"    => "Vendredi",
  "Saturday"  => "Samedi",
  "Sunday"    => "Dimanche"
];

$date = $_POST["dates"];
$dates = $date[1]["trip-start"];

$formattedDate = date("l Y-m-d", strtotime($dates));
$jour = explode(" ",$formattedDate);

$frenchDay = $dateJour[$jour[0]];

echo $frenchDay;

print_r($jour);


?>