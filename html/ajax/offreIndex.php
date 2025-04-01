<?php
header('Content-Type: application/json');

// Vérifie si le paramètre 'get' est bien 'display' et si 'idoffre' est présent
if (isset($_GET['get']) && isset($_GET['idoffre'])) {

  switch ($_GET['get']) {
    case 'display':
      $ids = is_array($_GET['idoffre']) ? $_GET['idoffre'] : [$_GET['idoffre']];

      // Définitions des données
      $attributs = [
        "idOffre" => "idoffre",
        "nomOffre" => "nom",
        "resume" => "resume",
        "categorie" => "categorie",
        "statut" => "statut",
        "ville" => "ville",
        "codePostal" => "codepostal",
        "numeroRue" => "numerorue",
        "rue" => "rue",
        "tags" => "all_tags"
      ];
      $enumOffre = implode(", ", $ids);
      $selectAttributs = implode(", ", array_map(fn($col) => "o.$col AS \"$col\"", array_values($attributs)));
      $groupByAttributs = implode(", ", array_map(fn($col) => "o.$col", array_values($attributs)));

      $query = "
        SELECT 
          $selectAttributs,
          (SELECT i.url FROM pact._illustre i WHERE i.idoffre = o.idoffre ORDER BY i.url LIMIT 1) AS \"imagePrincipale\",
          COALESCE(AVG(a.note), 0) AS \"noteAvg\", 
          COALESCE(COUNT(a.note), 0) AS \"totalAvis\",
          COALESCE(STRING_AGG(opt.nomoption, ','), '') AS \"options\"
        FROM pact.offres o
        LEFT JOIN pact._avis a ON o.idoffre = a.idoffre
        LEFT JOIN pact._option_offre opt ON o.idoffre = opt.idoffre
        WHERE o.idoffre IN ($enumOffre)
        GROUP BY o.idoffre, $groupByAttributs;
      ";

      $stmt = $conn->prepare($query);
      $stmt->execute();
      $offresData = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $offers = [];
      foreach ($offresData as $offre) {
        $idOffre = $offre["idOffre"];
        $offers[$idOffre] = [];

        // Insertion brut des données
        foreach ($attributs as $cle => $bddAttribut) {
          $offers[$idOffre][$cle] = $offre[$cle];
        }
        $offers[$idOffre]["images"] = $offre["imagePrincipale"] ? [$offre["imagePrincipale"]] : [""];
        $offers[$idOffre]["noteAvg"] = $offre["noteAvg"];
        $offers[$idOffre]["totalAvis"] = $offre["totalAvis"];
        $offers[$idOffre]["option"] = $offre["options"] ? explode(",", trim(isset($offre["options"]) ? $offre["options"] : '', "{}")) : [];
        
        // Adaptation des données
      }

      break;
    case 'filtre':
      break;
    default:
      break;
  }

  // Retourne les données JSON
  echo json_encode(["status" => "success", "offers" => array_values($result)]);
} else {
  echo json_encode(["status" => "error", "message" => "Paramètres invalides"]);
}
?>
