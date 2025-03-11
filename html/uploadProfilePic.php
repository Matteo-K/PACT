<?php
    session_start();
    require_once 'db.php';

    // Récupérer l'ID de l'utilisateur depuis la session
    $userId = $_SESSION['idUser'];

    // Vérifier si un fichier a été envoyé
    if (isset($_FILES['profile-pic']) && $_FILES['profile-pic']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['profile-pic'];

        // Types de fichiers autorisés
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

        if (in_array($file['type'], $allowedTypes)) {
            $targetDir = './img/profile_picture/';

            // Vérifier si le répertoire existe, sinon le créer
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            // Générer un nom de fichier unique
            $targetFile = $targetDir . uniqid('profile_', true) . basename($file['name']);

            // Déplacer le fichier téléchargé vers le répertoire de destination
            if (move_uploaded_file($file['tmp_name'], $targetFile)) {
                try {
                    // Vérifier si l'image existe déjà dans la base de données
                    $stmtImage = $conn->prepare("SELECT * FROM pact._image WHERE url = ?");
                    $stmtImage->execute([$targetFile]);
                    $imageExist = $stmtImage->fetch(PDO::FETCH_ASSOC);

                    if ($currentPhoto) {
                        // Si une photo de profil existe, supprimer l'ancienne image du répertoire et de la base de données
                        if ($currentPhoto['url'] !== "./img/profile_picture/default.svg") {
                            // Supprimer le fichier image de l'ancien chemin
                            unlink($currentPhoto['url']);
    
                            // Supprimer l'ancienne photo de la base de données
                            $stmtDeletePhoto = $conn->prepare("DELETE FROM pact._photo_profil WHERE idU = ?");
                            $stmtDeletePhoto->execute([$userId]);
                        }
                    }

                    // Si l'image n'existe pas déjà, l'ajouter à la table _image
                    if (!$imageExist) {
                        $stmtInsertImage = $conn->prepare("INSERT INTO pact._image (url, nomimage) VALUES (?, ?)");
                        $imageName = basename($targetFile);
                        $stmtInsertImage->execute([$targetFile, $imageName]);
                    }

                    // Mettre à jour la photo de profil de l'utilisateur
                    $stmtUpdatePhoto = $conn->prepare("UPDATE pact._photo_profil SET url = ? WHERE idU = ?");
                    $stmtUpdatePhoto->execute([$targetFile, $userId]);

                    // Retourner la nouvelle URL de l'image pour l'affichage dynamique
                    echo json_encode(['status' => 'success', 'newPhotoPath' => $targetFile]);
                } 
                
                catch (Exception $e) {
                    echo json_encode(['status' => 'error', 'message' => 'Erreur lors de la mise à jour de la photo de profil : ' . $e->getMessage()]);
                }
            } 
            
            else {
                echo json_encode(['status' => 'error', 'message' => 'Échec du téléchargement de l\'image.']);
            }
        } 
        
        else {
            echo json_encode(['status' => 'error', 'message' => 'Seules les images JPG, PNG ou GIF sont autorisées.']);
        }
    } 
    
    else {
        echo json_encode(['status' => 'error', 'message' => 'Aucun fichier téléchargé.']);
    }
?>