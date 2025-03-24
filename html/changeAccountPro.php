<?php
    // Démarrer la session
    session_start();

    // Connexion à la base de données
    require_once 'db.php';

    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['idUser'])) {
        header("Location: login.php");
        exit();
    }

    // Récupérer l'ID de l'utilisateur connecté
    $userId = $_SESSION['idUser'];

    // Récupérer les informations de l'utilisateur depuis la base de données
    try {
        $stmt = $conn->prepare("SELECT * FROM pact.propublic WHERE idU = ?");
        $stmt->execute([$userId]);
        $userPublic = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifier dans la table pro privé si pas trouvé dans pro public
        if (!$userPublic) {
            $stmt = $conn->prepare("SELECT * FROM pact.proprive WHERE idU = ?");
            $stmt->execute([$userId]);
            $userPrivate = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        // Récupérer la photo de profil de la table _photo_profil
        $stmtPhoto = $conn->prepare("SELECT * FROM pact._photo_profil WHERE idU = ?");
        $stmtPhoto->execute([$userId]);
        $photoProfil = $stmtPhoto->fetch(PDO::FETCH_ASSOC);

        if ($photoProfil) {
            $photoPath = $photoProfil['url'];  // Le chemin de l'image
        } 
        
        else {
            // Si aucune photo n'est trouvée, utiliser une image par défaut
            $photoPath = './img/profile_picture/default.svg';
        }

        // Fusionner les résultats de propublic et proprive, si les deux existent
        $user = $userPublic ?: $userPrivate;

        // Vérifier si les données sont trouvées
        if (!$user) {
            $_SESSION['errors'][] = "Utilisateur introuvable.";
            header("Location: login.php");
            exit();
        }
    } 
    
    catch (Exception $e) {
        $_SESSION['errors'][] = "Erreur de connexion à la base de données: " . $e->getMessage();
        header("Location: login.php");
        exit();
    }


    // Vérifier si le formulaire a été soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupérer les nouvelles données du formulaire
        $denomination = trim($_POST['denomination']);
        $telephone = trim($_POST['telephone']);
        $mail = trim($_POST['email']);
        $adresse = trim($_POST['adresse']);
        $code = trim($_POST['code']);
        $ville = trim($_POST['ville']);


        // // Photo de profil
        // $file = $_FILES['profile-pic'];

        // // Vérifier si un fichier a été envoyé
        // if (isset($_FILES['profile-pic']) && $_FILES['profile-pic']['error'] === UPLOAD_ERR_OK) {
        //     // Récupérer le fichier téléchargé
        //     $file = $_FILES['profile-pic'];
        
        //     // Définir les types de fichiers autorisés
        //     $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        
        //     // Vérifier si le type de fichier est autorisé
        //     if (in_array($file['type'], $allowedTypes)) {
        //         // Définir le répertoire de destination pour l'upload
        //         $targetDir = './img/profile_picture/';
                
        //         // Vérifier si le répertoire existe, sinon créer le répertoire
        //         if (!is_dir($targetDir)) {
        //             mkdir($targetDir, 0777, true);  // Créer le répertoire avec les bonnes permissions
        //         }
        
        //         // Créer un nom de fichier unique pour éviter les collisions
        //         $targetFile = $targetDir . uniqid('profile_', true) . basename($file['name']);
        
        //         // Déplacer le fichier téléchargé vers le répertoire de destination
        //         if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        //             try {
        //                 // Vérifier si l'URL de l'image existe déjà dans la table _image
        //                 $stmtImage = $conn->prepare("SELECT * FROM pact._image WHERE url = ?");
        //                 $stmtImage->execute([$targetFile]);
        //                 $imageExist = $stmtImage->fetch(PDO::FETCH_ASSOC);

        //                 if($photoProfil['url'] !="./img/profile_picture/default.svg"){
        //                     unlink($photoProfil['url']);
        //                 }
        
        //                 if (!$imageExist) {
        //                     // Si l'image n'existe pas, l'ajouter à la table _image avec un nom pour "nomimage"
        //                     $stmtInsertImage = $conn->prepare("INSERT INTO pact._image (url, nomimage) VALUES (?, ?)");
                            
        //                     // Utiliser le nom du fichier comme nom d'image (ou autre logique pour générer un nom unique)
        //                     $imageName = basename($targetFile); // Vous pouvez personnaliser cette logique si nécessaire
        //                     $stmtInsertImage->execute([$targetFile, $imageName]);
        //                 }
        
        //                 // Mettre à jour l'URL de la photo de profil dans la table _photo_profil
        //                 $stmtUpdatePhoto = $conn->prepare("UPDATE pact._photo_profil SET url = ? WHERE idU = ?");
        //                 $stmtUpdatePhoto->execute([$targetFile, $userId]);
        
        //                 $_SESSION['success'] = "Photo de profil mise à jour avec succès.";
        //                 header("Location: changeAccountPro.php");
        //                 exit();
        //             } 
                    
        //             catch (Exception $e) {
        //                 $_SESSION['errors'][] = "Erreur lors de la mise à jour de la photo : " . $e->getMessage();
        //             }
        //         } 
                
        //         else {
        //             $_SESSION['errors'][] = "Échec du téléchargement de l'image.";
        //         }
        //     } 
            
        //     else {
        //         $_SESSION['errors'][] = "Seules les images JPG, PNG ou GIF sont autorisées.";
        //     }
        // }


        // Si l'adresse mail a été modifiée, vérifier si elle existe déjà
        if ($mail !== $user['mail']) {
            try {
                // Requête sur propublic
                $stmt = $conn->prepare("SELECT * FROM pact.propublic WHERE mail = ?");
                $stmt->execute([$mail]);
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Si pas trouvé, vérifier dans proprive
                if (!$result) {
                    $stmt = $conn->prepare("SELECT * FROM pact.proprive WHERE mail = ?");
                    $stmt->execute([$mail]);
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                }
            }

            catch (Exception $e) {
                $_SESSION['errors'][] = "Erreur lors de la vérification de l'adresse mail : " . htmlspecialchars($e->getMessage());
            }
        }

        // Si aucun problème, mettre à jour les informations
        if (empty($_SESSION['errors'])) {
            try {
                $adresseExplode = explode(' ', $adresse, 2);
                $numeroRue = isset($adresseExplode[0]) ? $adresseExplode[0] : '';
                $rue = isset($adresseExplode[1]) ? $adresseExplode[1] : '';

                // Mettre à jour les informations dans la base de données
                if ($userPublic) {
                    $stmt = $conn->prepare("UPDATE pact.propublic SET denomination = ?, telephone = ?, mail = ?, numeroRue = ?, rue = ?, codePostal = ?, ville = ? WHERE idU = ?");
                    $stmt->execute([$denomination, $telephone, $mail, $numeroRue, $rue, $code, $ville, $userId]);
                } 
                
                else {
                    $stmt = $conn->prepare("UPDATE pact.proprive SET denomination = ?, telephone = ?, mail = ?, numeroRue = ?, rue = ?, codePostal = ?, ville = ? WHERE idU = ?");
                    $stmt->execute([$denomination, $telephone, $mail, $numeroRue, $rue, $code, $ville, $userId]);
                }

                $_SESSION['success'] = "Informations mises à jour avec succès.";
                header("Location: index.php");
                exit();
            }

            catch (Exception $e) {
                $_SESSION['errors'][] = "Erreur lors de la mise à jour des informations : " . $e->getMessage();
            }
        }
    }
?>
 
<!DOCTYPE html>
<html lang="en">
    <head> 
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
        <link rel="icon" href="img/logo.png" type="image/x-icon">
        <title>Modifier mes informations</title>
    </head>
    <body id ="body_creation_compte" class="creation-compte">
        <aside id="asideRetour">
            <button id="retour">
                <img src="img/logo.png" alt="Logo" title="Retour page précédente"/>
                Retour
            </button>
        </aside>
        
        <h1 id="changerInfoTitre">Modifier mes informations</h1>

        <?php
            if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])) {
                echo '<div id="messageErreur" class="messageErreur">';
                
                foreach ($_SESSION['errors'] as $error) {
                    echo "<p>$error</p>";
                }
                
                echo '</div>';
                unset($_SESSION['errors']);
            }
        ?>

        <div id="messageErreur" class="messageErreur"></div>

        <form id = "formPro" action="changeAccountPro.php" method="post" enctype="multipart/form-data">
            
            <div id="divPFPactuelle">
                <img src="<?= $photoPath ?>" alt="Photo de Profil" id="current-profile-pic">
            </div>

            <div id="divNewPFP">
                <label for="profile-pic" id="labelPFP"> Modifier ma photo de profil</label>
                <input type="file" id="profile-pic" name="profile-pic" accept="image/*">
            </div>


            <div class="ligne1">
                <label for="denomination">Dénomination*:</label>
                <label for="telephone">Numéro de téléphone*:</label>
                
                <!-- Saisi de la dénomination -->
                <input type="text" placeholder="MonEntreprise" id="denomination" name="denomination" value="<?= isset($user['denomination']) ? htmlspecialchars($user['denomination']) : '' ?>" required>

                <!-- Saisi du numéro de téléphone -->
                <input type="tel" placeholder="06 01 02 03 04" id="telephone" name="telephone" value="<?= isset($user['telephone']) ? htmlspecialchars($user['telephone']) : '' ?>" required>
            </div>



            <div class="ligne2">
                <!-- Saisi de l'adresse mail -->
                <label for="email">Adresse mail*:</label>
                <input type="email" placeholder="exemple@gmail.com" id="email" name="email" value="<?= isset($user['mail']) ? htmlspecialchars($user['mail']) : '' ?>" required>
            </div>


            
            <div class="ligne3">
                <!-- Saisi de l'adresse postale -->
                <label for="adresse">Adresse postale*:</label>
                <input type="text" placeholder ="123 Rue de Brest" id="adresse" name="adresse" value="<?= isset($user['numerorue']) && isset($user['rue']) ? htmlspecialchars($user['numerorue']) . ' ' . htmlspecialchars($user['rue']) : '' ?>" required>
                <br>
            </div>


            
            <div class="ligne4"> 
                <label for="code">Code postal*:</label>
                <label for="ville">Ville*:</label>
                
                <!-- Saisi du code postale -->
                <input type="text" placeholder="29200" id="code" name="code" value="<?= isset($user['codepostal']) ? htmlspecialchars($user['codepostal']) : '' ?>" required>

                <!-- Saisi de la ville -->
                <input type="text" placeholder="Brest" id="ville" name="ville" value="<?= isset($user['ville']) ? htmlspecialchars($user['ville']) : '' ?>" required>
            </div>
            
            <button type="submit" id="boutonInscription">Valider</button>
        </form>


        <section id="apiKey">
            <?php
                $stmt = $conn -> prepare ("SELECT * from pact._utilisateur WHERE idu = $userId");
                $stmt -> execute();
                $infoPro = $stmt -> fetch(PDO::FETCH_ASSOC);
            ?>
            <?php 
                // print_r($infoPro);
            ?>
            <p>Votre Clé API :</p>

            <?php if($infoPro["apikey"]){?>
                <p id = "valueAPIkey"> <?=$infoPro["apikey"]?></p>
                <p id="buttonAPIkey" onclick="generateAPIkey()">Générer ma clé API</p>
                <!-- <p id = "buttonAPIkey" onclick="generateAPIkey()">Regénérer ma clé API</p>
            <?php 
                } 
                // else {
            ?>
                <p id = "valueAPIkey"></p>
                <p id = "buttonAPIkey" onclick="generateAPIkey()">Générer ma clé API</p>
            <?php 
                // } 
            ?> -->
        </section>
    </body>

    <script>
        // function generateAPIkey() {
        //     fetch('generateAPIkey.php', {
        //         method: 'POST',
        //         headers: {
        //             'Content-Type': 'application/json'
        //         },
        //         body: JSON.stringify({ membre: false })
        //     })
        //     .then(response => response.json())
        //     .then(data => {
        //         // Traitement de la réponse
        //         if (data.status === 'success') {
        //             // alert('Clé API générée avec succès : ' + data.apikey);
        //             document.getElementById("valueAPIkey").innerHTML = data.apikey;
        //         } 
                
        //         else {
        //             alert('Erreur : ' + data.message);
        //         }
        //     })
        //     .catch(error => {
        //         console.error('Erreur lors de la requête fetch :', error);
        //         alert('Erreur lors de la requête fetch : ' + error.message);
        //     });
        // }

        function generateAPIkey() {
            fetch('generateAPIkey.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ membre: false })
            })
            .then(response => response.json())
            .then(data => {
                // Traitement de la réponse
                if (data.status === 'success') {
                    // Mettre à jour l'élément avec la clé API
                    document.getElementById("valueAPIkey").innerHTML = data.apikey;

                    // Changer dynamiquement le texte du bouton en fonction de l'API key générée
                    const apiButton = document.getElementById("buttonAPIkey");
                    if (apiButton) {
                        apiButton.innerHTML = 'Regénérer ma clé API';
                    }
                } else {
                    alert('Erreur : ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erreur lors de la requête fetch :', error);
                alert('Erreur lors de la requête fetch : ' + error.message);
            });
        }




        document.getElementById('profile-pic').addEventListener('change', function(event) {
            // Récupérer le fichier sélectionné
            var file = event.target.files[0];

            if (file) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    // Afficher immédiatement la nouvelle image dans le DOM
                    document.getElementById('current-profile-pic').src = e.target.result;

                    // Désactiver le bouton de soumission du formulaire jusqu'à ce que l'upload soit terminé
                    document.getElementById('boutonInscription').disabled = true;

                    var formData = new FormData();

                    // Ajouter l'image au FormData
                    formData.append('profile-pic', file);

                    fetch('uploadProfilePic.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            // Mettre à jour l'image avec le chemin retourné
                            document.getElementById('current-profile-pic').src = data.newPhotoPath;
                            // alert('Photo de profil mise à jour !');
                        } 
                        
                        else {
                            alert('Erreur : ' + data.message);
                        }

                        // Réactiver le bouton de soumission
                        document.getElementById('boutonInscription').disabled = false;
                    })
                    .catch(error => {
                        console.error('Erreur lors de l\'upload de la photo.', error);
                        alert('Erreur lors de l\'upload de la photo.');
                        document.getElementById('boutonInscription').disabled = false;
                    });
                };

                // Lire le fichier comme URL de données (pour l'afficher immédiatement)
                reader.readAsDataURL(file);
            }
        });
    </script>
    
    <script src="js/validationFormInscription.js"></script>
    <script src="js/setColor.js"></script>

</html>


<?php
    // session_start();
    // require_once 'db.php';

    // // Récupérer l'ID de l'utilisateur depuis la session
    // $userId = $_SESSION['idUser'];

    // // Vérifier si un fichier a été envoyé
    // if (isset($_FILES['profile-pic']) && $_FILES['profile-pic']['error'] === UPLOAD_ERR_OK) {
    //     $file = $_FILES['profile-pic'];

    //     // Types de fichiers autorisés
    //     $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

    //     if (in_array($file['type'], $allowedTypes)) {
    //         $targetDir = './img/profile_picture/';

    //         // Vérifier si le répertoire existe, sinon le créer
    //         if (!is_dir($targetDir)) {
    //             mkdir($targetDir, 0777, true);
    //         }

    //         // Générer un nom de fichier unique
    //         $targetFile = $targetDir . uniqid('profile_', true) . basename($file['name']);

    //         // Déplacer le fichier téléchargé vers le répertoire de destination
    //         if (move_uploaded_file($file['tmp_name'], $targetFile)) {
    //             try {
    //                 // Vérifier si l'image existe déjà dans la base de données
    //                 $stmtImage = $conn->prepare("SELECT * FROM pact._image WHERE url = ?");
    //                 $stmtImage->execute([$targetFile]);
    //                 $imageExist = $stmtImage->fetch(PDO::FETCH_ASSOC);

    //                 // $stmtCurrentPhoto = $conn->prepare("SELECT url FROM pact._photo_profil WHERE idU = ?");
    //                 // $stmtCurrentPhoto->execute([$userId]);
    //                 // $currentPhoto = $stmtCurrentPhoto->fetch(PDO::FETCH_ASSOC);

    //                 // // Si une photo de profil existe, supprimer l'ancienne image du répertoire et de la base de données
    //                 // if ($currentPhoto && $currentPhoto['url'] !== "./img/profile_picture/default.svg") {
    //                 //     // Supprimer le fichier image de l'ancien chemin
    //                 //     if (file_exists($currentPhoto['url'])) {
    //                 //         unlink($currentPhoto['url']);
    //                 //     }

    //                 //     // Supprimer l'ancienne photo de la base de données
    //                 //     $stmtDeletePhoto = $conn->prepare("DELETE FROM pact._photo_profil WHERE idU = ?");
    //                 //     $stmtDeletePhoto->execute([$userId]);
    //                 // }

    //                 // Si l'image n'existe pas déjà, l'ajouter à la table _image
    //                 if (!$imageExist) {
    //                     $stmtInsertImage = $conn->prepare("INSERT INTO pact._image (url, nomimage) VALUES (?, ?)");
    //                     $imageName = basename($targetFile);
    //                     $stmtInsertImage->execute([$targetFile, $imageName]);
    //                 }

    //                 // Mettre à jour la photo de profil de l'utilisateur
    //                 $stmtUpdatePhoto = $conn->prepare("UPDATE pact._photo_profil SET url = ? WHERE idU = ?");
    //                 $stmtUpdatePhoto->execute([$targetFile, $userId]);

    //                 // Retourner la nouvelle URL de l'image pour l'affichage dynamique
    //                 echo json_encode(['status' => 'success', 'newPhotoPath' => $targetFile]);
    //             } 
                
    //             catch (Exception $e) {
    //                 echo json_encode(['status' => 'error', 'message' => 'Erreur lors de la mise à jour de la photo de profil : ' . $e->getMessage()]);
    //             }
    //         } 
            
    //         else {
    //             echo json_encode(['status' => 'error', 'message' => 'Échec du téléchargement de l\'image.']);
    //         }
    //     } 
        
    //     else {
    //         echo json_encode(['status' => 'error', 'message' => 'Seules les images JPG, PNG ou GIF sont autorisées.']);
    //     }
    // } 
    
    // else {
    //     echo json_encode(['status' => 'error', 'message' => 'Aucun fichier téléchargé.']);
    // }
?>
