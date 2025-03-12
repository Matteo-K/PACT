<?php
require_once "Offer.php";

class Activity extends Offer implements Categorie {
  protected $activityData = null;

  public function __construct($idOffre) {
    parent::__construct($idOffre, "Activité");
  }

  public function getData() {
    $parentData = parent::getData();

    return array_merge($parentData, [
      "duree" => $this->activityData["duree"],
      "ageMinimal" => $this->activityData["ageMinimal"],
      "prixMinimal" => $this->activityData["prixMinimal"],
      "prestation" => $this->activityData["prestation"],
      "horaireMidi" => $this->horaireToJSON($this->activityData["horaireMidi"]),
      "horaireSoir" => $this->horaireToJSON($this->activityData["horaireSoir"]),
      "ouverture" => parent::statutOuverture($this->activityData["horaireSoir"], $this->activityData["horaireMidi"])
    ]);
  }
}

?>