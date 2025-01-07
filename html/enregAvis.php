<?php
function listImage($idOffre, $idComment)
{
    // Chemin du dossier où les images sont stockées
    $dossier = 'img/imageAvis/' . $idOffre . '/' . $idComment . '/';

    // Affiche le chemin pour le débogage
    echo "Chemin du dossier : " . $dossier . "<br>";

    // Vérifie si le dossier existe et est valide
    if (!is_dir($dossier)) {
        return ['success' => false, 'message' => 'Le dossier n\'existe pas ou est invalide.'];
    }

    // Liste les fichiers dans le dossier (en excluant '.' et '..')
    $files = array_diff(scandir($dossier), ['.', '..']);
    $fileUrls = [];

    // Extensions autorisées
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    // Parcours chaque fichier et vérifie si c'est une image valide
    foreach ($files as $file) {
        // On vérifie que c'est bien un fichier et pas un sous-dossier
        if (is_file($dossier . '/' . $file)) {
            // Récupération de l'extension du fichier
            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            // Vérification si l'extension est dans la liste autorisée
            if (in_array($extension, $allowedExtensions)) {
                $fileUrls[] = $dossier . $file;
            }
        }
    }

    // Si des fichiers ont été trouvés
    if (count($fileUrls) > 0) {
        return ['success' => true, 'files' => $fileUrls];
    } else {
        return ['success' => false, 'message' => 'Aucune image valide trouvée.'];
    }
}




// Fonction pour déplacer les images du dossier temporaire vers le dossier de l'offre
function moveImagesToOfferFolder($idOffre, $idComment, $tempFolder, $uploadBasePath = __DIR__ . '/uploads')
{
    $result = [
        'success' => [],
        'errors' => []
    ];

    // Vérification de l'idOffre
    if (!$idOffre) {
        $result['errors'][] = "L'identifiant de l'offre (idOffre) est manquant.";
        return $result;
    }

    // Chemin du dossier cible
    $targetFolder = $uploadBasePath . '/' . $idOffre . "/" . $idComment;

    // Créer le dossier cible si nécessaire
    if (!is_dir($targetFolder)) {
        if (!mkdir($targetFolder, 0777, true)) {
            $result['errors'][] = "Impossible de créer le dossier cible : $targetFolder";
            return $result;
        }
    }

    // Récupération des fichiers dans le dossier temporaire
    $images = glob("$tempFolder/*.{jpg,jpeg,png,gif,webp}", GLOB_BRACE);

    if (!$images) {
        $result['errors'][] = "Aucune image trouvée dans le dossier temporaire : $tempFolder";
        return $result;
    }

    // Déplacement des images
    foreach ($images as $image) {
        $extension = pathinfo($image, PATHINFO_EXTENSION);
        $idImage = uniqid();
        $newFilePath = $targetFolder . "/" . $idImage . "." . $extension;

        if (rename($image, $newFilePath)) {
            $result['success'][] = $newFilePath;
        } else {
            $result['errors'][] = "Erreur lors du déplacement de l'image : $image";
        }
    }

    return $result;
}

// Traitement des données envoyées par le formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($GET["membre"]) && isset($_POST["note"])) {
    $_SESSION['review_success'] = "Avis soumis avec succès!";
    // Préparation des données
    $note = $_POST['note'];
    $dateAvis = $_POST['date'];
    $compagnie = $_POST['compagnie'];
    $titreAvis = $_POST['titre'];
    $texteAvis = $_POST['avis'];
    $idOffre = $_POST['idoffre'];
    $uniqueId = $_POST['uniqueField'];

    // Récupération des données de l'utilisateur
    $stmt = $conn->prepare("SELECT * FROM pact.membre WHERE idu = ?");
    $stmt->execute([$idUser]);
    $result = $stmt->fetchAll();

    $pseudo = $result[0]['pseudo'];
    list($year, $month) = explode('-', $dateAvis);

    // Tableau des mois en lettres
    $months = [
        '01' => 'Janvier',
        '02' => 'Février',
        '03' => 'Mars',
        '04' => 'Avril',
        '05' => 'Mai',
        '06' => 'Juin',
        '07' => 'Juillet',
        '08' => 'Août',
        '09' => 'Septembre',
        '10' => 'Octobre',
        '11' => 'Novembre',
        '12' => 'Décembre'
    ];
    $monthInWords = $months[$month] ?? 'Inconnu'; // Gérer les mois invalides

    $tempFolder = "img/imageAvis/temp_uploads/" . $uniqueId;

    $stmt = $conn->prepare("INSERT INTO pact._commentaire (idU, content, datePublie)VALUES (?, ?, NOW())RETURNING idC;");
    $stmt->execute([$idUser, $texteAvis]);
    $idComment = $stmt->fetchColumn();

    $stmt = $conn->prepare("INSERT INTO pact._avis (idc, idoffre, note, companie, mois, annee, titre, lu) 
    VALUES (?, ?, ?, ?, ?, ?, ?, false)");
    $stmt->execute([$idComment, $idOffre, $note, $compagnie, $monthInWords, $year, $titreAvis]);


    // Déplacer les images vers le dossier de l'offre
    $moveResult = moveImagesToOfferFolder($idOffre, $idComment, $tempFolder, "img/imageAvis/");

    // Insertion des images dans la base de données
    $image = $conn->prepare("INSERT INTO pact._image (url, nomimage) VALUES (?, ?)");
    $imageAvis = $conn->prepare("INSERT INTO pact._avisimage (idc, url) VALUES (?, ?)");
    $mesImages = listImage($idOffre, $idComment);
    print_r($mesImages);

    foreach ($mesImages['files'] as $file) {
        $fileName = pathinfo($file, PATHINFO_BASENAME);

        // Exécution de l'insertion de l'image
        if (!$image->execute([$file, $fileName])) {
            $result['errors'][] = "Erreur lors de l'insertion de l'image dans la base de données.";
        }

        // Exécution de l'insertion dans la table _avisimage
        if (!$imageAvis->execute([$idComment, $file])) {
            $result['errors'][] = "Erreur lors de l'insertion de l'image liée à l'avis dans la base de données.";
        }
    }
?>
    <script>
        let form = document.createElement('form');
        form.action = "detailsOffer";
        form.method = "post";

        let input = document.createElement('input');
        input.type = "hidden";
        input.name = "idoffre";
        input.value = <?= $idOffre ?>; // Make sure this is correctly echoed into the JavaScript

        form.appendChild(input);
        document.body.appendChild(form); // Append the form to the body (or another container)

        form.submit(); // Call submit method with parentheses to submit the form
    </script>
<?php

}
?>