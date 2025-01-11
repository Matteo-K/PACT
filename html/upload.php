<?php
$uploadDir = $_POST["dossierImg"] ?? $_GET["dossierImg"];
$limit = $_POST["limit"] ?? 0;
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

echo $uploadDir;

// Crée le dossier s'il n'existe pas
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

function getMaxIndex($directory) {
    $files = array_values(array_diff(scandir($directory), ['.', '..']));
    $maxIndex = 0;

    foreach ($files as $file) {
        $matches = [];
        if (preg_match('/^(\d+)\.(jpg|jpeg|png|gif)$/i', $file, $matches)) {
            $maxIndex = max($maxIndex, (int)$matches[1]);
        }
    }

    return $maxIndex;
}

// Vérifier si le dossier existe et récupérer les fichiers existants
function getExistingFiles($directory) {
    return array_values(array_diff(scandir($directory), ['.', '..']));
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Action pour l'upload
    if ($_POST['action'] === 'upload') {
        $existingFiles = getExistingFiles($uploadDir);
        $imageCount = count($existingFiles);

        if (!empty($_FILES['images']['name'][0])) {
            foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
                if ($imageCount >= $limit) {
                    $response[] = ['error' => "Limite maximale de $imageCount images atteinte."];
                    break;
                }

                $maxIndex = getMaxIndex($uploadDir);
                $newIndex = $maxIndex + 1;

                $extension = pathinfo($_FILES['images']['name'][$key], PATHINFO_EXTENSION);
                $fileName = $newIndex . '.' . strtolower($extension);
                $targetFile = $uploadDir . $fileName;

                if (getimagesize($tmpName)) {
                    if (move_uploaded_file($tmpName, $targetFile)) {
                        $response[] = ['success' => "Fichier $fileName téléchargé avec succès."];
                        $imageCount++;
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
                
                $response[] = ['success' => "Fichier supprimé : " . $_POST['fileName']];
            } else {
                $response[] = ['error' => "Erreur lors de la suppression du fichier " . $_POST['fileName']];
            }
        } else {
            $response[] = ['error' => "Fichier non trouvé : " . $_POST['fileName']];
        }
    }    
} // Action pour lister les images existantes
elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (is_dir($uploadDir)) {
        $images = array_values(array_diff(scandir($uploadDir), ['.', '..']));
        
        // Applique un tri naturel
        natsort($images);

        // Réindexe les clés du tableau après le tri
        $images = array_values($images);

        $response = ['images' => $images];
    } else {
        $response = ['images' => []];
    }
}


header('Content-Type: application/json');
echo json_encode($response);
