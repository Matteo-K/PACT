<?php
require_once __DIR__. "/../config.php";
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

    if ($results) {
    }
  }

  /**
   * Prend les bonnes offres suivant l'utilisateur
   * @param idUser_ id référence de l'utilisateur
   * @param typeUser_ type de l'utilisateur
   */
  public function filtre($idUser_, $typeUser_) {
    return array_filter($this->arrayOffer, function($offer) use ($idUser_, $typeUser_) {
      return $offer->filterPagination($idUser_, $typeUser_);
    });
  }

  public function recherche($idUser_, $typeUser_, $recherche) {
    $array = $this->filtre($idUser_, $typeUser_);

    if (empty($recherche)) {
        return $array;
    }

    return array_filter($this->arrayOffer, function($item) use ($recherche) {
      $data = $item->getData();
      $categorie = isset($data["categorie"]) ? $data["categorie"] : '';
      $nomOffre = isset($data["nomOffre"]) ? $data["nomOffre"] : '';
      $gammeDePrix = isset($data["gammeDePrix"]) ? $data["gammeDePrix"] : '';
      $adresse = method_exists($item, 'formaterAdresse') ? $item->formaterAdresse() : '';

      return $this->offreContientTag($data["tags"], $recherche)  // tags
          || (strlen($categorie) > 0 && strpos(strtolower($categorie), strtolower($recherche)) !== false)  // catégorie
          || (strlen($nomOffre) > 0 && strpos(strtolower($nomOffre), strtolower($recherche)) !== false)  // nom de l'offre
          || (strlen($adresse) > 0 && strpos(strtolower($adresse), strtolower($recherche)) !== false)  // adresse
          || ($gammeDePrix === $recherche);  // gamme de prix
    });
  }

  /**
   * Vérifie si l'élément de recherche est dans un des tags de l'offre
   * @param tags liste de tag d'une offre
   * @param recherche chaine de caracrère saisit par l'utilisateur
   * @return bool si le mot de recherche est dans la liste de tags
   */
  public function offreContientTag($tags, $recherche) {
    foreach ($tags as $tag) {
      if (strpos(strtolower($tag), strtolower($recherche)) !== false) {
        return true;
      }
    }
    return false;
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
  public function getArray($array_ = 0) {
    if ($array_ == 0) {
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