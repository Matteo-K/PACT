<?php
$pageDirection = $_POST['pageCurrent'] ?? 1;
$idOffre = $_POST["idOffre"];
$idUser = $_POST["idUser"];

session_start();
require_once 'db.php';


if (isset($_POST['pageBefore'])) {
  if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['pageBefore'] > -1) {
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
        $idOffre = intval($result["idoffre"]) + 1;
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
          break;
      }

      /* Définition de l'abonnement */
      try {
        $stmt = $conn->prepare("INSERT INTO pact._abonner (idoffre, nomabonnement) VALUES (?, ?)");
        $stmt->execute([$idOffre, $typeOffre]);
      } catch (PDOException $e) {}
  }
  switch ($pageBefore) {
    case 1:
      // Gestion des options d'offre
      $options = [];
      if (isset($_POST["aLaUne"])) {
        $options[] = "ALaUne";
      }
      if (isset($_POST["enRelief"])) {
        $options[] = "EnRelief";
      }
      
      $stmt = $conn->prepare("SELECT * FROM pact._option_offre o LEFT JOIN pact._dateoption d ON d.idoption = o.idoption WHERE o.idoffre = ? ORDER BY o.idoption DESC ;");
      $stmt->execute([$idOffre]);

      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if ($row["datefin"] < date('Y-m-d')) {
        }
      }

      break;

    case 2:
      // Détails offre update

      // Informations obligatoires (Titre, Description) + résumé
      $titre = $_POST["nom"];
      $description = $_POST["description"];
      $resume = empty($_POST["resume"]) ? null : $_POST["resume"];
      $stmt = $conn->prepare("UPDATE pact._offre SET nom= ?, description= ?, resume= ? WHERE idoffre= ?");
      $stmt->execute([$titre, $description, $resume, $idOffre]);

      $categorie = $_POST["categorie"];


      // Traitement des images
      $dossierTemp = "./img/tempImage/";
      $dossierImg = "./img/imageOffre/";

      $anciennesImagesTotal = [];
      $stmt = $conn->prepare("SELECT * FROM pact._illustre WHERE idoffre = ?");
      $stmt->execute([$idOffre]);
      while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $anciennesImagesTotal[] = $result["url"];
      }

      $anciennesImagesRestantes = $_POST["imageExistante"] ?? [];

      if ($anciennesImagesRestantes != []) {

        if (file_exists($dossierTemp)) {
          rmdir($dossierTemp);
        }

        mkdir($dossierTemp, 0777, true); // Crée le dossier temporaire 

        var_dump($anciennesImagesRestantes);

        //On déplace les anciennes images conservées vers un dossier temporaire
        foreach ($anciennesImagesRestantes as $num => &$lien) {
          move_uploaded_file($lien, $dossierTemp . $num . "." . pathinfo($lien)['extension']);
          $lien = $dossierTemp . $num . '.' . pathinfo($lien)['extension'];
        }

        var_dump($anciennesImagesTotal);


        foreach ($anciennesImagesTotal as $imgA) {
          // Supprime l'image du serveur et de la BDD
          if (file_exists($imgA)) {
              unlink($imgA);
              $stmt = $conn->prepare("DELETE FROM pact._illustre WHERE idoffre = ?");
              $stmt->execute([$idOffre]);
              $stmt = $conn->prepare("DELETE FROM pact._image WHERE url = ?");
              $stmt->execute([$imgA]);
          }
        }        

        //On remet les anciennes images gardées dans la BDD et sur le serveur 
        foreach ($anciennesImagesRestantes as $num => $lien) {
          $fileExtension = strtolower(pathinfo($lien)['extension']);
          $newFileName = $idOffre . '-' . $num . '.' . $fileExtension;
          $dossierImgNom = $dossierImg . $newFileName;

          rename($lien, $dossierImgNom);

          try {
            $stmt = $conn->prepare("INSERT INTO pact._image (url, nomImage) VALUES (?, ?)");
            $stmt->execute([$dossierImgNom, $newFileName]);

            $stmt = $conn->prepare("INSERT INTO pact._illustre (idoffre, url) VALUES (?, ?)");
            $stmt->execute([$idOffre, $dossierImgNom]);
          } catch (PDOException $e) {
              error_log("Erreur BDD : " . $e->getMessage());
          }
        }
      }

      if (file_exists($dossierTemp)) {
        rmdir($dossierTemp);
      }

      $nbNouvellesImages = count($_FILES['ajoutPhoto']['name']);
      $nbAnciennesImages = count($anciennesImagesRestantes);
      $nbTotalImages = $nbNouvellesImages + $nbAnciennesImages;
      $imageCounter = $nbAnciennesImages;  // Compteur pour renommer les images

      // Boucle à travers chaque NOUVEAU fichier uploadé
      for ($i = 0; $i < $nbNouvellesImages; $i++) {
        $fileTmpPath = $_FILES['ajoutPhoto']['tmp_name'][$i];
        $fileName = $_FILES['ajoutPhoto']['name'][$i];
        $fileError = $_FILES['ajoutPhoto']['error'][$i];

        // Vérifie si l'image a été uploadée sans erreur
        if ($fileError === UPLOAD_ERR_OK) {
          // Renommage de l'image (idOffre3image0 -> 3-0.png, idOffre3image1 -> 3-1.png, etc.)
          $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
          $newFileName = $idOffre . '-' . $imageCounter . '.' . $fileExtension;
          $dossierImgNom = $dossierImg . $newFileName;

          // Déplace l'image vers le dossier cible
          move_uploaded_file($fileTmpPath, $dossierImgNom);
          $imageCounter++;

          try {
            $stmt = $conn->prepare("INSERT INTO pact._image (url, nomImage) VALUES (?, ?)");
            $stmt->execute([$dossierImgNom, $newFileName]);

            $stmt = $conn->prepare("INSERT INTO pact._illustre (idoffre, url) VALUES (?, ?)");
            $stmt->execute([$idOffre, $dossierImgNom]);
          } catch (PDOException $e) {
              error_log("Erreur BDD : " . $e->getMessage());
          }
        }
      }


      // Ajout des informations suivant la catégorie de l'offre
      switch ($categorie) {
        case 'restaurant':
          // Obtention des données
          $gammeDePrix = $_POST["gamme_prix"];
          // Création/Modification d'une offre restaurant
          $stmt = $conn->prepare("SELECT * from pact._restauration where idoffre=?");
          $stmt->execute([$idOffre]);
          $result = $stmt->fetch(PDO::FETCH_ASSOC);
          // Si pas de donnée, on créer
          if ($result === false) {
            $stmt = $conn->prepare("INSERT INTO pact._restauration (idoffre, gammedeprix) VALUES (?, ?) ");
            $stmt->execute([$idOffre, $gammeDePrix]);
          } else {
            // sinon modifie
            $stmt = $conn->prepare("UPDATE pact._restauration SET gammedeprix=? where idoffre=?");
            $stmt->execute([$gammeDePrix, $idOffre]);
          }
          break;
        case 'parc':
          // Obtention des données
          $ageMin = 0; // Modifier si ajouter dans html
          $nbAttraction = 0; // Modifier si ajouter dans html
          $prixMinimale = 0; // Modifier si ajouter dans html
          $urlPlan = null; // Modifier si ajouter dans html
          // Création/Modification d'une offre de parc d'attraction
          $stmt = $conn->prepare("SELECT * from pact._parcattraction where idoffre=?");
          $stmt->execute([$idOffre]);
          $result = $stmt->fetch(PDO::FETCH_ASSOC);
          // Si pas de donnée, on créer
          if ($result === false) {
            $stmt = $conn->prepare("INSERT INTO pact._parcattraction (idoffre, agemin, nbattraction, prixminimal, urlplan) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$idOffre, $ageMin, $nbAttraction, $prixMinimale, $urlPlan]);
          } else {
            // sinon modifie
            $stmt = $conn->prepare("UPDATE pact._parcattraction SET agemin=?, nbattraction=?, prixminimal=?, urlplan=? WHERE idoffre=?");
            $stmt->execute([$ageMin, $nbAttraction, $prixMinimale, $urlPlan, $idOffre]);
          }

          // Gestion des images
          $dossierImg = "img/imageOffre/";
          $imageCounter = 0;  // Compteur pour renommer les images

          $nbImages = count($_FILES['image1Park']['name']); //nb d'images uploadé

          // Boucle à travers chaque fichier uploadé
          for ($i = 0; $i < $nbImages; $i++) {
            $fileTmpPath = $_FILES['image1Park']['tmp_name'][$i];
            $fileName = $_FILES['image1Park']['name'][$i];
            $fileError = $_FILES['image1Park']['error'][$i];

            // Vérifie si l'image a été uploadée sans erreur
            if ($fileError === UPLOAD_ERR_OK) {
              // Renommage de l'image (idOffre3image0, idOffre3image1, etc.)
              $fileName = $idOffre . '-' . $imageCounter . '.' . strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
              $dossierImgNom = $dossierImg . $fileName;

              // Déplace l'image vers le dossier cible
              if (move_uploaded_file($fileTmpPath, $dossierImgNom)) {
                $imageCounter++;
              }

              try {
                $stmt = $conn->prepare("INSERT INTO pact._image (url, nomImage) VALUES (?, ?)");
                $stmt->execute([$dossierImgNom, $fileName]);

                $stmt = $conn->prepare("INSERT INTO pact._illustre (idoffre, url) VALUES (?, ?)");
                $stmt->execute([$idOffre, $dossierImgNom]);
              } catch (PDOException $e) {
              }
            }
          }
          break;
        case 'activite':
          // Obtention des données
          $duree = $_POST["duréeAct"] ?? null;
          $ageMin = $_POST["ageAct"] ?? null;
          $accessibilite = $_POST["Accessibilite"] == "Acces" ? true : null; // Modifier si ajouter dans BDD
          $prixMinimale = null; // Modifier si ajouter dans html
          $prestation = null; // Modifier si ajouter dans html
          // Création/Modification d'une offre de parc d'attraction
          $stmt = $conn->prepare("SELECT * from pact._activite where idoffre=?");
          $stmt->execute([$idOffre]);
          $result = $stmt->fetch(PDO::FETCH_ASSOC);
          // Si pas de donnée, on créer
          if ($result === false) {
            $stmt = $conn->prepare("INSERT INTO pact._activite (idoffre, duree, agemin, prixminimal, prestation) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$idOffre, $duree, $ageMin, $prixMinimale, $prestation]);
          } else {
            // sinon modifie
            $stmt = $conn->prepare("UPDATE pact._activite SET duree=?, agemin=?, prixminimal=?, prestation=? WHERE idoffre=?");
            $stmt->execute([$duree, $ageMin, $prixMinimale, $prestation, $idOffre]);
          }
          break;
        case 'spectacle':
          // Obtention des données
          $duree = $_POST["DuréeShow"] ?? null;
          $nbPlace = $_POST["nbPlaceShow"] ?? null;
          $prixMinimale = null; // Modifier si ajouter dans html
          // Création/Modification d'une offre de parc d'attraction
          $stmt = $conn->prepare("SELECT * from pact._spectacle where idoffre=?");
          $stmt->execute([$idOffre]);
          $result = $stmt->fetch(PDO::FETCH_ASSOC);
          // Si pas de donnée, on créer
          if ($result === false) {
            $stmt = $conn->prepare("INSERT INTO pact._spectacle (idoffre, duree, nbplace, prixminimal) VALUES (?, ?, ?, ?)");
            $stmt->execute([$idOffre, $duree, $nbPlace, $prixMinimale]);
          } else {
            // sinon modifie
            $stmt = $conn->prepare("UPDATE pact._spectacle SET duree=?, nbplace=?, prixminimal=? WHERE idoffre=?");
            $stmt->execute([$duree, $nbPlace, $prixMinimale, $idOffre]);
          }
          break;
        case 'visite':
          // Obtention des données
          $guide = null; // Modifier si ajouter dans html
          $duree = $_POST["numberHVisit"] ?? null;
          $prixMinimale = null; // Modifier si ajouter dans html
          $accessibilite = $_POST["Accessibilité"] == "access" ? true : null;
          // Création/Modification d'une offre de parc d'attraction
          $stmt = $conn->prepare("SELECT * from pact._visite where idoffre=?");
          $stmt->execute([$idOffre]);
          $result = $stmt->fetch(PDO::FETCH_ASSOC);
          // Si pas de donnée, on créer
          if ($result === false) {
            $stmt = $conn->prepare("INSERT INTO pact._visite (idoffre, guide, duree, prixminimal, accessibilite) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$idOffre, $guide, $duree, $prixMinimale, $accessibilite]);
          } else {
            // sinon modifie
            $stmt = $conn->prepare("UPDATE pact._visite SET guide=?, duree=?, prixminimal=?, accessibilite=? WHERE idoffre=?");
            $stmt->execute([$guide, $duree, $prixMinimale, $accessibilite, $idOffre]);
          }

          // Ajout des langues
          $langues = $_POST["texteLangueVisit"] ?? "";
          $tabLangue = explode(" ", $langues);
          // Supprime toute les langues dans la table visite_langue
          $stmt = $conn->prepare("DELETE FROM pact._visite_langue WHERE idoffre= ?");
          $stmt->execute([$idOffre]);
          foreach ($tabLangue as $langue) {
            // si n'existe pas dans la table Langue alors on ajoute
            $stmt = $conn->prepare("SELECT * from pact._langue WHERE langue=?");
            $stmt->execute([$langue]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            // si pas dans la table, on ajoute dans langue et visite langue
            if ($result === false) {
              $stmt = $conn->prepare("INSERT INTO pact._langue (langue) VALUES (?)");
              $stmt->execute([$langue]);
              $stmt = $conn->prepare("INSERT INTO pact._visite_langue (idoffre, langue) VALUES (?,?)");
              $stmt->execute([$idoffre, $langue]);
            } else {
              $stmt = $conn->prepare("SELECT * from pact._visite_langue WHERE idoffre=? AND langue=?");
              $stmt->execute([$idOffre, $langue]);
              $result = $stmt->fetch(PDO::FETCH_ASSOC);
              // Si pas dans la table visite_lang, on rajoute
              if ($result === false) {
                $stmt = $conn->prepare("INSERT INTO pact._visite_langue (idoffre, langue) VALUES (?,?)");
                $stmt->execute([$idoffre, $langue]);
              }
            }
          }
          break;

        default:
          # code...
          break;
      }



      // Traitement des tags
      $tags = $_POST["tags"] ?? [];

      //On supprime tous les anciens tags et on rajoute les tags actuellement sélectionnés
      switch ($categorie) {
        case 'restaurant':
          $stmt = $conn->prepare("DELETE * FROM pact._tag_restaurant WHERE idoffre = ?");
          break;
        case 'parc':
          $stmt = $conn->prepare("DELETE * FROM pact._tag_parc WHERE idoffre = ?");
          break;
        case 'activite':
          $stmt = $conn->prepare("DELETE * FROM pact._tag_act  WHERE idoffre = ?");
          break;
        case 'spectacle':
          $stmt = $conn->prepare("DELETE * FROM pact._tag_spec  WHERE idoffre = ?");
          break;
        case 'visite':
          $stmt = $conn->prepare("DELETE * FROM pact._tag_visite  WHERE idoffre = ?");
          break;
        default:
          break;
      }

      foreach ($tags as $key => $tag) {

        //On ajoute donc tous les tags entrés
        switch ($categorie) {
          case 'restaurant':
            $stmt = $conn->prepare("INSERT INTO pact._tag_restaurant (idoffre, nomtag) VALUES (?, ?);");
            break;
          case 'parc':
            $stmt = $conn->prepare("INSERT INTO pact._tag_parc (idoffre, nomtag) VALUES (?, ?)");
            break;
          case 'activite':
            $stmt = $conn->prepare("INSERT INTO pact._tag_act (idoffre, nomtag) VALUES (?, ?)");
            break;
          case 'spectacle':
            $stmt = $conn->prepare("INSERT INTO pact._tag_spec (idoffre, nomtag) VALUES (?, ?)");
            break;
          case 'visite':
            $stmt = $conn->prepare("INSERT INTO pact._tag_visite (idoffre, nomtag) VALUES (?, ?)");
            break;
          default:
            break;
        }
        $stmt->execute([$idOffre, $tag]);
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
      $telephone = empty($_POST["phone"]) ? null : preg_replace('/[^\d]/', '', $_POST["phone"]);
      if (strlen($telephone) == 11 && substr($telephone, 0, 2) == "33") {
        $telephone = "0" . substr($telephone, 2);
      }
      $affiche = $_POST['DisplayNumber'] == "Oui" ? true : null;
      $site = empty($_POST["webSide"]) || $_POST["webSide"] == "https://" ? null : $_POST["webSide"];
      $stmt = $conn->prepare("UPDATE pact._offre SET mail=?, telephone=?, affiche=?, urlsite=? WHERE idoffre= ?");
      $stmt->execute([$mail, $telephone, $affiche, $site, $idOffre]);
      break;

    case 5:
      // Détails Horaires update
      $jour_semaine = ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche"];

      // Ajoute dans la base de donnée les heures pour chaque jour
      // Si fermé ou les champs son vides, on ajoute pas dans la base de donnée
      foreach ($jour_semaine as $jour) {
        // Vérifier si le jour est fermé
        if (!isset($_POST["check$jour"])) {
          // Récupérer les horaires
          $horairesOuv1 = $_POST["horairesOuv1$jour"] ?? null;
          $horairesF1 = $_POST["horairesF1$jour"] ?? null;
          $horairesOuv2 = $_POST["horairesOuv2$jour"] ?? null;
          $horairesF2 = $_POST["horairesF2$jour"] ?? null;

          // Ajouter les horaires au Midi
          if ($horairesOuv1 && $horairesF1) {
            // Vérifier que l'horaire d'ouverture est avant l'horaire de fermeture
            if ($horairesOuv1 < $horairesF1) {
              // Requête ajout dans la base de donnée midi
              $stmt = $conn->prepare("SELECT * FROM pact._horairemidi WHERE idoffre=? AND jour=?");
              $stmt->execute([$idOffre, $jour]);
              $result = $stmt->fetch(PDO::FETCH_ASSOC);
              if ($result !== false) {
                // si existe déjà, on modifie
                $stmt = $conn->prepare("UPDATE pact._horairemidi SET heureouverture=?, heurefermeture=? where idoffre=? and jour=?");
                $stmt->execute([$horairesOuv1, $horairesF1, $idOffre, $jour]);
              } else {
                // sinon ajoute
                $stmt = $conn->prepare("INSERT INTO pact._horairemidi (idoffre, jour, heureouverture, heurefermeture) VALUES (?, ?, ?, ?)");
                $stmt->execute([$idOffre, $jour, $horairesOuv1, $horairesF1]);
              }
              // Ajout du soir si les horaires du midi sont correctes
              if (($horairesOuv2 && $horairesF2) && ($horairesF1 < $horairesOuv2)) {
                // Requête ajout dans la base de donnée Soir
                $stmt = $conn->prepare("SELECT * FROM pact._horairesoir WHERE idoffre=? AND jour=?");
                $stmt->execute([$idOffre, $jour]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($result !== false) {
                  // si existe déjà, on modifie
                  $stmt = $conn->prepare("UPDATE pact._horairesoir SET heureouverture=?, heurefermeture=? where idoffre=? and jour=?");
                  $stmt->execute([$horairesOuv2, $horairesF2, $idOffre, $jour]);
                } else {
                  // sinon ajoute
                  $stmt = $conn->prepare("INSERT INTO pact._horairesoir (idoffre, jour, heureouverture, heurefermeture) VALUES (?, ?, ?, ?)");
                  $stmt->execute([$idOffre, $jour, $horairesOuv2, $horairesF2]);
                }
              }
            }
          }
        }
      }
      break;

    case 6:
      // Pad de modification pour la prévisualisation
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