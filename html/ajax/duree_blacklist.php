<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "../config.php";

    $inputData = json_decode(file_get_contents("php://input"), true);

    if (!empty($duree_blacklist) && !empty($intervall_blacklist)) {
        $stmt = $conn->prepare("UPDATE pact._parametre SET dureeblacklistage=?, uniteblacklist=?;");
        $stmt->execute([intval($duree_blacklist), $intervall_blacklist]);
    }
}
header("Location: ../index.php");
exit();
?>
