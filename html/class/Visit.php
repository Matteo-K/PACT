<?php
require_once "Offer.php";

// TODO
class Visit extends Offer {
  private $estGuide;
  private $duree;
  private $prixMinimal;
  private $accessibilite;
  private $handicap; // adaptation bdd
  private $langue;
  private $horaireMidi;
  private $horaireSoir;

  public function __construct() {
    parent::__construct("Visite");
    $this->handicap = [];
    $this->langue = [];
  }

  public function setDataVisit($estGuide_, $duree_, $prixMinimal_, $accessibilite_, $handicap_, $langue_) {
    $this->estGuide = $estGuide_;
    $this->duree = $duree_;
    $this->prixMinimal = $prixMinimal_;
    $this->accessibilite = $accessibilite_;
    $this->handicap = $handicap_;
    $this->langue = $langue_;
  }

  public function getData() {
    $parentData = parent::getData();

    return array_merge($parentData, [
      "estGuide" => $this->estGuide,
      "duree" => $this->duree,
      "prixMinimal" => $this->prixMinimal,
      "accessibilite" => $this->accessibilite,
      "handicap" => $this->handicap,
      "langue" => $this->langue
    ]);
  }
}

?>