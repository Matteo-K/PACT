<?php
require_once "Offer.php";

class Show extends Offer {
  private $duree;
  private $nbPlace;
  private $prixMinimal;
  private $horaire;

  public function __construct() {
    parent::__construct("Spectacle");
    $this->horaire = [];
  }

  public function setDataShow($duree_, $nbPlace_, $prixMinimal_, $horaire_) {
    $this->duree = $duree_;
    $this->nbPlace = $nbPlace_;
    $this->prixMinimal = $prixMinimal_;
    $this->horaire = $horaire_;
  }

  public function getData() {
    $parentData = parent::getData();

    return array_merge($parentData, [
      "duree" => $this->duree,
      "nbPlace" => $this->nbPlace,
      "prixMinimal" => $this->prixMinimal,
      "horaire" => $this->horairePrecisToJSON($this->horaire),
      "ouverture" => parent::statutOuverture($this->horaire)
    ]);
  }
}

?>