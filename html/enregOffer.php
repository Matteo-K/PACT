<?php
$pageDirection = isset($_POST['pageCurrent']) ? $_POST['pageCurrent'] : 1;
$idOffre = $_POST["idOffre"];
$idUser = $_POST["idUser"];

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
        // si il y a un résultat, on l'ajoute dans la liste
        if ($res !== false) {
          foreach ($res as $elem) {
            array_push($options,$elem["nomoption"]);
          }
        }
        // Si à la une est coché && n'est pas dans la base : ajoute à la base
        if (isset($_POST["aLaUne"]) && !in_array("ALaUne",$options)) {
          $stmt = $conn->prepare("INSERT INTO pact._option_offre (idoffre, nomoption) VALUES (?, 'aLaUne')");
          $stmt->execute([$idOffre]);
        }
        // Si à la une est pas sélectionné && est dans la base : supprime de la base
        if (!isset($_POST["aLaUne"]) && in_array("ALaUne",$options)) {
          $stmt = $conn->prepare("DELETE FROM pact._option_offre WHERE idoffre= ? AND nomoption='aLaUne'");
          $stmt->execute([$idOffre]);
        }
        // Si en relief est coché && n'est pas dans la base : ajoute à la base
        if (isset($_POST["enRelief"]) && !in_array("EnRelief",$options)) {
          $stmt = $conn->prepare("INSERT INTO pact._option_offre (idoffre, nomoption) VALUES (?, 'EnRelief')");
          $stmt->execute([$idOffre]);
        }
        // Si en relief est pas sélectionné && est dans la base : supprime de la base
        if (!isset($_POST["enRelief"]) && in_array("EnRelief",$options)) {
          $stmt = $conn->prepare("DELETE FROM pact._option_offre WHERE idoffre= ? AND nomoption='EnRelief'");
          $stmt->execute([$idOffre]);
        }
        break;
      
      case 2:
        // Détails offre update

        print_r($_POST);
        
        // Informations obligatoires (Titre, Description) + résumé
        $titre = $_POST["nom"];
        $description = $_POST["description"];
        $resume = empty($_POST["resume"]) ? null : $_POST["resume"];
        $stmt = $conn->prepare("UPDATE pact._offre SET nom= ?, description= ?, resume= ? WHERE idoffre= ?");
        $stmt->execute([$titre, $description, $resume, $idOffre]);

        $categorie = $_POST["categorie"];

        // Traitement des images
        $dossierImg = "../../img/imageOffre/";
        $imageCounter = 0;  // Compteur pour renommer les images

        $nbImages = count($_FILES['ajoutPhoto']['name']); //nb d'images uploadé

        // Boucle à travers chaque fichier uploadé
        for ($i = 0; $i < $nbImages; $i++) {
          $fileTmpPath = $_FILES['ajoutPhoto']['tmp_name'][$i];
          $fileName = $_FILES['ajoutPhoto']['name'][$i];
          $fileError = $_FILES['ajoutPhoto']['error'][$i];

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
            // else {
            //   echo "Erreur lors du téléchargement de l'image $fileName.<br>";
            // }
          } 
          // else {
          //   echo "Le fichier $fileName n'est pas un type d'image valide.<br>";
          //   echo "Erreur lors du téléchargement de l'image $fileName (Erreur $fileError).<br>";
          // }
        }


        // Ajout des informations suivant la catégorie de l'offre
        switch ($_POST["categorie"]) {
          case 'restaurant':
            break;
          case 'parc':
            break;
          case 'activite':
            break;
          case 'spectacle':
            break;
          case 'visite':
            break;
          
          default:
            # code...
            break;
        }

        // Traitement des tags (Bon courage!)

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

        // Vérification Si l'adresse n'éxiste pas déjà dans la base de donnée
        $stmt = $conn->prepare("SELECT * FROM pact._adresse WHERE codepostal = ? AND ville = ? AND pays = ? AND rue = ? AND numerorue = ?");
        $stmt->execute([$codePostal, $ville, $pays, $rue, $numerorue]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        // Insertion si elle n'existe pas
        if ($result === false) {
          $stmt = $conn->prepare("INSERT INTO pact._adresse (codepostal, ville, pays, rue, numerorue) values (?, ?, ?, ?, ?)");
          $stmt->execute([$codePostal, $ville, $pays, $rue, $numerorue]);
        }

        // Insertion dans localisation
        // Ajout ou modification
        $stmt = $conn->prepare("SELECT * FROM pact._localisation WHERE idoffre = ?");
        $stmt->execute([$idOffre]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        // ajout si aucun résultat
        if ($result === false) {
          $stmt = $conn->prepare("INSERT INTO pact._localisation (idoffre, codepostal, ville, pays, rue, numerorue) VALUES (?, ?, ?, ?, ?, ?)");
          $stmt->execute([$idOffre, $codePostal, $ville, $pays, $rue, $numerorue]);
        } else {
          // modifiaction
          $stmt = $conn->prepare("UPDATE pact._localisation SET codepostal=?, ville=?, pays=?, rue=?, numerorue=?  WHERE idoffre= ?");
          $stmt->execute([$codePostal, $ville, $pays, $rue, $numerorue, $idOffre]);
        }
        break;

      case 4:
        // Détails Contact update
        $mail = $_POST["mail"];
        $telephone = empty($_POST["phone"]) ? null : $_POST["phone"];
        $affiche = $_POST['DisplayNumber'] == "Oui" ? true : false;
        $site = empty($_POST["webSide"]) || $_POST["webSide"] == "https://" ? null : $_POST["webSide"];
        $stmt = $conn->prepare("UPDATE pact._offre SET mail=?, telephone=?, affiche=?, urlsite=? WHERE idoffre= ?");
        $stmt->execute([$mail, $telephone, $affiche, $site, $idOffre]);
        break;

      case 5:
        // Détails Horaires update
        $jour_semaine = ["Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi","Dimanche"];
        
        // Ajoute dans la base de donnée les heures pour chaque jour
        // Si fermé ou les champs son vides, on ajoute pas dans la base de donnée
        foreach ($jours_semaine as $jour) {
            // Vérifier si le jour est fermé
            if (!isset($_POST["check$jour"])) {
                // Récupérer les horaires
                $horairesOuv1 = $_POST["horairesOuv1$jour"] ?? null; // Utiliser null si non défini
                $horairesF1 = $_POST["horairesF1$jour"] ?? null; // Utiliser null si non défini
                $horairesOuv2 = $_POST["horairesOuv2$jour"] ?? null; // Utiliser null si non défini
                $horairesF2 = $_POST["horairesF2$jour"] ?? null; // Utiliser null si non défini

                // Ajouter les horaires au Midi
                if ($horairesOuv1 && $horairesF1) {
                    // Vérifier que l'horaire d'ouverture est avant l'horaire de fermeture
                    if ($horairesOuv1 < $horairesF1) {
                        // Requête ajout dans la base de donnée midi
                        /*
                        $stmt = $conn->prepare("SELECT o.idoffre FROM pact._offre o ORDER BY idoffre DESC LIMIT 1");
                        $stmt->execute();
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);*/
                        if ($result !== false) {
                          // si existe déjà, on modifie
                          /*
                          $stmt = $conn->prepare("UPDATE pact._offre SET mail=?, telephone=?, affiche=?, urlsite=? WHERE idoffre= ?");
                          $stmt->execute([$mail, $telephone, $affiche, $site, $idOffre]);*/
                        } {
                          // sinon ajoute
                          
                        }
                    }
                }

                if ($horairesOuv2 && $horairesF2) {
                    // Requête ajout dans la base de donnée Soir
                }
            }
        }
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
    //document.getElementById('myForm').submit();
</script>