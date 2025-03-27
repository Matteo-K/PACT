<?php

// Tableau pour convertir les jours de la semaine de l'anglais au français
$daysOfWeek = [
    'Monday'    => 'Lundi',
    'Tuesday'   => 'Mardi',
    'Wednesday' => 'Mercredi',
    'Thursday'  => 'Jeudi',
    'Friday'    => 'Vendredi',
    'Saturday'  => 'Samedi',
    'Sunday'    => 'Dimanche'
];

class Offer {

  protected $data = null;

  // format : ["nom class", "attribut BDD"]
  static public $attributs = [
    "idU" => "idu", 
    "nomUser" => "denomination", 
    "idOffre" => "idoffre",
    "statut" => "statut", 
    "abonnement" => "nomabonnement",
    "option" => "nomoption", 
    "nomOffre" => "nom",
    "resume" => "resume", 
    "description" => "description",
    "mail" => "mail",
    "telephone" => "telephone",
    //"urlSite" => "",
    "dateCreation" => "datecrea",
    "categorie" => "categorie", 
    "noteAvg" => "moyenne",
    "nbNote" => "total",
    "images" => "listimage", 
    "tags" => "all_tags",
    "ville" => "ville",
    "pays" => "pays",
    "numeroRue" => "numerorue",
    "rue" => "rue",
    "codePostal" => "codepostal"
  ];

  public function __construct($idOffre_, $categorie_) {
    $this->data["idOffre"] = $idOffre_;
    $this->data["categorie"] = $categorie_;
  }

  /**
   * Affiche les cartes d'offre
   */
  public function displayCardOffer() {
    // Chargement des données
    $attributs = ["idOffre", "nomOffre", "nomUser", "resume", "images",
      "ville", "pays", "numeroRue", "rue", "codePostal", "categorie",
      "tags", "noteAvg", "nbNote", "option"
    ];
    $this->loadData($attributs);

    // Initialisation des valeurs de la carte
    $idOffre = $this->data["idOffre"];
    $nomOffre = $this->data["nomOffre"]; // a suivre
    $nomUser = $this->nomUser;
    $resume = $this->resume;
    $urlImg = $this->images[0] ?? "";
    $ville = $this->ville;
    $numerorue = $this->numerorue;
    $rue = $this->rue;
    $codePostal = $this->codePostal;
    $categorie = $this->categorie;
    $tags = $this->tags;
    $noteAvg = $this->noteAvg;
    $nbNote = $this->nbNote;
    $options = $this->options;

    // Affichage de la carte
    require __DIR__."/../components/cardOffer.php";
  }

  /**
   * Charge les données communes aux offres
   * @param attributs_ attributs des données
   * @return array liste des attributs chargé
   */
  public function loadData($attributs_ = []) {
    global $conn;
    $idOffre = $this->data["idOffre"];
    $ALL = empty($attributs_);
    $resAttr = $attributs_;
    $allOffre = ['idU', 'nomOffre', 'description', 'resume', 'mail', 'telephone', 'dateCreation', 'abonnement', 'images', 'ville', 'numerorue', 'rue', 'codePostal', 'tags', 'statut'];
    $allUser = ['nomUser'];

    if ($ALL) {
      $columns = implode(", ", array_map([$this, 'getAttribut'], $allOffre));

    } else {
      // Séparation des attributs par table
      $attributOffre = array_intersect($attributs_, $allOffre);
      $attributUtilisateur = array_intersect($attributs_, $allUser);
      $attributNote = array_intersect($attributs_, ['noteAvg', 'nbNote']);
      $attributOption = array_intersect($attributs_, ['option']);
      
      $resAttr = array_merge($attributOffre, $attributUtilisateur, $attributNote, $attributOption);

      if (!empty($attributOffre)) {
        $columns = implode(", ", array_map([$this, 'getAttribut'], $attributOffre));
      }
    }

    /** 1️⃣ CHARGEMENT DES DONNÉES PRINCIPALES (offres) **/
    if (isset($columns)) {
      $stmt = $conn->prepare("SELECT $columns FROM pact.offres WHERE idoffre = ?");
      $stmt->execute([$idOffre]);
      $resOffre = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($resOffre) {
        foreach ($attributOffre as $attrbt) {
          $this->data[$attrbt] = $resOffre[$attrbt];
        }
      }
    }

    /** 2️⃣ CHARGEMENT DES DONNÉES DE L'UTILISATEUR **/
    if ($attributUtilisateur || $ALL) {
      
      if ($ALL) {
        $columns = implode(", ", array_map([$this, 'getAttribut'], $allUser));

      } else {
        $columns = implode(", ", array_map([$this, 'getAttribut'], $attributUtilisateur));

      }

      $stmt = $conn->prepare("SELECT $columns FROM pact._pro WHERE idoffre = ?");
      $stmt->execute([$idOffre]);
      $resUser = $stmt->fetchAll(PDO::FETCH_COLUMN);

      if ($resUser) {
        foreach ($attributUtilisateur as $attrbt) {
          $this->data[$attrbt] = $resUser[$attrbt];
        }
      }
    }

    /** 3️⃣ CHARGEMENT DES NOTES (_avis) **/
    if ($attributHandicap || $ALL) {
      $stmt = $conn->prepare("SELECT avg(note) as moyenne, count(note) as total from pact._avis where idoffre = ? group by idoffre");
      $stmt->execute([$idOffre]);
      $resNote = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($resNote) {
        $this->data["noteAvg"] = $resNote["moyenne"] ?? 0;
        $this->data["nbNote"] = $resNote["total"] ?? 0;
      }
    }

    /** 4️⃣ CHARGEMENT DES OPTIONS (_option_offre) **/
    if ($attributOption || $ALL) {
      $this->data["option"] = [];
      $stmt = $conn->prepare("SELECT nomoption from pact._option_offre natural join pact._dateoption where idoffre = ? and datefin >= CURRENT_DATE and datelancement <= CURRENT_DATE");
      $stmt->execute([$idOffre]);
      while ($resOption = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if (!in_array($resOption["nomoption"], $options)) {
          $this->data["option"][] = $resOption["nomoption"];
        }
      }
    }

    return $resAttr;
  }
  
  /**
   * Récupération des données communes aux offres
   * @param attributs_ attributs des données
   */
  public function getData($attributs_ = []) {
    $attr = $this->loadData($attributs_);
    foreach ($attr as $attribut) {
      $res[$attribut] = $data[$attribut];
    }
    return $res;
  }
  
  /**
   * Récupère l'id de l'offre
   */
  public function getIdOffre() {
    return $this->data["idOffre"];
  }

  public function getAttribut($elem) {
    return Offer::$attributs[$elem] ?? '';
  }

  public function formaterAdresse() {
    return $this->numerorue . ' ' . $this->rue . ', ' . $this->codePostal . ' ' . $this->ville . ', ' . $this->pays;
  }
}

?>