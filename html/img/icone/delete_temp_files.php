<?php
// Vérifie que la requête est POST et contient un JSON valide
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['fileUrl']) || !isset($data['uniqueId'])) {
        echo json_encode(['success' => false, 'message' => 'Données manquantes.']);
        exit;
    }

    $fileUrl = $data['fileUrl'];
    $uniqueId = $data['uniqueId'];

    // Chemin réel du fichier à supprimer
    $filePath = $_SERVER['DOCUMENT_ROOT'] . parse_url($fileUrl, PHP_URL_PATH);

    if (file_exists($filePath)) {
        if (unlink($filePath)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Impossible de supprimer le fichier.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Fichier introuvable.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
}
?>