<?php

class Visit extends Offer implements Categorie {
  protected $horaire = null;
  protected $visitData = null;

  // format : ["nom class", "attribut BDD"]
  static public $attributs = [
    "estGuide" => "guide",
    "duree" => "duree",
    "prixMinimal" => "prixminimal",
    "accessibilite" => "accessibilite",
    "handicap" => "handicap",
    "langue" => "langue",
    "horaire"
  ];

  public function __construct($idOffre) {
    parent::__construct($idOffre, "Visite");
    $this->horaire = new Horaire();
  }

  /**
   * Charge les données spécifiques aux visites
   * @return array liste des attributs chargé
   */
  public function loadData($attributs_ = []) {
    global $conn;
    $idOffre = parent::getIdOffre();
    $ALL = empty($attributs_);
    $resAttr = $attributs_;
    $allVisite = ['guide', 'duree', 'prixminimal', 'accessibilite'];

    if ($ALL) {
      $columns = implode(", ", array_map([$this, 'getAttribut'], $allVisite));

    } else {
      // Séparation des attributs par table
      $attributVisit = array_intersect($attributs_, $allVisite);
      $attributLangues = array_intersect($attributs_, ['langue']);
      $attributHandicap = array_intersect($attributs_, ['handicap']);
      $attributHoraire = array_intersect($attributs_, ['horaire']);
      $resAttr = array_merge($attributVisit, $attributLangues, $attributHandicap, $attributHoraire);

      if (!empty($attributVisit)) {
        $columns = implode(", ", array_map([$this, 'getAttribut'], $attributVisit));
      }
    }

    /** 1️⃣ CHARGEMENT DES DONNÉES PRINCIPALES (_visite) **/
    if (isset($columns)) {
      $stmt = $conn->prepare("SELECT $columns FROM pact._visite WHERE idoffre = ?");
      $stmt->execute([$idOffre]);
      $resVisit = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($resVisit) {
        foreach ($attributVisit as $attrbt) {
          $this->visitData[$attrbt] = $resVisit[$attrbt];
        }
      }
    }

    /** 2️⃣ CHARGEMENT DES LANGUES (_visite_langue) **/
    if ($attributLangues || $ALL) {
      $stmt = $conn->prepare("SELECT langue FROM pact._visite_langue WHERE idoffre = ?");
      $stmt->execute([$idOffre]);
      $langues = $stmt->fetchAll(PDO::FETCH_COLUMN);

      if ($langues) {
        $this->visitData["langue"] = $langues;
      }
    }

    /** 3️⃣ CHARGEMENT DES HANDICAPS (_handicap) **/
    if ($attributHandicap || $ALL) {
      $stmt = $conn->prepare("SELECT type_handicap FROM pact._handicap WHERE idoffre = ?");
      $stmt->execute([$idOffre]);
      $handicaps = $stmt->fetchAll(PDO::FETCH_COLUMN);

      if ($handicaps) {
        $this->visitData["handicap"] = $handicaps;
      }
    }

    /** 4️⃣ CHARGEMENT DES HORAIRES **/
    if ($attributHoraire || $ALL) {
      $this->visitData["horaire"] = $this->horaire->getHoraire($idOffre);
    }

    return $resAttr;
  }

  public function getData($attributs_ = []) {
    $thisData = $this->loadData($attributs_);

    return array_merge(parent::getData($attributs_), $thisData);
  }

  public function getAttribut($elem) {
    return Offer::$attributs[$elem] ?? '';
  }
}

?>