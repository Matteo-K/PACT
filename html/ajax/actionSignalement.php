<?php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once '../config.php';
    
    $idOffre = $_POST["idoffre"];
    $idAvis = $_POST["idavis"];
    $signaleur = $_POST["signaleur"];
    $type = $_POST["type"];

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

          if ($type === "avis") {
            $stmt = $conn->prepare("DELETE FROM pact._signalementc WHERE idu=? AND idc=?;");
            $stmt->execute([$signaleur, $idAvis]);
  
            $stmt = $conn->prepare("DELETE FROM pact._avis WHERE idc=?;");
            $stmt->execute([$idAvis]);
          } else if ($type == "reponse") {
          }
          header("location: ../index.php");
          exit();
          break;
    }
  }
  header("location: ../index.php");
  exit();
?>