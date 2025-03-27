<?php
  require_once "../config.php";
  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $idOffre = $_POST['idOffre'];

    // Ajout de la demande de l'offre
    $stmt = $conn->prepare("UPDATE pact._offre SET statut='delete' WHERE idoffre=?;");
    $stmt->execute([$idOffre]);

    // Redirection vers l'offre
    ?>
      <form id="myForm" action="detailsOffer.php" method="POST">
        <input type="hidden" name="idoffre" value="<?php echo $idOffre; ?>">
      </form>
      <script>
        document.getElementById('myForm').submit();
      </script>
    <?php
  }

  // Redirection vers la page d'accueil
  header("Location: ../index.php");
  exit();

  ?>