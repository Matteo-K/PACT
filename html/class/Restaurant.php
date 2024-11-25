<?php
require_once "../config.php";
require_once "Offer.php";

class Restaurant extends Offer {
  private $gammeDePrix;

  public function __construct() {
    parent::__construct("Restaurant");
  }

  public function setDataRestaurant($gammeDePrix_) {
    $this->gammeDePrix = $gammeDePrix_;
  }

  public function getData() {
    return [$this->gammeDePrix, parent::getData()];
  }
}
?>