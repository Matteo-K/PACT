<?php

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once '../config.php';
    
    $idOffre = $_POST["idoffre"];
    switch ($_POST["action"]) {
      case 'visualiser':
        ?>
          <form id="visualiser" action="../detailsOffer.php" method="POST">
            <input type="hidden" name="idoffre" value="<?php echo $idOffre; ?>">
          </form>
          <script>
            document.getElementById('visualiser').submit();
          </script>
        <?php
        break;

      case 'rejeter':
        $stmt = $conn->prepare("UPDATE pact._offre SET statut='inactif' WHERE idoffre=?;");
        $stmt->execute([$idOffre]);
        
        // Redirection vers l'offre
        header("location: ../index.php");
        exit();
        break;
        
        case 'supprimer':
          function deleteOffer($queries) {
            foreach ($queries as $query) {
              $stmt = $conn->prepare($query);
              $stmt->execute([$idOffre]);
            }
          }

          $stmt = $conn->prepare("SELECT categorie FROM pact.offres WHERE idoffre=?;");
          $stmt->execute([$idOffre]);
          $res = $stmt->fetch(PDO::FETCH_ASSOC);
          
          // Information à propos de la catégorie
          switch ($res["categorie"]) {
            case 'Spectacle':
              $queries = [
                "DELETE FROM pact._spectacle WHERE idoffre=?;",
                "DELETE FROM pact._tag_spec WHERE idoffre=?;"
              ];

              deleteOffer($queries);
              break;

            case 'Restaurant':
              $stmt = $conn->prepare("SELECT menu FROM pact._menu WHERE idoffre=?;");
              $stmt->execute([$idOffre]);
              $res = $stmt->fetch(PDO::FETCH_ASSOC);
              while ($res = $stmt->fetch(PDO::FETCH_ASSOC)) {
                unlink("." . $res["menu"]);
              }

              $queries = [
                "DELETE FROM pact._restauration WHERE idoffre=?;",
                "DELETE FROM pact._tag_restaurant WHERE idoffre=?;",
                "DELETE FROM pact._menu WHERE idoffre=?;"
              ];

              deleteOffer($queries);
              break;

            case 'Activité':
              $queries = [
                "DELETE FROM pact._activite WHERE idoffre=?;",
                "DELETE FROM pact._tag_act WHERE idoffre=?;",
                "DELETE FROM pact._offreprestation_inclu WHERE idoffre=?;",
                "DELETE FROM pact._offreprestation_non_inclu WHERE idoffre=?;",
                "DELETE FROM pact._accessibilite WHERE idoffre=?;"
              ];

              deleteOffer($queries);
            break;

            case 'Parc Attraction':
              $stmt = $conn->prepare("SELECT urlplan FROM pact._parcattraction WHERE idoffre=?;");
              $stmt->execute([$idOffre]);
              $res = $stmt->fetch(PDO::FETCH_ASSOC);
              if (isset($res["urlplan"])) {
                unlink("." . $res["urlplan"]);
              }

              $queries = [
                "DELETE FROM pact._parcattraction WHERE idoffre=?;",
                "DELETE FROM pact._tag_parc WHERE idoffre=?;"
              ];

              deleteOffer($queries);
              break;

            case 'Visite':
              $queries = [
                "DELETE FROM pact._visite WHERE idoffre=?;",
                "DELETE FROM pact._offreaccess WHERE idoffre=?;",
                "DELETE FROM pact._visite_langue WHERE idoffre=?;",
                "DELETE FROM pact._tag_visite WHERE idoffre=?;"
              ];

              deleteOffer($queries);
              break;
          }
          // Supprime toute les images de tout les dossiers
          $stmt = $conn->prepare("SELECT url FROM pact._illustre WHERE idoffre=?;");
          $stmt->execute([$idOffre]);
          $res = $stmt->fetch(PDO::FETCH_ASSOC);
          while ($res = $stmt->fetch(PDO::FETCH_ASSOC)) {
            unlink("." . $res["url"]);
          }

          rmdir("../img/imageMenu/" . $idOffre);
          rmdir("../img/imagePlan/" . $idOffre);
          rmdir("../img/imageOffre/" . $idOffre);

          // Redirection vers l'offre
          header("location: ../index.php");
        exit();
        break;
    }
  }
  header("location: ../index.php");
  exit();
?>