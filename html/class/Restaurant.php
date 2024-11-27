<?php
require_once "Offer.php";

class Restaurant extends Offer {
  private $gammeDePrix;
  private $urlMenu; // Adaptation bdd
  private $horaireMidi;
  private $horaireSoir;

  public function __construct() {
    parent::__construct("Restaurant");
  }

  public function setDataRestaurant($gammeDePrix_) {
    $this->gammeDePrix = $gammeDePrix_;
    $this->urlMenu = "";
  }

  /*
  public function getData() {
    $parentData = parent::getData();

    return array_merge($parentData, [
      "gammeDePrix" => $this->gammeDePrix,
      "UrlMenu" => $this->urlMenu
    ]);
  }*/

  public function displayCardALaUne() {
    $offre = parent::getData();
    $idOffre = $offre["idOffre"];
    $nomOffre = $offre["nomOffre"];
    $resume = $offre["resume"];
    $urlImg = $offre["images"][0];
    $gammeDePrix = isset($this->gammeDePrix) ? $this->gammeDePrix : "";
    $ville = $offre["ville"];
    $categorie = $offre["categorie"];
    $tags = $offre["tags"];
    $noteAvg = $offre["noteAvg"];
    $nbNote = $offre["nbNote"];
    $codePostal = $offre["codePostal"];
    require __DIR__."/../components/cardALaUne.php";
  }
}
?>