<?php
  class HoraireSpectacle {

    static function loadHoraire($conn, $idOffre) {
      $stmt = $conn->prepare("SELECT listehoraireprecise FROM pact.offres WHERE idoffre = ?");
      $stmt->execute([$idOffre]);
      $horaire = $stmt->fetchAll(PDO::FETCH_COLUMN);
      return $horaire;
    }

    static function horaireToJson($horaire) {
      $formattedResultats = [];
      foreach ($horaire as $result) {
        $formattedResultats[] = json_encode([
          'jour' => $result['jour'],
          'heureouverture' => $result['heureouverture'],
          'heurefermeture' => $result['heurefermeture'],
          'daterepresentation' => $result['daterepresentation']
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
            'idoffre' => $idOffre_,
            'jour' => $decodedItem['jour'],
            'heureouverture' => $decodedItem['heureOuverture'],
            'heurefermeture' => $decodedItem['heureFin'],
            'daterepresentation' => $decodedItem['dateRepresentation']
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
  }
?>