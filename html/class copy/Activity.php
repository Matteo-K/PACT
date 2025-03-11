<?php
require_once "Offer.php";

// TODO
class Activity extends Offer {
  protected $ActivityData = null;

  
  public function __construct() {
    parent::__construct("Activité");
    $this->prestation = [];
    $this->horaireMidi = [];
    $this->horaireSoir = [];
  }

  public function setDataActivity($duree_, $ageMinimal_, $prixMinimal_, $prestation_, $horaireMidi_, $horaireSoir_) {
    $this->duree = $duree_;
    $this->ageMinimal = $ageMinimal_;
    $this->prixMinimal = $prixMinimal_;
    $this->prestation = $prestation_;
    $this->horaireMidi = $horaireMidi_;
    $this->horaireSoir = $horaireSoir_;
  }

  public function getData() {
    $parentData = parent::getData();

    return array_merge($parentData, [
      "duree" => $this->duree,
      "ageMinimal" => $this->ageMinimal,
      "prixMinimal" => $this->prixMinimal,
      "prestation" => $this->prestation,
      "horaireMidi" => $this->horaireToJSON($this->horaireMidi),
      "horaireSoir" => $this->horaireToJSON($this->horaireSoir),
      "ouverture" => parent::statutOuverture($this->horaireSoir, $this->horaireMidi)
    ]);
  }
}

?>