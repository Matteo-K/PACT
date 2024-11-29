<?php
$uploadDir = 'uploads/'; // Dossier contenant les images

// Crée le dossier s'il n'existe pas
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'upload') {
        if (!empty($_FILES['images']['name'][0])) {
            foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
                $fileName = basename($_FILES['images']['name'][$key]);
                $targetFile = $uploadDir . $fileName;

                // Vérifier si le fichier est une image
                if (getimagesize($tmpName)) {
                    // Vérifier s'il y a des erreurs lors du téléchargement
                    if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
                        if (move_uploaded_file($tmpName, $targetFile)) {
                            $response[] = ['success' => "Fichier $fileName téléchargé avec succès."];
                        } else {
                            $response[] = ['error' => "Erreur interne lors du téléchargement de $fileName."];
                        }
                    } else {
                        $errorCode = $_FILES['images']['error'][$key];
                        $response[] = ['error' => "Erreur $errorCode pour le fichier $fileName."];
                    }
                } else {
                    $response[] = ['error' => "$fileName n'est pas une image valide."];
                }
            }
        } else {
            $response[] = ['error' => "Aucun fichier sélectionné."];
        }
    } elseif ($_POST['action'] === 'delete') {
        $fileToDelete = $uploadDir . basename($_POST['fileName']);
        if (file_exists($fileToDelete)) {
            if (unlink($fileToDelete)) {
                $response[] = ['success' => "Fichier supprimé : " . $_POST['fileName']];
            } else {
                $response[] = ['error' => "Erreur lors de la suppression."];
            }
        } else {
            $response[] = ['error' => "Fichier non trouvé."];
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $images = array_values(array_diff(scandir($uploadDir), ['.', '..']));
    $response = ['images' => $images];
}

// Envoie la réponse JSON
header('Content-Type: application/json');
echo json_encode($response);
