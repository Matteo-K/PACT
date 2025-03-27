<?php

class Park extends Offer implements Categorie {
  protected $parkData = null;
  static public $attributs = [
    "ageMinimal" => "agemin",
    "nbAttraction" => "nbattraction",
    "prixMinimal" => "prixminimal",
    "urlPlan" => "urlplan",
  ];

  public function __construct($idOffre) {
    parent::__construct($idOffre, "Parc Attraction");
  }

  public function loadData($attribut = []) {
    global $conn;

    $idOffre = parent::getIdOffre();

    // Séparation des attributs par table
    $attributPark = array_intersect($attribut, []);
  }

  public function getData($parentAttribut = [], $thisAttribut = []) {
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