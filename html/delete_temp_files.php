<?php
// Suppression du fichier dans le dossier temporaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données envoyées
    $fileUrl = $_POST['fileUrl'];
    $uniqueId = $_POST['unique_id'];

    // Chemin du dossier temporaire où les images sont stockées
    $tempDir = 'img/imageAvis/temp_uploads/' . $uniqueId;

    // Vérifier que le fichier existe dans le dossier temporaire
    $filePath = $tempDir ."/". basename($fileUrl);
    if (file_exists($filePath)) {
        // Suppression du fichier
        if (unlink($filePath)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Impossible de supprimer le fichier.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Fichier non trouvé.']);
    }
}
?>