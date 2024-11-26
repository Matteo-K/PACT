<?php
require_once __DIR__."/../config.php";

$ar = new ArrayOffer();

$array = $ar->recherche(15,"paster","culturel");

$ar->displayArrayCard($array, 0 , 100);

?>