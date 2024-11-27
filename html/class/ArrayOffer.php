<?php
require_once __DIR__. "/../config.php";
require_once "Offer.php";
require_once "Park.php";
require_once "Restaurant.php";
require_once "Show.php";
require_once "Visit.php";
require_once "Activity.php";

function transformerHoraires($idOffre_, $horaires) {
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
        'idoffre' => $idOffre_,
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
      foreach ($results as $offre) {

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

        $options = [];
        $stmt = $conn->prepare("SELECT * FROM pact._option_offre WHERE idoffre = ? AND idoption = (SELECT MAX(idoption) FROM pact._option_offre WHERE idoffre = ?)");
        $stmt->execute([$offre['idoffre'], $offre['idoffre']]);
        while ($results = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
          print_r($results);
          $options[] = $results["nomoption"];
        }
        
        $stmt = $conn->prepare("SELECT count(note), avg(note) from pact._avis where idoffre = ?");
        

        $this->arrayOffer[$offre['idoffre']]->setData($offre['idoffre'], 
          $offre['idu'], $offre['nom'],
          $offre['nomabonnement'], $options, 
          $offre['description'], $offre['resume'],
          $offre['mail'], $offre['telephone'],
          $offre['urlsite'], $offre['datecrea'],
          explode(",", trim($offre['listimage'], "{}")),
          explode(",", trim(isset($offre['all_tags']) ? $offre['all_tags'] : '', "{}")),
          $offre['ville'],
          $offre['pays'],
          $offre['numerorue'],
          $offre['rue'],
          $offre['codepostal'],
          $offre['statut'],
          transformerHoraires($offre['idoffre'], $offre['listhorairemidi']),
          transformerHoraires($offre['idoffre'], $offre['listhorairesoir'])
        );
      }
    }
  }

  /**
   * Prend les bonnes offres suivant l'utilisateur
   */
  public function filtre($idUser_, $typeUser_) {
    return array_filter($this->arrayOffer, function($offer) use ($idUser_, $typeUser_) {
      return $offer->filterPagination($idUser_, $typeUser_);
    });
  }

  public function recherche($idUser_, $typeUser_, $recherche) {
    $array = $this->filtre($idUser_, $typeUser_);
    return array_filter($this->arrayOffer, function($item) use ($recherche) {

        $categorie = $item->getData()["categorie"] ?? '';
        $nomOffre = $item->getData()["nomOffre"] ?? '';
        $gammeDePrix = $item->getData()["gammeDePrix"] ?? '';
        $adresse = $item->formaterAdresse() ?? '';

        return $this->offreContientTag($item->getData()["tags"], $recherche) // tag
            || strpos(strtolower($categorie), strtolower($recherche)) !== false // catégorie
            || strpos(strtolower($nomOffre), strtolower($recherche)) !== false  // nom Offre
            || strpos(strtolower($adresse), strtolower($recherche)) !== false  // localisation
            || $gammeDePrix === $recherche;  // gamme de prix
    });
}


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
   */
  public function pagination($array_, $elementStart_ , $nbElement_) {
    return array_slice($array_, $elementStart_, $nbElement_); 
  }

  public function getArray($array_) {
    $arrayWithData = [];
    foreach ($array_ as $idOffre => $objet) {
      $arrayWithData[$idOffre] = $objet->getData();
    }
    
    return $arrayWithData;
  }

  public function displayCardALaUne($array_, $typeUser_, $elementStart_, $nbElement_) {
    $array = $this->pagination($array_, $elementStart_, $nbElement_);
    if (count($array) > 0) {
      foreach ($array as $key => $elem) {
        if (in_array("EnRelief", $elem->getData()["option"])) {
          $elem->displayCardALaUne();
        }
      }
    } else {
      echo "<p>Aucune offre trouvée </p>";
    }
  }

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