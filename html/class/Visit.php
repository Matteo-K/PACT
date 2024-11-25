<?php
require_once "../config.php";
require_once "Offer.php";

// TODO
class Visit extends Offer {
  public function __construct() {
    parent::__construct("Visite");
  }
}

?>