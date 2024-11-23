<?php
require_once "Offer.php";

// TODO
class Restaurant extends Offer {
  private $gammeDePrix;
  public function __construct() {
    parent::__construct("Restaurant");
  }  
}


?>