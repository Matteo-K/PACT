<?php
require_once "config.php";  

// Fonction pour lister les images dans un dossier
function listImage($idOffre, $idComment)
{
    $dossier = 'img/imageAvis/' . $idOffre . '/' . $idComment . '/';

    if (!is_dir($dossier)) {
        return ['success' => false, 'message' => 'Le dossier n\'existe pas ou est invalide.'];
    }

    $files = array_diff(scandir($dossier), ['.', '..']);
    $fileUrls = [];
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    foreach ($files as $file) {
        if (is_file($dossier . $file)) {
            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (in_array($extension, $allowedExtensions)) {
                $fileUrls[] = $dossier . $file;
            }
        }
    }

    return count($fileUrls) > 0 ? ['success' => true, 'files' => $fileUrls] : ['success' => false, 'message' => 'Aucune image valide trouvée.'];
}

// Fonction pour déplacer les images d'un dossier temporaire vers un dossier cible
function moveImagesToOfferFolder($idOffre, $idComment, $tempFolder, $uploadBasePath = __DIR__ . '/uploads')
{
    $result = ['success' => [], 'errors' => []];
    if (!$idOffre) {
        $result['errors'][] = "L'identifiant de l'offre (idOffre) est manquant.";
        return $result;
    }

    $targetFolder = $uploadBasePath . '/' . $idOffre . '/' . $idComment;

    if (!is_dir($targetFolder) && !mkdir($targetFolder, 0777, true)) {
        $result['errors'][] = "Impossible de créer le dossier cible : $targetFolder";
        return $result;
    }

    $images = glob("$tempFolder/*.{jpg,jpeg,png,gif,webp}", GLOB_BRACE);

    if (!$images) {
        $result['errors'][] = "Aucune image trouvée dans le dossier temporaire : $tempFolder";
        return $result;
    }

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_GET['membre'])) {
        $_SESSION['review_success'] = "Avis soumis avec succès!";

        $note = $_POST['note'] ?? null;
        $dateAvis = $_POST['date'] ?? null;
        $compagnie = $_POST['compagnie'] ?? null;
        $titreAvis = $_POST['titre'] ?? null;
        $texteAvis = $_POST['avis'] ?? null;
        $idOffre = $_POST['idoffre'] ?? null;
        $uniqueId = $_POST['uniqueField'] ?? null;

        if (!$note || !$dateAvis || !$compagnie || !$titreAvis || !$texteAvis || !$idOffre || !$uniqueId) {
            die("Données manquantes ou invalides.");
        }

        $stmt = $conn->prepare("SELECT pseudo FROM pact.membre WHERE idu = ?");
        $stmt->execute([$idUser]);
        $result = $stmt->fetch();
        if (!$result) {
            die("Utilisateur introuvable.");
        }

        $pseudo = $result['pseudo'];
        list($year, $month) = explode('-', $dateAvis);
        $months = ['01' => 'Janvier', '02' => 'Février', '03' => 'Mars', '04' => 'Avril', '05' => 'Mai', '06' => 'Juin',
                   '07' => 'Juillet', '08' => 'Août', '09' => 'Septembre', '10' => 'Octobre', '11' => 'Novembre', '12' => 'Décembre'];
        $monthInWords = $months[$month] ?? 'Inconnu';

        $tempFolder = "img/imageAvis/temp_uploads/$uniqueId";

        $stmt = $conn->prepare("INSERT INTO pact._commentaire (idU, content, datePublie) VALUES (?, ?, NOW()) RETURNING idC;");
        $stmt->execute([$idUser, $texteAvis]);
        $idComment = $stmt->fetchColumn();

        $stmt = $conn->prepare("INSERT INTO pact._avis (idc, idoffre, note, companie, mois, annee, titre, lu) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, false)");
        $stmt->execute([$idComment, $idOffre, $note, $compagnie, $monthInWords, $year, $titreAvis]);

        $moveResult = moveImagesToOfferFolder($idOffre, $idComment, $tempFolder, "img/imageAvis/");

        $imageStmt = $conn->prepare("INSERT INTO pact._image (url, nomimage) VALUES (?, ?)");
        $avisImageStmt = $conn->prepare("INSERT INTO pact._avisimage (idc, url) VALUES (?, ?)");
        $mesImages = listImage($idOffre, $idComment);

        if (isset($mesImages['files'])) {
            foreach ($mesImages['files'] as $file) {
                $fileName = pathinfo($file, PATHINFO_BASENAME);
                $imageStmt->execute([$file, $fileName]);
                $avisImageStmt->execute([$idComment, $file]);
            }
        }

    } elseif (isset($_GET['pro'])) {
        $contenuReponse = $_POST['reponsePro'] ?? null;
        $idAvis = $_POST['hiddenInputIdAvis'] ?? null;
        $idOffre = $_POST['idoffre'] ?? null;
        echo "test";

        if (!$contenuReponse || !$idAvis || !$idOffre) {
            die("Données manquantes ou invalides.");
        }

        $stmt = $conn->prepare("INSERT INTO pact.reponse (idpro, contenureponse, idc_avis) VALUES (?, ?, ?)");
        $stmt->execute([$idUser, $contenuReponse, $idAvis]);

        $stmt = $conn->prepare("UPDATE pact._avis SET lu = true WHERE idc = ?");
        $stmt->execute([$idAvis]);
    }
    ?>
    <script>
        let form = document.createElement('form');
        form.action = "detailsOffer.php";
        form.method = "post";

        let input = document.createElement('input');
        input.type = "hidden";
        input.name = "idoffre";
        input.value = <?= $idOffre ?>;

        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    </script>
    <?php
}
