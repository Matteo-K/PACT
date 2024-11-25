<?php

// Récupérer l'heure actuelle et le jour actuel
setlocale(LC_TIME, 'fr_FR.UTF-8');
date_default_timezone_set('Europe/Paris');

// Récupérer le jour actuel en français avec la classe DateTime
$currentDay = (new DateTime())->format('l'); // Récupère le jour en anglais

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

// Convertir le jour actuel en français
$currentDay = $daysOfWeek[$currentDay];
$currentTime = new DateTime(date('H:i'));

/**
 * Détermine le statut ouvert/fermé 
 * suivant les horaires déterminés et l'horaire actuelle
 */
function statutOuverture($soir, $midi) {
  $horaires = array_merge($soir, $midi);
  $ouverture = "EstFermé";
  
  // Vérification de l'ouverture en fonction de l'heure actuelle et des horaires
  foreach ($horaires as $horaire) {
      if ($horaire['jour'] == $currentDay) {
          $heureOuverture = DateTime::createFromFormat('H:i', $horaire['heureouverture']);
          $heureFermeture = DateTime::createFromFormat('H:i', $horaire['heurefermeture']);
          if ($currentTime >= $heureOuverture && $currentTime <= $heureFermeture) {
              $ouverture = "EstOuvert";
              break;
      }
    }
  }
  return $ouverture;
}

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
  private $pays;
  private $numerorue;
  private $rue;
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
    $noteAvg = $this->noteAvg;

    // Récupération ouvert Fermé
    $restaurantOuvert = statutOuverture($this->horaireSoir, $this->horaireMidi);
    require_once __DIR__."/../components/cardOffer.php";
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
    $noteAvg = $this->noteAvg;
    $restaurantOuvert = statutOuverture($resultsSoir, $resultsMidi);
    $statut = $this->statut;
    require_once __DIR__."/../components/cardOfferPro.php";
  }

  public function filterPagination($idUser_, $typeUser_) {
    if (($typeUser_ == "pro_public" || $typeUser_ == "pro_prive")) {
      return $this->idUser == $idUser_;
    } else {
      return $this->statut == 'actif';
    }
  }

  // TODO
  public function setData($idOffre_, $idUser_, $nomOffre_, $description_, $resume_, $images_, $tags_, $ville_, $pays_, $numerorue_ , $rue_, $codePostal_, $statut_, $horaireMidi_, $horaireSoir_) {
    $this->idOffre = $idOffre_;
    $this->statut = $statut_;
    $this->idUser = $idUser_;
    $this->nomOffre = $nomOffre_;
    $this->resume = $resume_;
    $this->description = $description_;
    $this->images = $images_;
    $this->tags = $tags_;
    $this->ville = $ville_;
    $this->pays = $pays_;
    $this->numerorue = $numerorue_;
    $this->rue = $rue_;
    $this->codePostal = $codePostal_;
    $this->horaireMidi = $horaireMidi_;
    $this->horaireSoir = $horaireSoir_;
  }

  public function getData() {
    return [
      "idU" => $this->idUser, 
      "idOffre" => $this->idOffre,
      "statut" => $this->statut, 
      "abonnement" => $this->abonnement,
      "option" => $this->options, 
      "nomOffre" => $this->nomOffre,
      "resume" => $this->resume, 
      "description" => $this->description,
      "categorie" => $this->categorie, 
      "noteAvg" => $this->noteAvg,
      "images" => $this->images, 
      "tags" => $this->tags,
      "ville" => $this->ville,
      "pays" => $this->pays,
      "numeroRue" => $this->numerorue,
      "rue" => $this->rue,
      "codePostal" => $this->codePostal,
      "horaireMidi" => $this->horaireMidi,
      "horaireSoir" => $this->horaireSoir
    ];
  }
}

?>