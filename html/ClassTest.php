<?php 
require_once "config.php"; 
require_once "class/ArrayOffer.php";
$idOffre = 3; 
$ar = new ArrayOffer($idOffre);
print_r($ar->getArray());
$arTout = new ArrayOffer();
print_r($arTout->getArray());
?>


