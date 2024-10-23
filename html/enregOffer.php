<?php
$pageDirection = isset($_POST['pageCurrent']) ? $_POST['pageCurrent'] : 1;
$idOffre = $_POST["idOffre"];
$idUser = $_POST["idUser"];

$idUser = 4;

session_start();
require_once 'db.php';

print($_POST);

/* Création d'une nouvelle offre */
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pageBefore'])) {
  $pageBefore = $_POST['pageBefore'];
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
  
    /* Définition de l'abonnement */
    try {
      $stmt = $conn->prepare("INSERT INTO pact._abonner (idoffre, nomabonnement) VALUES (?, ?, ?, null, null, null, null, null, null, null, ?)");
      $stmt->execute([$idUser, 'inactif', $idOffre, $date]);
    } catch (PDOException $e) {
      echo "Une erreur s'est produite lors de la saisit de l'abonnement de l'offre: \n" . $e->getMessage() . "\n";
    }
  } else {
    switch ($pageBefore) {
      case 1:
        // Modification des options
        break;
      
      case 2:
        // Détails offre update
        // Faire un switch suivant le type d'offre (restaurant, ...)
        break;
      
      case 3:
        // Détails Localisation update
        break;

      case 4:
        // Détails Contact update
        break;

      case 5:
        // Détails Horaires update
        break;

      case 6:
        // Détails Prévisualisation update
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
/*
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
}*/
?>
<script>
    document.getElementById('myForm').submit();
</script>