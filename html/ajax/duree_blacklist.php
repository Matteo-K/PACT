<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Tester une réponse simple en JSON
echo json_encode(["test" => "debug"]);
exit;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "../config.php";

    $inputData = json_decode(file_get_contents("php://input"), true);

    if (!empty($duree_blacklist) && !empty($intervall_blacklist)) {
        $stmt = $conn->prepare("UPDATE pact._parametre SET dureeblacklistage=10, uniteblacklist='minutes';");
        $stmt->execute([intval($duree_blacklist), $intervall_blacklist]);
    }
}
header("Location: ../index.php");
exit();
?>
