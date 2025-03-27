<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "../config.php";

    $duree_blacklist = $_POST['duree_blacklist'];
    $intervall_blacklist = $_POST['intervall_blacklist'];

    if (!empty($duree_blacklist) && !empty($intervall_blacklist)) {

        $stmt = $conn->prepare("UPDATE pact._parametre SET dureeblacklistage=?, uniteblacklist=? WHERE id=1;");
        $stmt->execute([intval($duree_blacklist), $intervall_blacklist]);
    }
}

header("Location: index.php");
exit();
?>
