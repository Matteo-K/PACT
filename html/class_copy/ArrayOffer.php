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

class ArrayOffer {
  // format : [$idOffre -> Objet, ...]
  private $arrayOffer;
  private $nbOffer;

  public function __construct($idoffres_ = "") {
    $this->arrayOffer = [];
    $this->nbOffer = 0;

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
   * Renvoie le nombre d'élément dela liste
   * Et la liste avec les éléments suivant le nombre d'élément sélectionner
   * @param array_ liste d'offre
   * @param elementStart_ indice de l'élément de départ
   * @param nbElement_ nombre d'élément à prendre
   * @return array extrait de la liste qui convient à la pagination
   */
  public function pagination($array_, $elementStart_ , $nbElement_) {
    return array_slice($array_, $elementStart_, $nbElement_); 
  }

  /**
   * Obtention de la liste d'offre pour la manipuler
   * @param array_ liste d'offre
   * @return array liste des informations des offres de la liste
   */
  public function getArray($array_ = "") {
    if (empty($array_)) {
      $array_ = $this->arrayOffer;
    }
    $arrayWithData = [];
    foreach ($array_ as $idOffre => $objet) {
      $arrayWithData[$idOffre] = $objet->getData();
    }
    return $arrayWithData;
  }

  /**
   * Affiche les offres à la une
   */
  public function displayCardALaUne() {
    $array = $this->arrayOffer;
    if (count($array) > 0) {
      foreach ($array as $key => $elem) {
        $elem->displayCardOffer();
      }
    } else {
      echo "<p>Aucune offre à la une </p>";
    }
  }
  
  /**
   * Affiche les offres consulter récemment par l'utilisateur
   * @param nbElement_ nombre d'élément à prendre
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

  /**
   * Affiche les offres nouvelles par l'utilisateur
   */
  public function displayNouvelle() {
    $array = $this->arrayOffer;
    if (count($array) > 0) {
      foreach ($array as $key => $elem) {
        $elem->displayCardOffer();
      }
    } else {
      echo "<p>Aucune nouvelle offres ont été posté</p>";
    }
  }

  /**
   * Affiche une liste d'offre convenant à l'utilisateur
   * @param array_ liste d'offre
   * @param typeUser_ type de l'utilisateur
   * @param elementStart_ indice de l'élément de départ
   * @param nbElement_ nombre d'élément à prendre
   */
  public function displayArrayCard($array_, $typeUser_, $elementStart_, $nbElement_) {
    $array = $this->pagination($array_, $elementStart_, $nbElement_);
    if (count($array) > 0) {
      foreach ($array as $key => $elem) {
        if ($typeUser_ == "pro_public" || $typeUser_ == "pro_prive") {
          $elem->displayCardOfferPro();
        } else {
          $elem->displayCardOffer();
        }
      }
    } else {
      echo "<p>Aucune offre trouvée </p>";
    }
    return count($array_);
  }
}


?>