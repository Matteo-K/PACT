<?php

if ($_SERVER["REQUEST_METHOD"] == "GET") {
  if (isset($_GET["idu"])) {

    $idu = intval($_GET["idu"]);
    if (is_int($idu)) {
      require_once '../config.php';

      $stmt = $conn->prepare(
        "SELECT 
          a.note, a.titre, a.datepublie, a.pseudo, a.idc,
          o.idoffre, o.nom, pp.url
        from pact.avis a
          LEFT JOIN pact._photo_profil pp on a.idu = pp.idu
          LEFT JOIN pact._offre o on a.idoffre = o.idoffre
        WHERE lu=false AND o.idu=? ORDER BY a.idoffre;"
      );
      $stmt->execute([$idu]);
      $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

      echo json_encode(["avis" => $res]);
    } else {
      echo json_encode(["error" => "idu is Nan"]);
    }
  } else {
    echo json_encode(["error" => "idu is undefined"]);
  }
}
?>