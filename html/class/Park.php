<?php
require_once "Offer.php";

// TODO
class Park extends Offer {
  private $ageMinimal;
  private $nbAttraction;
  private $prixMinimal;
  private $urlPlan;
  private $horaireMidi;
  private $horaireSoir;

  public function __construct() {
    parent::__construct("Parc Attraction");
  }

  public function setDataPark($ageMinimal_, $nbAttraction_, $prixMinimal_, $urlPlan_) {
    $this->ageMinimal = $ageMinimal_;
    $this->nbAttraction = $nbAttraction_;
    $this->prixMinimal = $prixMinimal_;
    $this->urlPlan = $urlPlan_;
  }

  /*
  public function getData() {
    $parentData = parent::getData();

    return array_merge($parentData, [
      "ageMinimal" => $this->ageMinimal,
      "nbAttraction" => $this->nbAttraction,
      "prixMinimal" => $this->prixMinimal,
      "urlPlan" => $this->urlPlan
    ]);
  }*/
  
}

?>