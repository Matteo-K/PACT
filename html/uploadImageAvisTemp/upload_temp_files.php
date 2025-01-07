<?php
header('Content-Type: application/json');

// Chemin du dossier temporaire
$tempDir = '../img/imageAvis/temp_uploads/';

// Crée le dossier temporaire s'il n'existe pas
if (!is_dir($tempDir)) {
    if (!mkdir($tempDir, 0777, true)) {
        echo json_encode(['success' => false, 'message' => 'Impossible de créer le dossier temporaire.']);
        exit;
    }
}

// Récupère l'identifiant unique de l'utilisateur
if (!isset($_POST['unique_id'])) {
    echo json_encode(['success' => false, 'message' => 'Identifiant unique non fourni.']);
    exit;
}

$uniqueId = preg_replace('/[^a-zA-Z0-9_-]/', '', $_POST['unique_id']);
$userTempDir = $tempDir . $uniqueId . '/';

// Crée le sous-dossier pour cet utilisateur s'il n'existe pas
if (!is_dir($userTempDir)) {
    if (!mkdir($userTempDir, 0777, true)) {
        echo json_encode(['success' => false, 'message' => 'Impossible de créer le dossier utilisateur temporaire.']);
        exit;
    }
}

// Vérifie qu'il y a des fichiers téléchargés
if (empty($_FILES['images']['name'][0])) {
    echo json_encode(['success' => false, 'message' => 'Aucun fichier reçu.']);
    exit;
}

$uploadedFiles = [];
foreach ($_FILES['images']['name'] as $key => $name) {
    $tmpName = $_FILES['images']['tmp_name'][$key];
    $error = $_FILES['images']['error'][$key];
    $size = $_FILES['images']['size'][$key];

    // Vérifie les erreurs
    if ($error !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => "Erreur lors de l'upload du fichier $name."]);
        exit;
    }

    // Vérifie la taille du fichier (exemple : max 5 Mo)
    if ($size > 5 * 1024 * 1024) {
        echo json_encode(['success' => false, 'message' => "Le fichier $name dépasse la taille maximale autorisée."]);
        exit;
    }

    // Déplace le fichier vers le dossier temporaire
    $extension = pathinfo($name, PATHINFO_EXTENSION);
    $newFileName = uniqid('img_') . '.' . $extension;
    $destination = $userTempDir . $newFileName;

    if (move_uploaded_file($tmpName, $destination)) {
        $uploadedFiles[] = $destination;
        chmod($destination, 0777);
    } else {
        echo json_encode(['success' => false, 'message' => "Erreur lors du déplacement du fichier $name."]);
        exit;
    }
}

echo json_encode(['success' => true, 'files' => $uploadedFiles]);
?>
