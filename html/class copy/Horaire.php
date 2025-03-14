<?php
  class Horaire {

    static function loadHoraire($conn, $idOffre) {
      $stmt = $conn->prepare("SELECT SELECT listhorairemidi, listhorairesoir FROM pact.offres WHERE idoffre = 3");
      $stmt->execute([$idOffre]);
      $langues = $stmt->fetchAll(PDO::FETCH_COLUMN);
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

    static function JsonToHoraire($idOffre_, $horaires) {
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

    /**
     * Détermine le statut ouvert/fermé 
     * suivant les horaires déterminés et l'horaire actuelle
     */
    static function statutOuverture($soir, $midi = null) {
      global $currentDay, $currentTime, $currentDate;
      $ouverture = "EstFermé";
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
      return $ouverture;
    }
  }
?>