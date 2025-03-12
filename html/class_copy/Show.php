<?php
require_once "Offer.php";

class Show extends Offer implements Categorie {

  public function __construct($idOffre) {
    parent::__construct($idOffre, "Spectacle");
  }

  public function loadData($attribut = []) {
  }

  public function getData($parentAttribut = [], $thisAttribut = []) {
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