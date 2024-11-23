<?php
class Offer {
  private $idUser;
  private $typeUser;
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

  public function __construct($categorie_) {
    $this->options = [];
    $this->images = [];
    $this->tags = [];
    $this->categorie = $categorie_;
  }

  // TODO
  public function setData($idOffre_, $typeUser_, $idUser_, $nomOffre_) {
    $this->idOffre = $idOffre_;
    $this->typeUser = $typeUser_;
    $this->idUser = $idUser_;
    $this->nomOffre = $nomOffre_;
  }

  /** TODO
   * Exemple d'utilisation 
   * list($idOffre, $tag ...) = $instance->getCardOffer();
   */
  public function getCardOffer() {
    return [$this->idOffre];
  }

  // 
  public function displayCardOffer() {
    list($idOffre) = $this->getCardOffer();
    require_once "../components/cardOffer.php";
  }

  public function filterPagination($idUser_) {
    if (($this->typeUser == "pro_public" || $this->typeUser == "pro_prive")) {
      return $this->idUser == $idUser_;
    } else {
      return $this->statut == 'actif';
    }
  }
}

?>