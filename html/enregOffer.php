<?php
require_once 'config.php';
$pageDirection = $_POST['pageCurrent'] ?? 1;
$idOffre = $_POST["idOffre"];
$idUser = $_POST["idUser"];

$stepManageOffer = unserialize($_POST['ArrayStepManageOffer']);

if ($pageDirection != -1) {
  if (isset($_POST['pageBefore'])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['pageBefore'] > -1) {
      $pageBefore = $_POST['pageBefore'];

      /* Création d'une nouvelle offre */
      if (empty($idOffre)) {
        // Initialisation d'une offre
        $modif = false;
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
      } else {
        $modif = true;
      }

      if ($modif && $pageDirection == 1) {
          ?>
              <form id="leftSelectOffer" action="manageOffer.php" method="post">
                  <input type="hidden" name="pageCurrent" value="2">
                  <input type="hidden" name="idOffre" value="<?php echo $idOffre ?>">
              </form>
              <script>
                  document.getElementById("leftSelectOffer").submit();
              </script>
          <?php
      }

      $page = $stepManageOffer[$pageBefore - 1]["page"];
      switch ($page) {
        case "selectOffer.php":
          // Gestion des options d'offre
            $options = [];
            if (isset($_POST["aLaUne"])) {
              $options[] = ["ALaUne",$_POST['nbWeekALaUne'],$_POST['nbWeekALaUne']*20];
            }
            if (isset($_POST["enRelief"])) {
              $options[] = ["EnRelief",$_POST['nbWeekEnRelief'],$_POST['nbWeekEnRelief']*10];
            }
            foreach ($options as $key => $value) {
              $stmt = $conn->prepare("INSERT INTO pact.option (idOffre,dateLancement,dateFin,duree_total,prix_total,nomOption) VALUES (?,NULL,NULL,?,?,?)");
              $stmt->execute([$idOffre, $value[1], $value[2], $value[0]]);
            }
    
            // while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            //   if ($row["datefin"] < date('Y-m-d')) {
            //   }
            // }

          break;

        case "detailsOffer.php":
          // Détails offre update

          // Informations obligatoires (Titre, Description) + résumé
          $titre = $_POST["nom"];
          $description = $_POST["description"];
          $resume = empty($_POST["resume"]) ? null : $_POST["resume"];
          $stmt = $conn->prepare("UPDATE pact._offre SET nom= ?, description= ?, resume= ? WHERE idoffre= ?");
          $stmt->execute([$titre, $description, $resume, $idOffre]);

          $categorie = $_POST["categorie"];



          // Il reste plus qu'a ajoute les images dans la BDD EWEN donc prends les images directement dans le dossier
          // Les images sont nommer par un nombre unique donc ne changerons jamais ca va etre plus simple avec la bdd pour les supprimer
          // Et on a plus le probleme des images qui se supprime pas 


          // Traitement des images
          // $dossierTemp = "./img/tempImage/";
          $dossierImg = "./img/imageOffre/" . $idOffre . "/";

          //suppression des images existantes dans la bdd pour eviter les doublon ou erreur de suppression

          $stmt = $conn->prepare("SELECT * FROM pact._illustre WHERE idoffre = ?");
          $stmt->execute([$idOffre]);
          $imgASuppr = $stmt->fetchAll();
          
          $stmtilluste = $conn->prepare("DELETE FROM pact._illustre WHERE url = ?");
          $stmtimg = $conn->prepare("DELETE FROM pact._image WHERE url = ?");
          foreach ($imgASuppr as $key => $value) {
            $urlImgSupp = $value['url'];
            $stmtilluste->execute([$urlImgSupp]);
            $stmtimg->execute([$urlImgSupp]);
          }

          //ajout des images du dossier de l'idOffre

          $imgs = scandir($dossierImg);
          foreach ($imgs as $img) {
              if ($img !== "." && $img !== "..") {
                  $filePath = $dossierImg . $img;
                  $stmt = $conn->prepare("INSERT INTO pact._image (url, nomImage) VALUES (?, ?)");
                  $stmt->execute([$filePath, $img]);
                  $stmt = $conn->prepare("INSERT INTO pact._illustre (url, idoffre) VALUES (?, ?)");
                  $stmt->execute([$filePath, $idOffre]);
              }
          }



          // $anciennesImagesTotal = [];
          // $stmt = $conn->prepare("SELECT * FROM pact._illustre WHERE idoffre = ?");
          // $stmt->execute([$idOffre]);
          // while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
          //   $anciennesImagesTotal[] = $result["url"];
          // }

          // $anciennesImagesRestantes = $_POST["imageExistante"] ?? [];

          // if ($anciennesImagesRestantes != []) {

          //   if (!file_exists($dossierTemp)) {
          //     mkdir($dossierTemp, 0777, true); // Crée le dossier temporaire 
          //   }
          //   else{
          //     $fichiers = glob($dossierTemp . "*"); // Récupère tous les fichiers du dossier temporaire
              
          //     foreach ($fichiers as $fichier) {
          //       unlink($fichier); // Supprime le fichier
          //     }
          //   }


          //   var_dump($anciennesImagesRestantes);

          //   //On déplace les anciennes images conservées vers un dossier temporaire
          //   foreach ($anciennesImagesRestantes as $num => &$lien) {
          //     copy($lien, 
          //           $dossierTemp . $num . "." . pathinfo($lien)['extension']);
          //     $lien = $dossierTemp . $num . "." . pathinfo($lien)['extension'];
          //   }

          //   foreach ($anciennesImagesTotal as $imgA) {
          //     // Supprime l'image du serveur et de la BDD
          //     if (file_exists($imgA)) {
          //         unlink($imgA);
          //         $stmt = $conn->prepare("DELETE FROM pact._illustre WHERE idoffre = ?");
          //         $stmt->execute([$idOffre]);
          //         $stmt = $conn->prepare("DELETE FROM pact._image WHERE url = ?");
          //         $stmt->execute([$imgA]);
          //     }
          //   } 
            

          //   //On remet les anciennes images gardées dans la BDD et sur le serveur 
          //   foreach ($anciennesImagesRestantes as $num => $lien) {
          //     $fileExtension = strtolower(pathinfo($lien)['extension']);
          //     $newFileName = $idOffre . '-' . $num . '.' . $fileExtension;
          //     $dossierImgNom = $dossierImg . $newFileName;

          //     copy($lien, 
          //           $dossierImgNom);

          //     try {
          //       $stmt = $conn->prepare("INSERT INTO pact._image (url, nomImage) VALUES (?, ?)");
          //       $stmt->execute([$dossierImgNom, $newFileName]);

          //       $stmt = $conn->prepare("INSERT INTO pact._illustre (idoffre, url) VALUES (?, ?)");
          //       $stmt->execute([$idOffre, $dossierImgNom]);
          //     } catch (PDOException $e) {
          //         error_log("Erreur BDD : " . $e->getMessage());
          //     }

          //   }
          // }

          // $stmt = $conn->prepare("SELECT * FROM pact._illustre WHERE idoffre = ?");
          // $stmt->execute([$idOffre]);
          // while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
          //   $anciennesImagesTotal[] = $result["url"];
          // }

          // print_r($_FILES);


          // $nbNouvellesImages = count($_FILES['images']);
          // $nbAnciennesImages = count($anciennesImagesRestantes);
          // $nbTotalImages = $nbNouvellesImages + $nbAnciennesImages;
          // $imageCounter = $nbAnciennesImages;  // Compteur pour renommer les images

          // if($nbTotalImages > 10){
          //   $nbNouvellesImages = 10 - $nbAnciennesImages;
          //   $nbTotalImages = $nbNouvellesImages + $nbAnciennesImages;
          // }

          // // Boucle à travers chaque NOUVEAU fichier uploadé
          // for ($i = 0; $i < $nbNouvellesImages; $i++) {
          //   $fileTmpPath = $_FILES['images']['tmp_name'][$i];
          //   $fileName = $_FILES['images']['name'][$i];
          //   $fileError = $_FILES['images']['error'][$i];

          //   // Vérifie si l'image a été uploadée sans erreur
          //   if ($fileError === UPLOAD_ERR_OK) {
          //     // Renommage de l'image (idOffre3image0 -> 3-0.png, idOffre3image1 -> 3-1.png, etc.)
          //     $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
          //     $newFileName = $idOffre . '-' . $imageCounter . '.' . $fileExtension;
          //     $dossierImgNom = $dossierImg . $newFileName;
            
          //     try {
          //       // Déplace l'image vers le dossier d'images des offres
          //       move_uploaded_file($fileTmpPath, $dossierImgNom);
          //       $imageCounter++;

          //       //On insère l'url de l'image sur le serveur dans la BDD
          //       $stmt = $conn->prepare("INSERT INTO pact._image (url, nomImage) VALUES (?, ?)");
          //       $stmt->execute([$dossierImgNom, $newFileName]);

          //       //On insère la relation entre l'image et l'offre
          //       $stmt = $conn->prepare("INSERT INTO pact._illustre (idoffre, url) VALUES (?, ?)");
          //       $stmt->execute([$idOffre, $dossierImgNom]);
          //     } catch (PDOException $e) {
          //         error_log("Erreur BDD : " . $e->getMessage());
          //     }
          //   }
          // }


          // Ajout des informations suivant la catégorie de l'offre
          switch ($categorie) {
            case 'restaurant':
              // Obtention des données
              $gammeDePrix = $_POST["rest_gamme_prix"];

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

              // Gestion des images
              $dossierImg = "img/imageMenu/". $idOffre . "/";

              // Vérifie si le dossier existe, sinon le crée
              if (!is_dir($dossierImg)) {
                mkdir($dossierImg, 0777, true);
              }

              $stmt = $conn->prepare("SELECT * FROM pact._menu WHERE idoffre = ?");
              $stmt->execute([$idOffre]);
              $imgASuppr = $stmt->fetchAll();
              
              $stmtmenu = $conn->prepare("DELETE FROM pact._menu WHERE menu = ?");
              $stmtimg = $conn->prepare("DELETE FROM pact._image WHERE url = ?");
              foreach ($imgASuppr as $key => $value) {
                $urlImgSupp = $value['menu'];
                $stmtmenu->execute([$urlImgSupp]);
                $stmtimg->execute([$urlImgSupp]);
              }

              $nbImage = count($_FILES['rest_ajoutPhotoMenu']['name']);
              for ($i=0; $i < $nbImage; $i++) {
                
                // Boucle à travers chaque fichier uploadé
                $fileTmpPath = $_FILES['rest_ajoutPhotoMenu']['tmp_name'][$i];
                $fileName = $_FILES['rest_ajoutPhotoMenu']['name'][$i];
                $fileError = $_FILES['rest_ajoutPhotoMenu']['error'][$i];

                // Vérifie si l'image a été uploadée sans erreur
                if ($fileError === UPLOAD_ERR_OK) {
                  // Renommage de l'image (idOffre3image0, idOffre3image1, etc.)
                  $fileName = $idOffre . '-' . $i . '.' . strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                  $dossierImgNom = $dossierImg . $fileName;

                  // Déplace l'image vers le dossier cible
                  move_uploaded_file($fileTmpPath, $dossierImgNom);

                  try {
                    $stmt = $conn->prepare("INSERT INTO pact._image (url, nomImage) VALUES (?, ?)");
                    $stmt->execute([$dossierImgNom, $fileName]);

                    $stmt = $conn->prepare("INSERT INTO pact._menu (menu, idoffre) VALUES (?, ?)");
                    $stmt->execute([$dossierImgNom, $idOffre]);
                  } catch (PDOException $e) {
                  }
                }
              }

              // Menu

              break;
            case 'parc':
              // Obtention des données
              $ageMin = $_POST["park_ageMin"];
              $nbAttraction = $_POST["park_nbAttrac"];
              $prixMinimale = $_POST["park_prixMin"];
              $dossierImgNom = ""; // urlPlan

              // Gestion des images
              $dossierImg = "img/imagePlan/". $idOffre . "/";

              // Vérifie si le dossier existe, sinon le crée
              if (!is_dir($dossierImg)) {
                mkdir($dossierImg, 0777, true);
              }

              // Boucle à travers chaque fichier uploadé
              $fileTmpPath = $_FILES['park_plan']['tmp_name'][0];
              $fileName = $_FILES['park_plan']['name'][0];
              $fileError = $_FILES['park_plan']['error'][0];

              // Vérifie si l'image a été uploadée sans erreur
              if ($fileError === UPLOAD_ERR_OK) {
                // Renommage de l'image (idOffre3image0, idOffre3image1, etc.)
                $fileName = 'plan' . $idOffre . '.' . strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $dossierImgNom = $dossierImg . $fileName;

                // Déplace l'image vers le dossier cible
                move_uploaded_file($fileTmpPath, $dossierImgNom);

                try {
                  $stmt = $conn->prepare("INSERT INTO pact._image (url, nomImage) VALUES (?, ?)");
                  $stmt->execute([$dossierImgNom, $fileName]);
                } catch (PDOException $e) {
                }
              }

              $stmt = $conn->prepare("SELECT * from pact._parcattraction where idoffre=?");
              $stmt->execute([$idOffre]);
              $result = $stmt->fetch(PDO::FETCH_ASSOC);
              // Si pas de donnée, on créer
              if ($result === false) {
                $stmt = $conn->prepare("INSERT INTO pact._parcattraction (idoffre, agemin, nbattraction, prixminimal, urlplan) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$idOffre, $ageMin, $nbAttraction, $prixMinimale, $dossierImgNom]);
              } else {
                // sinon modifie
                $stmt = $conn->prepare("UPDATE pact._parcattraction SET agemin=?, nbattraction=?, prixminimal=?, urlplan=? WHERE idoffre=?");
                $stmt->execute([$ageMin, $nbAttraction, $prixMinimale, $dossierImgNom, $idOffre]);
              }
              break;
            case 'activite':
              // Obtention des données
              $duree = $_POST["actv_min"];
              $ageMin = $_POST["actv_ageMin"];
              $prixMinimale = $_POST["actv_prixMin"];
              $accessibilite = $_POST["actv_access"] ?? [];
              $acces = count($accessibilite) > 0;
              $prestationInclus = $_POST["prestationInclu"] ?? [];
              $prestationNonInclus = $_POST["prestationNonInclu"] ?? [];

              $stmt = $conn->prepare("SELECT * from pact._activite where idoffre=?");
              $stmt->execute([$idOffre]);
              $result = $stmt->fetch(PDO::FETCH_ASSOC);
              // Si pas de donnée, on créer
              if ($result === false) {
                $stmt = $conn->prepare("INSERT INTO pact._activite (idoffre, duree, agemin, prixminimal) VALUES (?, ?, ?, ?)");
                $stmt->execute([$idOffre, $duree, $ageMin, $prixMinimale]);
              } else {
                // sinon modifie
                $stmt = $conn->prepare("UPDATE pact._activite SET duree=?, agemin=?, prixminimal=? WHERE idoffre=?");
                $stmt->execute([$duree, $ageMin, $prixMinimale, $idOffre]);
              }

              // Accessibilité
              $stmt = $conn->prepare("DELETE FROM pact._offreaccess WHERE idoffre = ?");
              $stmt->execute([$idOffre]);
              foreach ($accessibilite as $value) {
                $stmt = $conn->prepare("INSERT INTO pact._offreaccess (idoffre, nomaccess) VALUES (? , ?) ");
                $stmt->execute([$idOffre, $value]);
              }
              
              // Prestation inclus
              $stmt = $conn->prepare("DELETE FROM pact._offreprestation_inclu WHERE idoffre = ?");
              $stmt->execute([$idOffre]);
              foreach ($prestationInclus as $value) {
                $stmt = $conn->prepare("INSERT INTO pact._offreprestation_inclu (idoffre, nompresta) VALUES (? , ?) ");
                $stmt->execute([$idOffre, $value]);
              }
              
              // Prestation non inclus
              $stmt = $conn->prepare("DELETE FROM pact._offreprestation_non_inclu WHERE idoffre = ?");
              $stmt->execute([$idOffre]);
              foreach ($prestationNonInclus as $value) {
                $stmt = $conn->prepare("INSERT INTO pact._offreprestation_non_inclu (idoffre, nompresta) VALUES (? , ?) ");
                $stmt->execute([$idOffre, $value]);
              }

              break;
            case 'spectacle':
              // Obtention des données
              $duree = $_POST["show_min"];
              $nbPlace = $_POST["show_nbPlace"];
              $prixMinimale = $_POST["show_prixMin"];

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
              $guide = isset($_POST["visit_guidee"]) && $_POST["visit_guidee"] === "guidee";
              $duree = $_POST["visit_min"];
              $prixMinimale = $_POST["visit_minPrix"];
              $accessibilite = $_POST["visit_access"];
              $access = !empty($accessibilite); 

              // Création/Modification d'une offre de visite
              $stmt = $conn->prepare("SELECT * from pact._visite where idoffre=?");
              $stmt->execute([$idOffre]);
              $result = $stmt->fetch(PDO::FETCH_ASSOC);
              // Si pas de donnée, on créer
              if ($result === false) {
                $stmt = $conn->prepare("INSERT INTO pact._visite (idoffre, guide, duree, prixminimal, accessibilite) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$idOffre, $guide ? 1 : 0, $duree, $prixMinimale, $access]);
              } else {
                // sinon modifie
                $stmt = $conn->prepare("UPDATE pact._visite SET guide=?, duree=?, prixminimal=?, accessibilite=? WHERE idoffre=?");
                $stmt->execute([$guide, $duree, $prixMinimale, $access, $idOffre]);
              }

              // Ajout des langues
              $langues = $_POST["visit_langue"] ?? [];
              // Supprime toute les langues dans la table visite_langue
              $stmt = $conn->prepare("DELETE FROM pact._visite_langue WHERE idoffre= ?");
              $stmt->execute([$idOffre]);
              foreach ($langues as $langue) {
                $stmt = $conn->prepare("INSERT INTO pact._visite_langue (idoffre, langue) VALUES (?,?)");
                $stmt->execute([$idoffre, $langue]);
              }

              // Accessibilité
              $stmt = $conn->prepare("DELETE FROM pact._offreaccess WHERE idoffre = ?");
              $stmt->execute([$idOffre]);
              foreach ($accessibilite as $value) {
                $stmt = $conn->prepare("INSERT INTO pact._offreaccess (idoffre, nomaccess) VALUES (? , ?) ");
                $stmt->execute([$idOffre, $value]);
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
              $stmt = $conn->prepare("DELETE FROM pact._tag_restaurant WHERE idoffre = ?");
              break;
            case 'parc':
              $stmt = $conn->prepare("DELETE FROM pact._tag_parc WHERE idoffre = ?");
              break;
            case 'activite':
              $stmt = $conn->prepare("DELETE FROM pact._tag_act  WHERE idoffre = ?");
              break;
            case 'spectacle':
              $stmt = $conn->prepare("DELETE FROM pact._tag_spec  WHERE idoffre = ?");
              break;
            case 'visite':
              $stmt = $conn->prepare("DELETE FROM pact._tag_visite  WHERE idoffre = ?");
              break;
            default:
              break;
          }

          $stmt->execute([$idOffre]);

          foreach ($tags as $key => $tag) {

            $tag = str_replace(" ", "_",$tag);

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

        case "localisationOffer.php":
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

        case "contactOffer.php":
          // Détails Contact update
          $mail = $_POST["mail"];
          $telephone = empty($_POST["phone"]) ? null : preg_replace('/[^\d]/', '', $_POST["phone"]);
          if ($telephone) {
            if (strlen($telephone) == 11 && substr($telephone, 0, 2) == "33") {
              $telephone = "0" . substr($telephone, 2);
            }
          }
          $affiche = $_POST['DisplayNumber'] == "Oui" ? true : null;
          $site = empty($_POST["webSide"]) || $_POST["webSide"] == "https://" ? null : $_POST["webSide"];
          $stmt = $conn->prepare("UPDATE pact._offre SET mail=?, telephone=?, affiche=?, urlsite=? WHERE idoffre= ?");
          $stmt->execute([$mail, $telephone, $affiche, $site, $idOffre]);
          break;

        case "hourlyOffer.php":

          switch ($_POST["typeOffre"]) {
            case 0: // Autre
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
            
            case 1: // Spectacle
              if (isset($_POST["dates"])) {
                $stmt = $conn->prepare("DELETE FROM pact._horaireprecise WHERE idoffre= ?");
                $stmt->execute([$idOffre]);

                // Tableau pour la convertion anglais français
                $dateJour = [
                  "Monday"    => "Lundi",
                  "Tuesday"   => "Mardi",
                  "Wednesday" => "Mercredi",
                  "Thursday"  => "Jeudi",
                  "Friday"    => "Vendredi",
                  "Saturday"  => "Samedi",
                  "Sunday"    => "Dimanche"
                ];
                $dates = $_POST["dates"];
                
                foreach ($dates as $date) {
                  $convertionDate = date("l Y-m-d", strtotime($date["trip-start"]));
                  $splitDate = explode(" ",$convertionDate);

                  $jour = $dateJour[$splitDate[0]];
                  $dateRep = new DateTime($splitDate[1]);
                  $dateRepFormatted = $dateRep->format('Y-m-d');
                  $heureDebut = $date["HRep_part1.1"];
                  $heureFin = $date["HRep_part1.2"];

                  $stmt = $conn->prepare("INSERT INTO pact._horaireprecise (jour, idoffre, heuredebut, heurefin, daterepresentation) values (?, ?, ?, ?, ?)");
                  $stmt->execute([$jour, $idOffre, $heureDebut, $heureFin, $dateRepFormatted]);
                }
              }
              break;
            default: // -1 : Aucune offre
              break;
          }

          break;

        case "previewOffer.php":
          // Pad de modification pour la prévisualisation
          break;

        case "paymentOffer.php":
          // Détails Paiement update
          break;

        default:
          # code...
          break;
      }
    }
  }
}
// Redirection vers les bonnes pages
if ($pageDirection >= 1) {
  ?>
  <form id="myForm" action="manageOffer.php" method="POST">
    <input type="hidden" name="page" value="<?php echo $pageDirection; ?>">
    <input type="hidden" name="idOffre" value="<?php echo $idOffre; ?>">
    <input type="hidden" name="save">
  </form>
  <?php
} else {
  if (!empty($idOffre)) {
    $stmt = $conn->prepare("SELECT * FROM pact.offrescomplete WHERE idoffre = :idoffre");
    $stmt->bindParam(':idoffre', $idOffre);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($result) {
      ?>
      <form action="detailsOffer.php" id="toDetailsOffer" method="post">
        <input type="hidden" name="idoffre" value="<?php echo $idOffre ?>">
      </form>
      <script>
        document.getElementById("toDetailsOffer").submit();
        </script>
      <?php
    }
  }
  header("Location: index.php");
  exit();
}
?>
<script>
  document.getElementById('myForm').submit();
</script>