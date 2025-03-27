<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "config.php";

    $inputData = json_decode(file_get_contents("php://input"), true);

    if ($inputData) {
        $duree_blacklist = $inputData['duree_blacklist'];
        $intervall_blacklist = $inputData['intervall_blacklist'];

        try {
            $stmt = $conn->prepare("UPDATE pact._parametre SET dureeblacklistage=?, uniteblacklist=? WHERE id=true");
            $stmt->execute([intval($duree_blacklist), $intervall_blacklist]);

            echo json_encode([
                "resultat" => $stmt->rowCount() > 0
            ]);
            exit;
        } catch (PDOException $e) {
            echo json_encode([
                "resultat" => false,
                "erreur" => $e->getMessage()
            ]);
            exit;
        }
    } else {
        echo json_encode([
            "resultat" => false,
            "message" => "Données invalides"
        ]);
        exit;
    }
} else {
    echo json_encode([
        "resultat" => false,
        "message" => "Méthode non autorisée"
    ]);
    exit;
}
?>
