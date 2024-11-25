<?php

class Offer {
  private $idUser;
  private $idOffre;
  private $statut;
  private $abonnement;
  private $options;
  private $nomOffre;
  private $resume;
  private $description;
  private $categorie;
  private $noteAvg;
  private $images;
  private $tags;
  private $ville;
  private $adresse;
  private $codePostal;
  private $horaireMidi;
  private $horaireSoir;

  public function __construct($categorie_) {
    $this->options = [];
    $this->images = [];
    $this->tags = [];
    $this->horaireMidi = [];
    $this->horaireSoir = [];
    $this->categorie = $categorie_;
  }

  /** TODO
   * 
   */
  public function displayCardOffer() {
    $idOffre = $this->idOffre;
    $nomOffre = $this->nomOffre;
    $urlImg = $this->images[0];
    $gammeText = isset($this->gammeDePrix) ? $this->gammeDePrix : "";
    $ville = $this->ville;
    $nomTag = $this->categorie;
    $tag = $this->tags;
    $resume = $this->resume;
    $noteAvg->$this->noteAvg;
    $restaurantOuvert;
    require_once "../components/cardOffer.php";
  }

  public function displayCardOfferPro() {
    $idOffre = $this->idOffre;
    $nomOffre = $this->nomOffre;
    $urlImg = $this->images[0];
    $gammeText = isset($this->gammeDePrix) ? $this->gammeDePrix : "";
    $ville = $this->ville;
    $nomTag = $this->categorie;
    $tag = $this->tags;
    $resume = $this->resume;
    $noteAvg->$this->noteAvg;
    $restaurantOuvert ;
    $statut = $this->statut;
    require_once "../components/cardOfferPro.php";
  }

  public function filterPagination($idUser_, $typeUser_) {
    if (($typeUser_ == "pro_public" || $typeUser_ == "pro_prive")) {
      return $this->idUser == $idUser_;
    } else {
      return $this->statut == 'actif';
    }
  }

  // TODO
  public function setData($idOffre_, $idUser_, $nomOffre_, $resume_, $images_, $tags_, $ville_, $statut_, $horaireMidi_, $horaireSoir_) {
    $this->idOffre = $idOffre_;
    $this->statut = $statut_;
    $this->idUser = $idUser_;
    $this->nomOffre = $nomOffre_;
    $this->resume = $resume_;
    $this->images = $images_;
    $this->tags = $tags_;
    $this->ville = $ville_;
    $this->horaireMidi = $horaireMidi_;
    $this->horaireSoir = $horaireSoir_;
  }

  public function getData() {
    return [$this->idUser, $this->idOffre,
    $this->statut, $this->abonnement,
    $this->options, $this->nomOffre,
    $this->resume, $this->description,
    $this->categorie, $this->noteAvg,
    $this->images, $this->tags,
    $this->ville, $this->adresse,
    $this->codePostal
  ];
  }
}

?>