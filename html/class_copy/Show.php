<?php

class Show extends Offer implements Categorie {
  protected $showData = null;
  static public $attributs = [
    "duree" => "duree",
    "prixMinimal" => "prixminimal",
    "nbPlace" => "nbplace"
  ];

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