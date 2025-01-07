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

function deleteFolder($folderPath) {
    // Récupérer tous les fichiers et dossiers, en excluant '.' et '..'
    $files = array_diff(scandir($folderPath), ['.', '..']);
    foreach ($files as $file) {
        $filePath = $folderPath . DIRECTORY_SEPARATOR . $file;
        if (is_dir($filePath)) {
            // Si c'est un dossier, l'appel récursif
            deleteFolder($filePath);
        } else {
            // Si c'est un fichier, on le supprime
            if (!unlink($filePath)) {
                // Si la suppression échoue, afficher l'erreur
                echo json_encode([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression du fichier : ' . $filePath,
                ]);
                exit;
            }
        }
    }
    return rmdir($folderPath); // Supprimer le dossier après avoir supprimé son contenu
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
