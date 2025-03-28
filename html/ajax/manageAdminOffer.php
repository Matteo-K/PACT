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
          function deleteOffer($queries, $data) {
            global $conn;
            
            foreach ($queries as $query) {
              echo $query . " " . $data;
              $stmt = $conn->prepare($query);
              $stmt->execute([$data]);
            }
          }

          function deleteImg($folder) {
            if (!is_dir($folder)) {
                return;
            }
        
            $files = glob($folder . '/*');
        
            foreach ($files as $file) {
              if (is_dir($file)) {
                deleteImg($file);
              } else {
                unlink($file);
              }
            }
            rmdir($folder);
          }
        

          $stmt = $conn->prepare("SELECT categorie FROM pact.offres WHERE idoffre=?;");
          $stmt->execute([$idOffre]);
          $res = $stmt->fetch(PDO::FETCH_ASSOC);
          echo $res["categorie"] . "\n";
          // Information à propos de la catégorie
          switch ($res["categorie"]) {
            case 'Spectacle':
              $queries = [
                "DELETE FROM pact._spectacle WHERE idoffre=?;",
                "DELETE FROM pact._tag_spec WHERE idoffre=?;"
              ];

              deleteOffer($queries, $idOffre);
              break;

            case 'Restaurant':
              $queries = [
                "DELETE FROM pact._restauration WHERE idoffre=?;",
                "DELETE FROM pact._tag_restaurant WHERE idoffre=?;",
                "DELETE FROM pact._menu WHERE idoffre=?;"
              ];

              deleteOffer($queries, $idOffre);
              break;

            case 'Activité':
              $queries = [
                "DELETE FROM pact._activite WHERE idoffre=?;",
                "DELETE FROM pact._tag_act WHERE idoffre=?;",
                "DELETE FROM pact._offreprestation_inclu WHERE idoffre=?;",
                "DELETE FROM pact._offreprestation_non_inclu WHERE idoffre=?;",
                "DELETE FROM pact._offreaccess WHERE idoffre=?;"
              ];

              deleteOffer($queries, $idOffre);
            break;

            case 'Parc Attraction':
              $queries = [
                "DELETE FROM pact._parcattraction WHERE idoffre=?;",
                "DELETE FROM pact._tag_parc WHERE idoffre=?;"
              ];

              deleteOffer($queries, $idOffre);
              break;

            case 'Visite':
              $queries = [
                "DELETE FROM pact._visite WHERE idoffre=?;",
                "DELETE FROM pact._offreaccess WHERE idoffre=?;",
                "DELETE FROM pact._visite_langue WHERE idoffre=?;",
                "DELETE FROM pact._tag_visite WHERE idoffre=?;"
              ];

              deleteOffer($queries, $idOffre);
              break;
          }
          // Supprime toute les images de tout les dossiers
          deleteImg("../img/imageAvis/" . $idOffre);
          deleteImg("../img/imageMenu/" . $idOffre);
          deleteImg("../img/imagePlan/" . $idOffre);
          deleteImg("../img/imageOffre/" . $idOffre);

          // Suppression des avis
          $queries = [
            "DELETE FROM pact._signalementc WHERE idc=?",
            "DELETE FROM pact._reponse WHERE idc=?",
            "DELETE FROM pact._reponse WHERE ref=?",
            "DELETE FROM pact._avisimage WHERE idc=?",
            "DELETE FROM pact._blacklist WHERE idc=?",
            "DELETE FROM pact._avis WHERE idc=?",
            "DELETE FROM pact._commentaire WHERE idc=?"
          ];

          $stmt = $conn->prepare("SELECT idc FROM pact.avis WHERE idoffre=?;");
          $stmt -> execute([$idOffre]);
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            deleteOffer($queries, $row["idc"]);
          }

          // Suppression des données de l'offre
          $queries = [
            "DELETE FROM pact._illustre WHERE idoffre=?;",
            "DELETE FROM pact._abonner WHERE idoffre=?;",
            "DELETE FROM pact._consulter WHERE idoffre=?;",
            "DELETE FROM pact._localisation WHERE idoffre=?;",
            "DELETE FROM pact._horairemidi WHERE idoffre=?;",
            "DELETE FROM pact._horaireprecise WHERE idoffre=?;",
            "DELETE FROM pact._horairesoir WHERE idoffre=?;",
            "DELETE FROM pact._offre WHERE idoffre=?;"
          ];

          deleteOffer($queries, $idOffre);
          // Redirection vers l'offre
          header("location: ../index.php");
        exit();
        break;
    }
  }
  header("location: ../index.php");
  exit();
?>