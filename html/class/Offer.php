<?php
class Offer {
  private $idOffre;
  private $typeUser;
  private $idUser;
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


  public function setData($idOffre_, $typeUser_, $idUser_, $nomOffre_) {
    $this->idOffre = $idOffre_;
    $this->typeUser = $typeUser_;
    $this->idUser = $idUser_;
    $this->nomOffre = $nomOffre_;
  }

  /**
   * Exemple d'utilisation 
   * list($idOffre, $type) = $instance->getCardOffer();
   */
  public function getCardOffer() {
    return [$this->idOffre, $this->$typeUser];
  }

  public function displayCardOffer() {

  }
}

?>