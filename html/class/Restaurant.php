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
    $idOffre = $this->idOffre;
    $nomOffre = $this->nomOffre;
    $resume = $this->resume;
    $urlImg = $this->images[0];
    $gammeDePrix = isset($this->gammeDePrix) ? $this->gammeDePrix : "";
    $ville = $this->ville;
    $categorie = $this->categorie;
    $tags = $this->tags;
    $noteAvg = $this->noteAvg;
    $nbNote = $this->nbNote;
    $codePostal = $this->codePostal;
    require __DIR__."/../components/cardALaUne.php";
  }
}
?>