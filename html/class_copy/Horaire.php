<?php
  class Horaire {

    private $horaireMidi = null;
    private $horaireSoir = null;
    private $ouverture = null;

    public function loadHoraire($idOffre) {
      $stmt = $conn->prepare("SELECT listhorairemidi, listhorairesoir FROM pact.offres WHERE idoffre = ?");
      $stmt->execute([$idOffre]);
      $horaire = $stmt->fetchAll(PDO::FETCH_COLUMN);
      $this->horaireMidi = Horaire::jsonToHoraire($idOffre, $horaire["listhorairemidi"]);
      $this->horaireSoir = Horaire::jsonToHoraire($idOffre, $horaire["listhorairesoir"]);
      $this->ouverture = $this->statutOuverture();
    }

    public function getHoraire() {
      return [
        "horaireMidi" => Horaire::jsonToHoraire($idOffre, $horaire["listhorairemidi"]),
        "horaireSoir" => Horaire::jsonToHoraire($idOffre, $horaire["listhorairesoir"])
      ];
    }

    /**
     * Détermine le statut ouvert/fermé 
     * suivant les horaires déterminés et l'horaire actuelle
     */
    public function statutOuverture() {
      global $currentDay, $currentTime, $currentDate;
      $ouverture = "EstFermé";
      $horaires = array_merge($this->horaireSoir, $this->horaireMidi);
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



    static function horaireToJson($horaire) {
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

    static function jsonToHoraire($idOffre_, $horaires) {
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
  }
?>