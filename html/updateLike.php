<?php
header('Content-Type: application/json');

// Connect to the database
include 'connect_params.php';

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['action'], $input['idAvis'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$action = $input['action'];
$idAvis = (int)$input['idAvis'];

try {
    if ($action === 'like') {
        $stmt = $conn->prepare("UPDATE pact._commentaire SET nblike = nblike + 1 WHERE idc = ?");
    } elseif ($action === 'dislike') {
        $stmt = $conn->prepare("UPDATE pact._commentaire SET nbdislike = nbdislike + 1 WHERE idc = ?");
    } elseif ($action === 'unlike') {
        $stmt = $conn->prepare("UPDATE pact._commentaire SET nblike = nbdlike - 1 WHERE idc = ?");
    }
    elseif ($action === 'undislike') {
        $stmt = $conn->prepare("UPDATE pact._commentaire SET nbdislike = nbdislike -  1 WHERE idc = ?");
    }
    else {
        throw new Exception('Invalid action');
    }

    $stmt->execute([$idAvis]);

    // Fetch updated counts
    $stmt = $conn->prepare("SELECT nblike, nbdislike FROM reviews WHERE idc = ?");
    $stmt->execute([$idAvis]);
    $counts = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'newLikeCount' => $counts['nblike'], 'newDislikeCount' => $counts['nbdislike']]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
