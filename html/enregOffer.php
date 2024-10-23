<?php
$page = isset($_POST['page']) ? $_POST['page'] : 1;
$idOffre = isset($_POST["idOffre"])?$_POST["idOffre"]:"";
$idUser = $_POST["idUser"];

$idUser = 4;
session_start();
require_once 'db.php';

/* Création d'une nouvelle offre */
if (empty($idOffre)) {
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