<?php
require_once "Offer.php";

class Visit extends Offer {
  protected $visitData = null;

  public function __construct($idOffre) {
    parent::__construct($idOffre, "Visite");
  }

  /**
   * Charge les données suivant les attributs saisit
   * @param Array $attribut Liste de colonne désiré
   */
  public function loadData($attribut = []) {
    global $conn;

    $offreId = parent::getIdOffre();

    // Séparation des attributs par table
    $attributVisit = array_intersect($attribut, ['guide', 'duree', 'prixminimal', 'accessibilite']);
    $loadLangues = in_array('langue', $attribut);
    $loadHandicap = in_array('handicap', $attribut);

    /** 1️⃣ CHARGEMENT DES DONNÉES PRINCIPALES (_visite) **/
    if (empty($attribut)) {
      $columns = "*";
    } else if (!empty($attributVisit)) {
      $columns = implode(", ", $attributVisit);
    }

    if (isset($columns)) {
      $stmt = $conn->prepare("SELECT $columns FROM pact._visite WHERE idoffre = ?");
      $stmt->execute([$offreId]);
      $resVisit = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($resVisit) {
        foreach ($attributVisit as $attrbt) {
          $this->visitData[$attrbt] = $resVisit[$attrbt];
        }
      }
    }

    /** 2️⃣ CHARGEMENT DES LANGUES **/
    if ($loadLangues || empty($attribut)) {
      $stmt = $conn->prepare("SELECT langue FROM pact._visite_langue WHERE idoffre = ?");
      $stmt->execute([$offreId]);
      $langues = $stmt->fetchAll(PDO::FETCH_COLUMN);

      if ($langues) {
        $this->visitData["langue"] = $langues;
      }
    }

    /** 3️⃣ CHARGEMENT DES HANDICAPS **/
    if ($loadHandicap || empty($attribut)) {
      $stmt = $conn->prepare("SELECT type_handicap FROM pact._handicap WHERE idoffre = ?");
      $stmt->execute([$offreId]);
      $handicaps = $stmt->fetchAll(PDO::FETCH_COLUMN);

      if ($handicaps) {
        $this->visitData["handicap"] = $handicaps;
      }
    }
  }

  public function getData() {
    $parentData = parent::getData();
    $this->loadData();

    return array_merge($parentData, [
      "estGuide" => $this->visitData["guide"],
      "duree" => $this->visitData[""],
      "prixMinimal" => $this->visitData["prixMinimal"],
      "accessibilite" => $this->visitData["accessibilite"],
      "handicap" => $this->visitData["handicap"],
      "langue" => $this->visitData["langue"],

      "horaireMidi" => $this->horaireToJSON($this->horaireMidi),
      "horaireSoir" => $this->horaireToJSON($this->horaireSoir),
      "ouverture" => parent::statutOuverture($this->horaireSoir, $this->horaireMidi)
    ]);
  }
}

?>