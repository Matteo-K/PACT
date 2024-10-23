<?php
$page = isset($_POST['page']) ? $_POST['page'] : 1;
$idOffre = isset($_POST["idOffre"])?$_POST["idOffre"]:"";
$idUser = $_POST["idUser"];

session_start();
require_once 'db.php';

/* Création d'une nouvelle offre */
if ($idOffre == "") {
   /* obtention de la nouvelle id de l'offre */
   $stmt = $conn->prepare("SELECT pact.idoffre FROM _offre ORDER BY idoffre DESC LIMIT 1;");
     $stmt->execute();
     $result = $stmt->fetch(PDO::FETCH_ASSOC);
   print_r($result);
   $idOffre = $result;

   /* Obtention de la date current */
   $currentDateTime = new DateTime();
   $date = $currentDateTime->format('Y-m-d H:i:s.u');

   /* création d'une offre avec la nouvelle id */
   $stmt = $conn->prepare("INSERT INTO pact._offre (idu, statut, idoffre, nom, description, mail, telephone, affiche, urlsite, resume, datecrea) VALUES (?, ?, ?, null, null, null, null, null, null, null, ?)");
   $stmt->execute([$idUser, 'inactif', $idOffre, $date]);
 }
$idOffre = 15;

if ($_SERVER["REQUEST_METHOD"] == "POST" && $page < 1) {
  ?>
  <form id="myForm" action="manageOffer.php" method="POST">
    <input type="hidden" name="page" value="<?php echo $page; ?>">
    <input type="hidden" name="idOffre" value="<?php echo $idOffre; ?>">
  </form>
  <?php
} else {
  header("Location: search.php");
  exit();
}
?>
<script>
    document.getElementById('myForm').submit();
</script>






<?php

    if (condition) {
      # code...
    }

    $dossierImg = "../../img/imageOffre/";

    // Compteur pour renommer les images
    $imageCounter = 0;


    // Boucle à travers chaque fichier uploadé
        for ($i = 0; $i < $totalFiles && $imageCounter < $maxImages; $i++) {
            $fileTmpPath = $_FILES['photos']['tmp_name'][$i];
            $fileName = $_FILES['photos']['name'][$i];
            $fileError = $_FILES['photos']['error'][$i];

        // Vérifie si l'image a été uploadée sans erreur
            if ($fileError === UPLOAD_ERR_OK) {
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                // Renommage de l'image (image0, image1, etc.)
                $fileName = 'idOffre' . $idOffre . 'image' . $imageCounter . '.' . strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $dossierImgNom = $dossierImg . $newFileName;

                // Déplace l'image vers le dossier cible
                if (move_uploaded_file($fileTmpPath, $dossierImgNom)) {
                    echo "L'image $fileName a été uploadée avec succès.<br>";
                    $imageCounter++;
                } 
                else {
                    echo "Erreur lors du téléchargement de l'image $fileName.<br>";
                }
            } else {
                echo "Le fichier $fileName n'est pas un type d'image valide.<br>";
                echo "Erreur lors du téléchargement de l'image $fileName (Erreur $fileError).<br>";
            }
        }


?>