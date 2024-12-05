<?php

// Récupérer l'heure actuelle et le jour actuel
setlocale(LC_TIME, 'fr_FR.UTF-8');
date_default_timezone_set('Europe/Paris');

// Récupérer le jour actuel en français avec la classe DateTime
$currentDay = (new DateTime())->format('l'); // Récupère le jour en anglais
$currentDate = date("Y-m-d");

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
  private $mail;
  private $telephone;
  private $urlSite;
  private $dateCreation;
  private $noteAvg;
  private $nbNote;
  private $images;
  private $tags;
  private $ville;
  private $pays;
  private $numerorue;
  private $rue;
  private $codePostal;
  private $avis;

  public function __construct($categorie_) {
    $this->options = [];
    $this->images = [];
    $this->tags = [];
    $this->avis = [];
    $this->categorie = $categorie_;
  }

  /** 
   * Affichage des cartes d'offres
   */
  public function displayCardOffer() {
    $idOffre = $this->idOffre;
    $nomOffre = $this->nomOffre;
    $urlImg = $this->images[0] ?? "";
    $gammeText = "";
    $ville = $this->ville;
    $nomTag = $this->categorie;
    $tag = $this->tags;
    $resume = $this->resume;
    $noteAvg = $this->noteAvg;

    $restaurantOuvert = statutOuverture($this->horaireSoir, $this->horaireMidi) ?? ""; // à remplacer
    require __DIR__."/../components/cardOffer.php";
  }

  public function displayCardOfferPro() {
    $idOffre = $this->idOffre;
    $nomOffre = $this->nomOffre;
    $urlImg = $this->images[0] ?? "";
    $gammeText = "";
    $ville = $this->ville;
    $nomTag = $this->categorie;
    $tag = $this->tags;
    $resume = $this->resume;
    $noteAvg = $this->noteAvg;
    $restaurantOuvert = statutOuverture($this->horaireSoir, $this->horaireMidi) ?? ""; // à remplacer
    $statut = $this->statut;
    require __DIR__."/../components/cardOfferPro.php";
  }

  public function displayCardALaUne() {
    $idOffre = $this->idOffre;
    $nomOffre = $this->nomOffre;
    $resume = $this->resume;
    $urlImg = $this->images[0] ?? "";
    $ville = $this->ville;
    $categorie = $this->categorie;
    $tags = $this->tags;
    $noteAvg = $this->noteAvg;
    $nbNote = $this->nbNote;
    $codePostal = $this->codePostal;
    require __DIR__."/../components/cardALaUne.php";
  }

  public function displayCardALaUnePro() {
    $idOffre = $this->idOffre;
    $nomOffre = $this->nomOffre;
    $urlImg = $this->images[0] ?? "";
    $ville = $this->ville;
    $categorie = $this->categorie;
    $tags = $this->tags;
    $noteAvg = $this->noteAvg;
    $nbNote = $this->nbNote;
    $codePostal = $this->codePostal;
    $statut = $this->statut;
    $abonnement = $this->abonnement;
    $options = $this->options;
    require __DIR__."/../components/cardALaUnePro.php";
  }

  public function horaireToJSON($horaire) {
    $formattedResultats = [];
    foreach ($horaire as $result) {
      $formattedResultats[] = json_encode([
          'jour' => $result['jour'],
          'heureOuverture' => $result['heureouverture'],
          'heureFermeture' => $result['heurefermeture']
      ]);
    }
    return $formattedResultats;
  }

  public function horairePrecisToJSON($horaire) {
    $formattedResultats = [];
    foreach ($horaire as $result) {
      $formattedResultats[] = json_encode([
        'jour' => $result['jour'],
        'heureouverture' => $result['heureouverture'],
        'heurefermeture' => $result['heurefermeture'],
        'daterepresentation' => $result['daterepresentation']
      ]);
    }
  }

  public function avisToJSON($avis) {
    $formattedResultats = [];
    foreach ($avis as $result) {
      $formattedResultats[] = json_encode([
        'titre' => $result['titre'],
        'contenue' => $result['contenue'],
        'pseudo' => $result['pseudo'],
        'note' => $result['note']
      ]);
    }
    return $formattedResultats;
  }

  /**
   * Détermine le statut ouvert/fermé 
   * suivant les horaires déterminés et l'horaire actuelle
   */
  public function statutOuverture($soir, $midi = null) {
    global $currentDay, $currentTime, $currentDate;
    $ouverture = "EstFermé";
    if ($this->categorie != "Spectacle") {
      $horaires = array_merge($soir, $midi);
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
    } else {
      foreach ($soir as $horaire) {
        if ($horaire['daterepresentation'] == $currentDate) {
          $heureOuverture = DateTime::createFromFormat('H:i', $horaire['heureouverture']);
          $heureFermeture = DateTime::createFromFormat('H:i', $horaire['heurefermeture']);
          if ($currentTime >= $heureOuverture && $currentTime <= $heureFermeture) {
            $ouverture = "EstOuvert";
            break;
          }
        }
      }
    }
    return $ouverture;
  }

  public function filterPagination($idUser_, $typeUser_) {
    if (($typeUser_ == "pro_public" || $typeUser_ == "pro_prive")) {
      return $this->idUser == $idUser_;
    } else {
      return $this->statut == 'actif';
    }
  }

  public function setData($idOffre_, $idUser_, $nomOffre_, $abonnement_, $options_, $description_, $resume_, $mail_, $telephone_, $urlsite_, $dateCreation_, $images_, $tags_, $ville_, $pays_, $numerorue_ , $rue_, $codePostal_, $statut_, $noteAvg_, $nbNote_, $avis_) {
    $this->idOffre = $idOffre_;
    $this->statut = $statut_;
    $this->idUser = $idUser_;
    $this->nomOffre = empty($nomOffre_) ? "Offre ". $this->idOffre : $nomOffre_;
    $this->resume = empty($resume_) ? "" : $resume_;
    $this->description = empty($description_) ? "" : $description_;
    $this->images = $images_;
    $this->images = array_filter($this->images, function($image) {
      return $image != NULL;
    });
    
    $this->images = array_values($this->images);
    
    if (empty($this->images)) {
        $this->images = [];
    }
    $this->tags = $tags_;
    foreach ($this->tags as &$tag) {
      $tag = str_replace('_', ' ', $tag);
    }
    $this->ville = empty($ville_) ? "" : $ville_;
    $this->pays = empty($pays_) ? "" : $pays_;
    $this->numerorue = $numerorue_;
    $this->rue = $rue_;
    $this->codePostal = $codePostal_;
    $this->mail = empty($mail_) ? "" : $mail_;
    $this->telephone = empty($telephone_) ? "" : $telephone_;
    //$this->urlsite = $urlsite_;
    $this->dateCreation = $dateCreation_;
    $this->abonnement = $abonnement_;
    $this->options = $options_;
    $this->noteAvg = number_format($noteAvg_,1);
    $this->nbNote = $nbNote_;
    $this->avis = $avis_;
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
      "mail" => $this->mail,
      "telephone" => $this->telephone,
      //"urlSite" => $this->urlsite,
      "dateCreation" => $this->dateCreation,
      "categorie" => $this->categorie, 
      "noteAvg" => $this->noteAvg,
      "nbNote" => $this->nbNote,
      "images" => $this->images, 
      "tags" => $this->tags,
      "ville" => $this->ville,
      "pays" => $this->pays,
      "numeroRue" => $this->numerorue,
      "rue" => $this->rue,
      "codePostal" => $this->codePostal,
      "avis" => $this->avisToJSON($this->avis)
    ];
  }

  public function formaterAdresse() {
    return $this->numerorue . ' ' . $this->rue . ', ' . $this->codePostal . ' ' . $this->ville . ', ' . $this->pays;
  }
}

?>