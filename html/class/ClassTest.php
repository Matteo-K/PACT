<?php
require_once __DIR__."/../config.php";

$ar = new ArrayOffer();

$array = $ar->recherche(15,"paster","culturel");

foreach ($array as $value) {
  $value->getData()["nomOffre"];
}

?>