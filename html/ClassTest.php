<?php require_once "config.php"; 
$idOffre = 3; 
$ar = new ArrayOffer($idOffre);
print_r($ar->getArray());
$arTout = new ArrayOffer();
print_r($arTout->getArray());
?>


