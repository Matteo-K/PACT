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
    $this->horaireMidi = [];
    $this->horaireSoir = [];
  }

  public function setDataPark($ageMinimal_, $nbAttraction_, $prixMinimal_, $urlPlan_, $horaireMidi_, $horaireSoir_) {
    $this->ageMinimal = $ageMinimal_;
    $this->nbAttraction = $nbAttraction_;
    $this->prixMinimal = $prixMinimal_;
    $this->urlPlan = $urlPlan_;
    $this->horaireMidi = $horaireMidi_;
    $this->horaireSoir = $horaireSoir_;
  }

  public function getData() {
    $parentData = parent::getData();

    return array_merge($parentData, [
      "ageMinimal" => $this->ageMinimal,
      "nbAttraction" => $this->nbAttraction,
      "prixMinimal" => $this->prixMinimal,
      "urlPlan" => $this->urlPlan,
      "horaireMidi" => $this->horaireToJSON($this->horaireMidi),
      "horaireSoir" => $this->horaireToJSON($this->horaireSoir),
      "ouverture" => parent::statutOuverture($this->horaireSoir, $this->horaireMidi)
    ]);
  }
}

?>