<?php
require_once "Offer.php";

// TODO
class Visit extends Offer {
  private $estGuide;
  private $duree;
  private $prixMinimal;
  private $accessibilite;
  private $handicap;
  private $langue;
  private $horaireMidi;
  private $horaireSoir;

  public function __construct() {
    parent::__construct("Visite");
    $this->handicap = [];
    $this->langue = [];
    $this->horaireMidi = [];
    $this->horaireSoir = [];
  }

  public function setDataVisit($estGuide_, $duree_, $prixMinimal_, $accessibilite_, $handicap_, $langue_, $horaireMidi_, $horaireSoir_) {
    $this->estGuide = $estGuide_;
    $this->duree = $duree_;
    $this->prixMinimal = $prixMinimal_;
    $this->accessibilite = $accessibilite_;
    $this->handicap = $handicap_;
    $this->langue = $langue_;
    $this->horaireMidi = $horaireMidi_;
    $this->horaireSoir = $horaireSoir_;
  }

  public function getData() {
    $parentData = parent::getData();

    return array_merge($parentData, [
      "estGuide" => $this->estGuide,
      "duree" => $this->duree,
      "prixMinimal" => $this->prixMinimal,
      "accessibilite" => $this->accessibilite,
      "handicap" => $this->handicap,
      "langue" => $this->langue,
      "horaireMidi" => $this->horaireToJSON($this->horaireMidi),
      "horaireSoir" => $this->horaireToJSON($this->horaireSoir),
      "ouverture" => parent::statutOuverture($this->horaireSoir, $this->horaireMidi)
    ]);
  }
}

?>