<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "config.php";
    
    $duree_blacklist = $_POST["duree_blacklist"] ?? "";
    $intervall_blacklist = $_POST["intervall_blacklist"] ?? "";

    if (!empty($duree_blacklist) && !empty($intervall_blacklist)) {
        try {
            $stmt = $conn->prepare("UPDATE pact._parametre SET dureeblacklistage=?, uniteblacklist=? WHERE id=true");
            $stmt->execute([$duree_blacklist, $intervall_blacklist]);

            echo json_encode([
                "resultat" => $stmt->rowCount() > 0
            ]);
        } catch (PDOException $e) {
            echo json_encode([
                "resultat" => false,
                "erreur" => $e->getMessage()
            ]);
        }
    } else {
        echo json_encode([
            "resultat" => false,
            "message" => "Données invalides"
        ]);
    }
} else {
    echo json_encode([
        "resultat" => false,
        "message" => "Méthode non autorisée"
    ]);
}
?>
