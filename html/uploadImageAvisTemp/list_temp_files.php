<?php
header('Content-Type: application/json');

// Chemin du dossier temporaire
$tempDir = '../img/imageAvis/temp_uploads/';

// Vérifie que l'ID unique est passé en paramètre
if (!isset($_GET['unique_id'])) {
    echo json_encode(['success' => false, 'message' => 'Identifiant unique non fourni.']);
    exit;
}

$uniqueId = preg_replace('/[^a-zA-Z0-9_-]/', '', $_GET['unique_id']);
$userTempDir = $tempDir . $uniqueId . '/';

// Vérifie si le dossier utilisateur existe
if (!is_dir($userTempDir)) {
    echo json_encode(['success' => false, 'message' => 'Aucun fichier trouvé pour cet utilisateur.']);
    exit;
}

// Liste les fichiers dans le dossier
$files = array_diff(scandir($userTempDir), ['.', '..']);
$fileUrls = [];

foreach ($files as $file) {
    $fileUrls[] = $userTempDir . $file;
}

// Retourne les URLs des fichiers sous forme JSON
echo json_encode(['success' => true, 'files' => $fileUrls]);
?>
