<?php

// Récupérer l'heure actuelle et le jour actuel
setlocale(LC_TIME, 'fr_FR.UTF-8');
date_default_timezone_set('Europe/Paris');

class Horaire {
  private $horaireMidi = [];
  private $horaireSoir = [];
  private $ouverture = "EstFermé";
  private $conn;

  public function __construct($conn) {
    $this->conn = $conn;
  }

  public function loadHoraire($idOffre) {
    $stmt = $this->conn->prepare("SELECT listhorairemidi, listhorairesoir FROM pact.offres WHERE idoffre = ?");
    $stmt->execute([$idOffre]);
    $horaire = $stmt->fetch(PDO::FETCH_ASSOC);

    $this->horaireMidi = self::jsonToHoraire($idOffre, $horaire["listhorairemidi"] ?? "");
    $this->horaireSoir = self::jsonToHoraire($idOffre, $horaire["listhorairesoir"] ?? "");
    $this->ouverture = $this->statutOuverture();
  }

  /**
   * Renvoie un tableau associatif des horaires
   */
  public function getHoraire() {
    return [
      "horaireMidi" => $this->horaireMidi,
      "horaireSoir" => $this->horaireSoir,
      "ouverture" => $this->ouverture
    ];
  }

  /**
   * Détermine le statut d'ouverture (Ouvert/Fermé)
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
