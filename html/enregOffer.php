<?php
$pageDirection = isset($_POST['pageCurrent']) ? $_POST['pageCurrent'] : 1;
$idOffre = $_POST["idOffre"];
$idUser = $_POST["idUser"];

$idUser = 4;

session_start();
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pageBefore'])) {
  $pageBefore = $_POST['pageBefore'];

  /* Création d'une nouvelle offre */
  if (empty($idOffre)) {
    // Initialisation d'une offre

    // ##### Main info de l'offre #####
    /* obtention de la nouvelle id de l'offre */
    try {
      $stmt = $conn->prepare("SELECT o.idoffre FROM pact._offre o ORDER BY idoffre DESC LIMIT 1");
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      $idOffre = intval($result["idoffre"])+1;
    } catch (PDOException $e) {
        echo "Une erreur s'est produite lors de la récupération de l'offre: \n" . $e->getMessage() . "\n";
    }
  
    /* Obtention de la date current */
    $currentDateTime = new DateTime();
    $date = $currentDateTime->format('Y-m-d H:i:s.u');
  
    /* création d'une offre avec la nouvelle id */
    try {
      $stmt = $conn->prepare("INSERT INTO pact._offre (idu, statut, idoffre, nom, description, mail, telephone, affiche, urlsite, resume, datecrea) VALUES (?, ?, ?, null, null, null, null, null, null, null, ?)");
      $stmt->execute([$idUser, 'inactif', $idOffre, $date]);
    } catch (PDOException $e) {
      echo "Une erreur s'est produite lors de la création de l'offre: \n" . $e->getMessage() . "\n";
    }
    
    // ##### Abonnement à propos de l'offre #####
    /* Obtention du type de l'abonnement */
    $typeOffre;
    switch ($_POST["typeOffre"]) {
      case 'premium':
        $typeOffre = 'Premium';
        break;
      
      case 'standard':
        $typeOffre = 'Basique';
        break;

      case 'gratuit':
        $typeOffre = 'Gratuit';
        break;

      default:
        echo "Type d'offre inconnue";
        break;
    }

    /* Définition de l'abonnement */
    try {
      $stmt = $conn->prepare("INSERT INTO pact._abonner (idoffre, nomabonnement) VALUES (?, ?)");
      $stmt->execute([$idOffre, $typeOffre]);
    } catch (PDOException $e) {
      echo "Une erreur s'est produite lors de la saisit de l'abonnement de l'offre: \n" . $e->getMessage() . "\n";
    }
  } else {
    switch ($pageBefore) {
      case 1:
        // Insert/Modifications des options
        // Récupération des offres
        $options = Array();
        $stmt = $conn->prepare("SELECT nomoption FROM pact._option_offre WHERE idoffre = ?");
        $stmt->execute([$idOffre]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        foreach ($res as $elem) {
          array_push($options,$elem["nomoption"]);
        }
        // Si à la une est coché && n'est pas dans la base : ajoute à la base
        if (isset($_POST["aLaUne"]) && !in_array("aLaUne",$options)) {
          $stmt = $conn->prepare("INSERT INTO pact._option_offre (idoffre, nomoption) VALUES (?, 'aLaUne')");
          $stmt->execute([$idOffre]);
        }
        // Si à la une est pas sélectionné && est dans la base : supprime de la base
        if (!isset($_POST["aLaUne"]) && in_array("aLaUne",$options)) {
          $stmt = $conn->prepare("DELETE FROM pact._option_offre WHERE idoffre= ? AND nomoption='aLaUne'");
          $stmt->execute([$idOffre]);
        }
        // Si en relief est coché && n'est pas dans la base : ajoute à la base
        if (isset($_POST["enRelief"]) && !in_array("enRelief",$options)) {
          $stmt = $conn->prepare("INSERT INTO pact._option_offre (idoffre, nomoption) VALUES (?, 'enRelief')");
          $stmt->execute([$idOffre]);
        }
        // Si en relief est pas sélectionné && est dans la base : supprime de la base
        if (!isset($_POST["enRelief"]) && in_array("enRelief",$options)) {
          $stmt = $conn->prepare("DELETE FROM pact._option_offre WHERE idoffre= ? AND nomoption='EnRelief'");
          $stmt->execute([$idOffre]);
        }



        break;
      
      case 2:
        // Détails offre update
        // Faire un switch suivant le type d'offre (restaurant, ...)
        $dossierImg = "../../img/imageOffre/";

        print_r($_FILES);

        $imageCounter = 0;  // Compteur pour renommer les images

        $totalFiles = count($_FILES['photos']['name']); //nb d'images uploadé

        // Boucle à travers chaque fichier uploadé
        for ($i = 0; $i < $totalFiles && $imageCounter < $maxImages; $i++) {
          $fileTmpPath = $_FILES['photos']['tmp_name'][$i];
          $fileName = $_FILES['photos']['name'][$i];
          $fileError = $_FILES['photos']['error'][$i];

        // Vérifie si l'image a été uploadée sans erreur
          if ($fileError === UPLOAD_ERR_OK) {

            // Renommage de l'image (idOffre3image0, idOffre3image1, etc.)
            $fileName = $idOffre . '-' . $imageCounter . '.' . strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
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
        break;
      
      case 3:
        // Détails Localisation update
        // insertion dans adresse
        $adresse = $_POST["adresse2"];
        $codePostal = $_POST["codepostal"];
        $ville = $_POST["ville2"];
        $pays = 'France';
        // obtention du split de l'adresse par une expression régulière
        preg_match('/^(\d+)\s+(.*)$/', $adresse, $matches);
        $numerorue = $matches[1];
        $rue = $matches[2];

        $stmt = $conn->prepare("SELECT * FROM pact._adresse WHERE codepostal = ? AND ville = ? AND pays = ? AND rue = ? AND numerorue = ?");
        $stmt->execute([$codePostal, $ville, $pays, $rue, $numerorue]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result === false) {
          $stmt = $conn->prepare("INSERT INTO pact._adresse (codepostal, ville, pays, rue, numerorue) values (?, ?, ?, ?, ?)");
          $stmt->execute([$codePostal, $ville, $pays, $rue, $numerorue]);
        }

        // insertion dans localisation
        $stmt = $conn->prepare("insert into pact._localisation (idoffre, codepostal, ville, pays, rue, numerorue) values (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$idOffre, $codePostal, $ville, $pays, $rue, $numerorue]);
        break;

      case 4:
        // Détails Contact update
        break;

      case 5:
        // Détails Horaires update
        break;

      case 6:
        // Détails Prévisualisation update
        break;

      case 7:
        // Détails Paiement update
        break;

      default:
        # code...
        break;
    }
  }
}

// Redirection vers les bonnes pages
if ($pageDirection >= 1) {
  ?>
  <form id="myForm" action="manageOffer.php" method="POST">
    <input type="hidden" name="page" value="<?php echo $pageDirection; ?>">
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