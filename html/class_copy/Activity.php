<?php
require_once "Offer.php";

class Activity extends Offer implements Categorie {
  protected $activityData = null;
  static public $attributs = [
    "duree" => "duree",
    "ageMinimal" => "agemin",
    "prixMinimal" => "prixminimal",
    "prestationInclu" => "nomPresta",
    "prestationNonInclu" => "nomPresta"
  ];

  public function __construct($idOffre) {
    parent::__construct($idOffre, "Activité");
  }

  public function loadData($attribut = []) {
    global $conn;

    $idOffre = parent::getIdOffre();

    // Séparation des attributs par table
    $attributPark = array_intersect($attribut, []);
  }

  public function getData() {
    $parentData = parent::getData();

    return array_merge($parentData, [
      "duree" => $this->activityData["duree"],
      "ageMinimal" => $this->activityData["ageMinimal"],
      "prixMinimal" => $this->activityData["prixMinimal"],
      "prestationInclu" => $this->activityData["prestationInclu"],
      "prestationNonInclu" => $this->activityData["prestationNonInclu"],
      "horaireMidi" => $this->horaireToJSON($this->activityData["horaireMidi"]),
      "horaireSoir" => $this->horaireToJSON($this->activityData["horaireSoir"]),
      "ouverture" => parent::statutOuverture($this->activityData["horaireSoir"], $this->activityData["horaireMidi"])
    ]);
  }
}

?>