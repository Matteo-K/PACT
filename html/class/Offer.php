<?php
abstract class Offer {
  private $idOffre;
  private $typeUser;
  private $idUser;
  private $nomOffre;
  private $resume;
  private $description;
  private $categorie;
  private $noteAvg;
  private $images;
  private $tags;
  private $ville;

  public function __construct($categorie_) {
    $this->$img = [];
    $this->$tags = [];
    $this->$categorie = $categorie_;
  }
}

?>