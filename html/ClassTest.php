<?php 
require_once "config.php";
require_once "class/ArrayOffer.php";

$arTout = new ArrayOffer(3);

$arTout->displayArrayCard(18, 'passeur', 0, 20);
print_r($arTout->getArray());
?>


