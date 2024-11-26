<?php
require_once __DIR__."/../config.php";

$ar = new ArrayOffer();

$array = $ar->recherche(15,"paster","Restaurant");

$ar->displayArrayCard($array, "paster", 0 , 100);

?>