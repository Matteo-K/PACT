<?php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once '../config.php';
    
    $idOffre = $_POST["idoffre"];
    $idAvis = $_POST["idavis"];
    $signaleur = $_POST["signaleur"];

    switch ($_POST["action"]) {
      case 'visualiser':
        ?>
          <form id="visualiser" action="../detailsOffer.php#avis<?= $idAvis ?>" method="POST" onsubmit="window.open('', '_blank'); this.target='_blank';">
            <input type="hidden" name="idoffre" value="<?php echo $idOffre; ?>">
          </form>
          <script>
            document.getElementById('visualiser').submit();
          </script>
        <?php
        break;

      case 'rejeter':
        $stmt = $conn->prepare("DELETE FROM pact._signalementc WHERE idu=? AND idc=?;");
        $stmt->execute([$signaleur, $idAvis]);
        
        // Redirection vers l'offre
        header("location: ../index.php");
        exit();
        break;
        
        case 'supprimer':
          function deleteOffer($queries, $data) {
            global $conn;
            
            foreach ($queries as $query) {
              $stmt = $conn->prepare($query);
              $stmt->execute([$data]);
            }
          }

          $queries = [
            "DELETE FROM pact._signalementc WHERE idc=?",
            "DELETE FROM pact._reponse WHERE idc=?",
            "DELETE FROM pact._reponse WHERE ref=?",
            "DELETE FROM pact._avisimage WHERE idc=?",
            "DELETE FROM pact._blacklist WHERE idc=?",
            "DELETE FROM pact._avis WHERE idc=?",
            "DELETE FROM pact._commentaire WHERE idc=?"
          ];

          deleteOffer($queries, $idAvis);

          header("location: ../index.php");
          exit();
          break;
    }
  }
  header("location: ../index.php");
  exit();
?>