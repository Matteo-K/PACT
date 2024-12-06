<?php
require_once "Offer.php";

class Restaurant extends Offer {
  private $gammeDePrix;
  private $urlMenu;
  private $horaireMidi;
  private $horaireSoir;

  public function __construct() {
    parent::__construct("Restaurant");
    $this->horaireMidi = [];
    $this->horaireSoir = [];
    $this->urlMenu = [];
  }

  public function setDataRestaurant($gammeDePrix_, $urlMenu_, $horaireMidi_, $horaireSoir_) {
    $this->gammeDePrix = $gammeDePrix_;
    $this->urlMenu = $urlMenu_;
    $this->horaireMidi = $horaireMidi_;
    $this->horaireSoir = $horaireSoir_;
  }

  public function getData() {
    $parentData = parent::getData();
    $data = array_merge($parentData, [
        "gammeDePrix" => $this->gammeDePrix,
        "UrlMenu" => $this->urlMenu,
        "horaireMidi" => $this->horaireToJSON($this->horaireMidi),
        "horaireSoir" => $this->horaireToJSON($this->horaireSoir),
        "ouverture" => parent::statutOuverture($this->horaireSoir, $this->horaireMidi)
    ]);

    return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}


  public function displayCardALaUne() {
    $offre = parent::getData();
    $idOffre = $offre["idOffre"];
    $nomOffre = $offre["nomOffre"];
    $resume = $offre["resume"];
    $urlImg = $offre["images"][0] ?? "";
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