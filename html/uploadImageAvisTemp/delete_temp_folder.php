<?php
header('Content-Type: application/json');

// Vérifiez si un identifiant unique est passé
if (!isset($_POST['unique_id']) || empty($_POST['unique_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Identifiant unique manquant.',
    ]);
    exit;
}

$uniqueId = htmlspecialchars($_POST['unique_id']);
$tempFolderPath = '../img/imageAvis/temp_uploads/' . $uniqueId; // Dossier temporaire basé sur l'ID

// Vérifiez si le dossier existe
if (!is_dir($tempFolderPath)) {
    echo json_encode([
        'success' => false,
        'message' => "Le dossier temporaire pour l'ID $uniqueId n'existe pas.",
    ]);
    exit;
}

// Fonction pour supprimer un dossier et son contenu
function deleteFolder($folderPath) {
    $files = array_diff(scandir($folderPath), ['.', '..']);
    foreach ($files as $file) {
        $filePath = $folderPath . DIRECTORY_SEPARATOR . $file;
        if (is_dir($filePath)) {
            deleteFolder($filePath);
        } else {
            unlink($filePath); // Supprime le fichier
        }
    }
    return rmdir($folderPath); // Supprime le dossier
}

// Tentez de supprimer le dossier
if (deleteFolder($tempFolderPath)) {
    echo json_encode([
        'success' => true,
        'message' => 'Le dossier temporaire a été supprimé avec succès.',
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Impossible de supprimer le dossier temporaire.',
    ]);
}
