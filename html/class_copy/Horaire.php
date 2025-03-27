<?php

class Horaire {
  private $horaireMidi = [];
  private $horaireSoir = [];
  private $ouverture = "EstFermé";

  /**
   * Charge les horaires de la BDD
   * @param idOffre id de l'offre
   */
  public function loadHoraire($idOffre) {
    global $conn;

    $stmt = $conn->prepare("SELECT listhorairemidi, listhorairesoir FROM pact.offres WHERE idoffre = ?");
    $stmt->execute([$idOffre]);
    $horaire = $stmt->fetch(PDO::FETCH_ASSOC);

    $this->horaireMidi = self::jsonToHoraire($idOffre, $horaire["listhorairemidi"] ?? "");
    $this->horaireSoir = self::jsonToHoraire($idOffre, $horaire["listhorairesoir"] ?? "");
    $this->ouverture = $this->statutOuverture();
  }

  /**
   * Renvoie un tableau associatif des horaires
   * @param idOffre_ id de l'offre
   * @return array tableau d'horaire
   */
  public function getHoraire($idOffre_) {
    $this->loadHoraire($idOffre_);
    return [
      "horaireMidi" => $this->horaireMidi,
      "horaireSoir" => $this->horaireSoir,
      "ouverture" => $this->ouverture
    ];
  }

  /**
   * Détermine le statut d'ouverture (Ouvert/Fermé)
   * @return String état de l'offre "EstOuvert" / "EstFermé"
   */
  public function statutOuverture() {
    // Récupération de la date
    $formatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::FULL, IntlDateFormatter::NONE);
    $currentDay = ucfirst($formatter->format(new DateTime()));
    $currentTime = new DateTime(date('H:i'));

    $horaires = array_merge($this->horaireSoir ?? [], $this->horaireMidi ?? []);

    foreach ($horaires as $horaire) {
      if (strcasecmp($horaire['jour'], $currentDay) === 0) {
        $heureOuverture = DateTime::createFromFormat('H:i', $horaire['heureouverture']);
        $heureFermeture = DateTime::createFromFormat('H:i', $horaire['heurefermeture']);

        if ($heureOuverture && $heureFermeture && $currentTime >= $heureOuverture && $currentTime <= $heureFermeture) {
          return "EstOuvert";
        }
      }
    }
    return "EstFermé";
  }

  /**
   * Convertit un tableau d'horaires en JSON
   * @param horaire tableau des horaires
   * @return json Convertion du tableau en json
   */
  public static function horaireToJson(array $horaire) {
    return json_encode(array_map(fn($result) => [
      'jour' => $result['jour'],
      'heureOuverture' => $result['heureouverture'],
      'heureFermeture' => $result['heurefermeture']
    ], $horaire));
  }

  /**
   * Convertit un JSON en tableau d'horaires
   * @param idOffre id de l'offre
   * @param horairesJson tableau des horaires
   * @return array Convertion du json en format de la class
   */
  public static function jsonToHoraire($idOffre, $horairesJson) {
      if (empty($horairesJson)) {
        return [];
      }

      $horairesArray = json_decode($horairesJson, true);
      if (!is_array($horairesArray)) {
        return [];
      }

      return array_map(fn($horaire) => [
        'jour' => $horaire['jour'],
        'idoffre' => $idOffre,
        'heureouverture' => $horaire['heureOuverture'],
        'heurefermeture' => $horaire['heureFermeture']
      ], $horairesArray);
    }
  }
?>
