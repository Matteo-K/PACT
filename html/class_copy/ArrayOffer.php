<?php
require_once __DIR__. "/../config.php";
require_once "Horaire.php";
require_once "HoraireSpectacle.php";
require_once "Offer.php";
require_once "Park.php";
require_once "Restaurant.php";
require_once "Show.php";
require_once "Visit.php";
require_once "Activity.php";

// Définition de l'heure française
setlocale(LC_TIME, 'fr_FR.UTF-8');
date_default_timezone_set('Europe/Paris');

class ArrayOffer {
  // format : [$idOffre -> Objet, ...]
  private $arrayOffer;

  public function __construct($idoffres_ = "") {
    $this->arrayOffer = [];

    global $conn;

    // Selectionne toute les offres
    if (empty($idoffres_)) {
        $stmt = $conn->prepare("SELECT idoffre, categorie FROM pact.offres");
        $stmt->execute();
    } else {
        // Selectionne les offres de la liste
        if (is_array($idoffres_)) {
            $placeholders = rtrim(str_repeat('?,', count($idoffres_)), ',');
            $stmt = $conn->prepare("SELECT idoffre, categorie FROM pact.offres WHERE idoffre IN ($placeholders)");
            $stmt->execute($idoffres_);
        // Seulement l'offre de l'id
        } else {
            $stmt = $conn->prepare("SELECT idoffre, categorie FROM pact.offres WHERE idoffre = ?");
            $stmt->execute([$idoffres_]);
        }
    }
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($results) {
      switch ($offre['categorie']) {
        case 'Restaurant':
          $this->arrayOffer[$offre['idoffre']] = new Restaurant($offre['idoffre']);
          break;

        case 'Spectacle':
          $this->arrayOffer[$offre['idoffre']] = new Show($offre['idoffre']);
          break;
          
        case 'Visite':
          $this->arrayOffer[$offre['idoffre']] = new Visit($offre['idoffre']);
          $handicap = array();
          break;
            
        case 'Activité':
          $this->arrayOffer[$offre['idoffre']] = new Activity($offre['idoffre']);
          break;
        
        case 'Parc Attraction':
          $this->arrayOffer[$offre['idoffre']] = new Park($offre['idoffre']);
          break;

        // Autre
        default:
          $this->arrayOffer[$offre['idoffre']] = new Offer($offre['idoffre'], "Pas de catégorie");
          break;
      }
    }
  }

  /**
   * Obtention des données de la liste d'offre pour de la manipulation
   * @param id_ liste d'id ded offres
   * @param attributs_ liste nom des attriburs des données des offres
   * @return array liste des informations des offres de la liste
   */
  public function getDataArray(array $id_ = [], array $attributs_ = []) {
    $array_ = empty($id_) ? $this->arrayOffer : array_intersect_key($this->arrayOffer, array_flip($id_));
    return array_map(fn($offer) => $offer->getData($attributs_), $array_);
  }

  /**
   * Chargement des données de la BDD de la liste d'offre
   * @param id_ liste d'id ded offres
   * @param attributs_ liste nom des attriburs des données des offres
   * @return array liste des informations des offres de la liste
   */
  public function loadDataArray( $id_ = [], array $attributs_ = []) {
    $array_ = empty($id_) ? $this->arrayOffer : array_intersect_key($this->arrayOffer, array_flip($id_));
    array_walk($array_, fn($offre) => $offre->loadData($attributs_));
  }

  /// Affichage des cartes d'offres ///

  /**
   * Affiche les offres avec un message d'erreur personnalisé
   * @param message_ message personnalisé
   */
  public function displayCard($message_ = "") {
    $array = $this->arrayOffer;
    if (count($array) > 0) {
      foreach ($array as $key => $elem) {
        $elem->displayCardOffer();
      }
    } else {
      echo $message_;
    }
  }
  
  /**
   * Affiche les offres consulter récemment par l'utilisateur
   * @param nbElement_ nombre d'élément à affiché
   */
  public function displayConsulteRecemment($nbElement_) {
    $array = $this->arrayOffer;
    $array = array_slice($array, 0, $nbElement_);
    if (count($array) > 0) {
      foreach ($array as $key => $elem) {
        $elem->displayCardOffer();
      }
    } else {
      echo "<p>Aucune offre consultée récemment</p>";
    }
  }
}


?>