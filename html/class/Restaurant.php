<?php
require_once "Offer.php";

class Restaurant extends Offer {
  private $gammeDePrix;
  private $urlMenu; // Adaptation bdd
  private $horaireMidi;
  private $horaireSoir;

  public function __construct() {
    parent::__construct("Restaurant");
  }

  public function setDataRestaurant($gammeDePrix_) {
    $this->gammeDePrix = $gammeDePrix_;
    $this->urlMenu = "";
  }

  public function getData() {
    $parentData = parent::getData();

    return array_merge($parentData, [
      "gammeDePrix" => $this->gammeDePrix,
      "UrlMenu" => $this->urlMenu
    ]);
  }
}
?>