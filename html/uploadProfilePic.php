<?php
    // Démarrer la session
    session_start();

    // Connexion à la base de données
    require_once 'db.php';
    
    // Photo de profil
    $file = $_FILES['profile-pic'];

    // Vérifier si un fichier a été envoyé
    if (isset($_FILES['profile-pic']) && $_FILES['profile-pic']['error'] === UPLOAD_ERR_OK) {
        // Récupérer le fichier téléchargé
        $file = $_FILES['profile-pic'];
    
        // Définir les types de fichiers autorisés
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    
        // Vérifier si le type de fichier est autorisé
        if (in_array($file['type'], $allowedTypes)) {
            // Définir le répertoire de destination pour l'upload
            $targetDir = './img/profile_picture/';
            
            // Vérifier si le répertoire existe, sinon créer le répertoire
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);  // Créer le répertoire avec les bonnes permissions
            }
    
            // Créer un nom de fichier unique pour éviter les collisions
            $targetFile = $targetDir . uniqid('profile_', true) . basename($file['name']);
    
            // Déplacer le fichier téléchargé vers le répertoire de destination
            if (move_uploaded_file($file['tmp_name'], $targetFile)) {
                try {
                    // Vérifier si l'URL de l'image existe déjà dans la table _image
                    $stmtImage = $conn->prepare("SELECT * FROM pact._image WHERE url = ?");
                    $stmtImage->execute([$targetFile]);
                    $imageExist = $stmtImage->fetch(PDO::FETCH_ASSOC);

                    if($photoProfil['url'] !="./img/profile_picture/default.svg"){
                        unlink($photoProfil['url']);
                    }
    
                    if (!$imageExist) {
                        // Si l'image n'existe pas, l'ajouter à la table _image avec un nom pour "nomimage"
                        $stmtInsertImage = $conn->prepare("INSERT INTO pact._image (url, nomimage) VALUES (?, ?)");
                        
                        // Utiliser le nom du fichier comme nom d'image (ou autre logique pour générer un nom unique)
                        $imageName = basename($targetFile); // Vous pouvez personnaliser cette logique si nécessaire
                        $stmtInsertImage->execute([$targetFile, $imageName]);
                    }
    
                    // Mettre à jour l'URL de la photo de profil dans la table _photo_profil
                    $stmtUpdatePhoto = $conn->prepare("UPDATE pact._photo_profil SET url = ? WHERE idU = ?");
                    $stmtUpdatePhoto->execute([$targetFile, $userId]);
    
                    $_SESSION['success'] = "Photo de profil mise à jour avec succès.";
                    header("Location: changeAccountMember.php");
                    exit();
                } 
                
                catch (Exception $e) {
                    $_SESSION['errors'][] = "Erreur lors de la mise à jour de la photo : " . $e->getMessage();
                }
            } 
            
            else {
                $_SESSION['errors'][] = "Échec du téléchargement de l'image.";
            }
        } 
        
        else {
            $_SESSION['errors'][] = "Seules les images JPG, PNG ou GIF sont autorisées.";
        }
    }
?>