<?php
$uploadDir = './img/imageOffre/';
$response = [];

// Vérifiez si l'idOffre est défini
if (empty($_GET['idOffre']) && empty($_POST['idOffre'])) {
    $response = ['error' => "ID de l'offre non défini."];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

$idOffre = $_GET['idOffre'] ?? $_POST['idOffre'];
$uploadDir .= $idOffre . '/'; // Répertoire spécifique à l'offre

// Crée le dossier s'il n'existe pas
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Fonction pour réindexer les fichiers dans le dossier
function reindexFiles($directory) {
    $files = array_values(array_diff(scandir($directory), ['.', '..']));
    sort($files); // Tri pour garantir un ordre cohérent
    $index = 0;

    foreach ($files as $file) {
        $newName = $index . ".jpg";
        rename($directory . $file, $directory . $newName);
        $index++;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Action pour l'upload
    if ($_POST['action'] === 'upload') {
        if (!empty($_FILES['images']['name'][0])) {
            foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
                $fileName = count(scandir($uploadDir)) - 2 . ".jpg"; // Utilise le prochain index disponible
                $targetFile = $uploadDir . $fileName;

                if (getimagesize($tmpName)) {
                    if (move_uploaded_file($tmpName, $targetFile)) {
                        $response[] = ['success' => "Fichier $fileName téléchargé avec succès."];
                    } else {
                        $response[] = ['error' => "Erreur lors du téléchargement de $fileName."];
                    }
                } else {
                    $response[] = ['error' => "Le fichier n'est pas une image valide."];
                }
            }
        } else {
            $response[] = ['error' => "Aucun fichier sélectionné."];
        }
    }
    // Action pour supprimer une image
    elseif ($_POST['action'] === 'delete') {
        $fileToDelete = $uploadDir . basename($_POST['fileName']);
        if (file_exists($fileToDelete)) {
            if (unlink($fileToDelete)) {
                reindexFiles($uploadDir); // Réindexe les fichiers après suppression
                $response[] = ['success' => "Fichier supprimé : " . $_POST['fileName']];
            } else {
                $response[] = ['error' => "Erreur lors de la suppression du fichier " . $_POST['fileName']];
            }
        } else {
            $response[] = ['error' => "Fichier non trouvé : " . $_POST['fileName']];
        }
    }    
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Action pour lister les images existantes
    if (is_dir($uploadDir)) {
        $images = array_values(array_diff(scandir($uploadDir), ['.', '..']));
        $response = ['images' => $images];
    } else {
        $response = ['images' => []];
    }
}

header('Content-Type: application/json');
echo json_encode($response);
