<?php

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once '../config.php';
    
    $idOffre = $_POST["idoffre"];
    switch ($_POST["action"]) {
      case 'visualiser':
        ?>
          <form id="visualiser" action="../detailsOffer.php" method="POST" onsubmit="window.open('', '_blank'); this.target='_blank';">
            <input type="hidden" name="idoffre" value="<?php echo $idOffre; ?>">
            <input type="hidden" name="suppression">
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
            global $conn;
            
            foreach ($queries as $query) {
              $stmt = $conn->prepare($query);
              $stmt->execute([$idOffre]);
            }
          }

          function deleteImg($folder) {
            $images = glob($folder . '*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE);

            foreach ($images as $image) {
                if (is_file($image)) {
                    unlink($image);
                }
            }
            rmdir($folder);
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
          deleteImg("../img/imageMenu/" . $idOffre);
          deleteImg("../img/imagePlan/" . $idOffre);
          deleteImg("../img/imageOffre/" . $idOffre);

          // Redirection vers l'offre
          header("location: ../index.php");
        exit();
        break;
    }
  }
  header("location: ../index.php");
  exit();
?>