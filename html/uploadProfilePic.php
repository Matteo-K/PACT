<?php
    // Inclure le fichier de connexion à la base de données
    require_once 'db.php'; // Assure-toi que ce fichier contient la connexion à la base de données

    // Vérifier si l'utilisateur est connecté
    session_start();
    if (!isset($_SESSION['idUser'])) {
        echo json_encode(['status' => 'error', 'message' => 'Utilisateur non connecté.']);
        exit();
    }

    // Définir le répertoire où l'image sera enregistrée
    $targetDir = './img/profile_picture/';

    // Vérifier si le fichier a été correctement téléchargé
    if (isset($_FILES['profile-pic']) && $_FILES['profile-pic']['error'] === UPLOAD_ERR_OK) {
        // Récupérer les informations du fichier téléchargé
        $file = $_FILES['profile-pic'];

        // Vérifier le type du fichier (par exemple, autoriser uniquement les images JPEG, PNG, GIF)
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowedTypes)) {
            echo json_encode(['status' => 'error', 'message' => 'Seules les images JPG, PNG ou GIF sont autorisées.']);
            exit();
        }

        // Créer un nom unique pour le fichier afin d'éviter les conflits
        $targetFile = $targetDir . uniqid('profile_', true) . basename($file['name']);

        // Déplacer le fichier téléchargé vers le répertoire cible
        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            // Connexion à la base de données et mise à jour de la photo de profil
            try {
                // Récupérer l'ID de l'utilisateur connecté
                $userId = $_SESSION['idUser'];

                // Vérifier si une photo de profil existe déjà pour cet utilisateur et la supprimer si nécessaire
                $stmt = $conn->prepare("SELECT * FROM pact._photo_profil WHERE idU = ?");
                $stmt->execute([$userId]);
                $photoProfil = $stmt->fetch(PDO::FETCH_ASSOC);

                // Si une photo existe déjà, supprimer l'ancienne image
                if ($photoProfil && $photoProfil['url'] !== './img/profile_picture/default.svg') {
                    unlink($photoProfil['url']);
                }

                // Insérer ou mettre à jour l'URL de la photo de profil dans la table _photo_profil
                $stmtUpdate = $conn->prepare("INSERT INTO pact._photo_profil (idU, url) VALUES (?, ?) ON DUPLICATE KEY UPDATE url = ?");
                $stmtUpdate->execute([$userId, $targetFile, $targetFile]);

                // Réponse JSON avec le chemin de la nouvelle photo de profil
                echo json_encode(['status' => 'success', 'newPhotoPath' => $targetFile]);
            } catch (Exception $e) {
                // Si une erreur se produit lors de la mise à jour en base de données
                echo json_encode(['status' => 'error', 'message' => 'Erreur de mise à jour en base de données : ' . $e->getMessage()]);
            }
        } else {
            // Réponse JSON en cas d'erreur lors du déplacement du fichier
            echo json_encode(['status' => 'error', 'message' => 'Échec du téléchargement du fichier.']);
        }
    } else {
        // Réponse JSON en cas d'absence de fichier ou d'erreur d'upload
        echo json_encode(['status' => 'error', 'message' => 'Erreur lors de l\'envoi du fichier.']);
    }
?>