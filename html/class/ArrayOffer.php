<?php
require_once "Park.php";
require_once "Restaurant.php";
require_once "Show.php";
require_once "Visit.php";
require_once "Activity.php";

class ArrayOffer {
  private $arrayOffer;
  private $nbOffer;

  public function __construct() {
    $this->arrayOffer = [];

    $stmt = $conn->prepare("SELECT * FROM pact.offres");
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($results) {
      foreach ($results as $offer) {
        switch ($offer['categorie']) {
          case 'value':
            # code...
            break;
          
          // Autre
          default:
            # code...
            break;
        }
      }
    }
  }
}


?>