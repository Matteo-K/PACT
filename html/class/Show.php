<?php
require_once "Offer.php";

// TODO
class Show extends Offer {
  private $horaire;
  public function __construct() {
    parent::__construct("Spectacle");
  }
}

?>