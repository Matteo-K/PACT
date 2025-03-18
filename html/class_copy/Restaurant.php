<?php
require_once "Offer.php";

class Restaurant extends Offer implements Categorie {
  protected $restaurantData = null;

  public function __construct($idOffre) {
    parent::__construct($idOffre, "Restaurant");
  }

  public function getData($parentAttribut = [], $thisAttribut = []) {
    $parentData = parent::getData();

    return array_merge($parentData, [
      "gammeDePrix" => $this->gammeDePrix,
      "UrlMenu" => $this->urlMenu,
      "horaireMidi" => $this->horaireToJSON($this->horaireMidi),
      "horaireSoir" => $this->horaireToJSON($this->horaireSoir),
      "ouverture" => parent::statutOuverture($this->horaireSoir, $this->horaireMidi)
    ]);
  }

  public function displayCardOffer() {
    $offre = parent::getData();
    $idOffre = $offre["idOffre"];
    $nomOffre = $offre["nomOffre"];
    $nomUser = $offre["nomUser"];
    $resume = $offre["resume"];
    $urlImg = $offre["images"][0] ?? "";
    $gammeDePrix = isset($this->gammeDePrix) ? $this->gammeDePrix : "";
    $ville = $offre["ville"];
    $numerorue = $offre["numeroRue"];
    $rue = $offre["rue"];
    $codePostal = $offre["codePostal"];
    $categorie = $offre["categorie"];
    $tags = $offre["tags"];
    $noteAvg = $offre["noteAvg"];
    $nbNote = $offre["nbNote"];
    $options = $offre["option"];
    require __DIR__."/../components/cardTest.php";
  }
}
?>