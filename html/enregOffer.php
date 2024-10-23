<?php
$page = isset($_POST['page']) ? $_POST['page'] : 1;
$idOffre = isset($_POST["idOffre"])?$_POST["idOffre"]:"";
$idUser = $_POST["idUser"];

session_start();
require_once 'db.php';

/* Création d'une nouvelle offre */
if (empty($idOffre)) {
  /* obtention de la nouvelle id de l'offre */
  try {
    $stmt = $conn->prepare("SELECT idoffre FROM _offre ORDER BY idoffre DESC LIMIT 1");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    print_r($result);
    $idOffre = $result["idoffre"]+1;
  } catch (PDOException $e) {
      echo "Une erreur s'est produite lors de la récupération de l'offre: " . $e->getMessage();
  }

  /* Obtention de la date current */
  $currentDateTime = new DateTime();
  $date = $currentDateTime->format('Y-m-d H:i:s.u');

  /* création d'une offre avec la nouvelle id */
  $stmt = $conn->prepare("INSERT INTO pact._offre (idu, statut, idoffre, nom, description, mail, telephone, affiche, urlsite, resume, datecrea) VALUES (?, ?, ?, null, null, null, null, null, null, null, ?)");
  $stmt->execute([$idUser, 'inactif', $idOffre, $date]);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $page < 1) {
  ?>
  <form id="myForm" action="manageOffer.php" method="POST">
    <input type="hidden" name="page" value="<?php echo $page; ?>">
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