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

  public function __construct($categorie_) {
    $this->options = [];
    $this->images = [];
    $this->tags = [];
    $this->categorie = $categorie_;
  }

  /** TODO 
   * Récupère les informations nécessaires pour afficher les cartes d'offres
   * Exemple d'utilisation 
   * list($idOffre, $tag ...) = $instance->getCardOffer();
   */
  public function getCardOffer() {
    return [$this->idOffre];
  }

  /** TODO
   * 
   */
  public function displayCardOffer() {
    list($idOffre) = $this->getCardOffer();
    require_once "../components/cardOffer.php";
  }

  public function filterPagination($idUser_, $typeUser_) {
    if (($typeUser_ == "pro_public" || $typeUser_ == "pro_prive")) {
      return $this->idUser == $idUser_;
    } else {
      return $this->statut == 'actif';
    }
  }

  // TODO
  public function setData($idOffre_, $idUser_, $nomOffre_, $resume_, $images_, $tags_, $ville_, $statut_) {
    $this->idOffre = $idOffre_;
    $this->statut = $statut_;
    $this->idUser = $idUser_;
    $this->nomOffre = $nomOffre_;
    $this->resume = $resume_;
    $this->images = $images_;
    $this->tags = $tags_;
    $this->ville = $ville_;
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