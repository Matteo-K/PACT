<?php
require_once __DIR__. "/../config.php";
require_once "Offer.php";
require_once "Park.php";
require_once "Restaurant.php";
require_once "Show.php";
require_once "Visit.php";
require_once "Activity.php";

function transformerHoraires($horaires) {
  if (empty($horaires)) {
    return [];
  }

  $resultats = [];
  $horairesArray = explode(';', $horaires);

  foreach ($horairesArray as $item) {
    $decodedItem = json_decode($item, true);
    if (json_last_error() === JSON_ERROR_NONE) {
      $resultats[] = [
        'jour' => $decodedItem['jour'],
        'idoffre' => $GLOBALS['idOffre'],
        'heureouverture' => $decodedItem['heureOuverture'],
        'heurefermeture' => $decodedItem['heureFermeture']
      ];
    }
  }
  return $resultats;
}

class ArrayOffer {
  // format : [$idOffre -> Objet, ...]
  private $arrayOffer;
  private $nbOffer;


  // TODO
  public function __construct($idoffres_ = "") {
    $this->arrayOffer = [];
    $this->nbOffer = 0;

    global $conn;

    if (empty($idoffres_)) {
        $stmt = $conn->prepare("SELECT * FROM pact.offres");
        $stmt->execute();
    } else {
        if (is_array($idoffres_)) {
            $placeholders = rtrim(str_repeat('?,', count($idoffres_)), ',');
            $stmt = $conn->prepare("SELECT * FROM pact.offres WHERE idoffre IN ($placeholders)");
            $stmt->execute($idoffres_);
        } else {
            $stmt = $conn->prepare("SELECT * FROM pact.offres WHERE idoffre = ?");
            $stmt->execute([$idoffres_]);
        }
    }
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "Effectue";

    if ($results) {
      foreach ($results as $offre) {
        echo " boucle ++ ";
        $this->nbOffer ++;
        switch ($offre['categorie']) {
          case 'Restaurant':
            $this->arrayOffer[$offre['idoffre']] = new Restaurant();
            $this->arrayOffer[$offre['idoffre']]->setDataRestaurant($offre['gammedeprix']);
            break;
          
          case 'Spectacle':
            $this->arrayOffer[$offre['idoffre']] = new Show();
            break;

          case 'Visite':
            $this->arrayOffer[$offre['idoffre']] = new Visit();
            break;

          case 'Activité':
            $this->arrayOffer[$offre['idoffre']] = new Activity();
            break;
          
          case 'Parc Attraction':
            $this->arrayOffer[$offre['idoffre']] = new Park();
            break;

          // Autre
          default:
            $this->arrayOffer[$offre['idoffre']] = new Offer("Pas de catégorie");
            break;
        }
        $this->arrayOffer[$offre['idoffre']]->setData($offre['idoffre'], 
          $offre['idu'], $offre['nom'], $offre['resume'],
          explode(",", trim($offre['listimage'], "{}")),
          explode(",", trim($offre['all_tags'], "{}")),
          $offre['ville'],
          $offre['statut'],
          transformerHoraires($offre['listhorairemidi']),
          transformerHoraires($offre['listhorairesoir'])
        );
      }
    }
  }


  /**
   * Set 
   */
  public function filtre($idUser_, $typeUser_) {
    return array_filter($this->arrayOffer, function($offer) use ($idUser_) {
      return $offer->filterPagination($idUser_, $typeUser_);
    });
  }

  public function pagination($idUser_, $typeUser_, $elementStart_ , $nbElement_) {
    return array_slice($this->filtre($idUser_, $typeUser_), $elementStart_, $nbElement_); 
  }

  public function getArray() {
    return $this->arrayOffer;
  }

  // TODO
  public function displayArray($idUser_, $typeUser_, $elementStart_ , $nbElement_) {
    $array = $this->pagination($idUser_, $typeUser_, $elementStart_ , $nbElement_);
    if (($typeUser_ == "pro_public" || $typeUser_ == "pro_prive")) {
      foreach ($array as $key -> $elem) {
        $elem->displayCardOffer();
      }
    } else {
      foreach ($array as $key -> $elem) {
        $elem->displayCardOfferPro();
      }
    }
  }
}


?>