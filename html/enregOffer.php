<?php
$page = isset($_POST['page']) ? $_POST['page'] : 1;
$idOffre = isset($_POST["idOffre"])?$_POST["idOffre"]:"";
if ($idOffre == "") {
  /* Incrémente depuis l'id la plus grande */
  // Select id from offre order by id limit 1;
  $idOffre = 15;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $page != -1) {

}



if ($page < 1) {
  header("Location: search.php");
  exit();
} else {
  
}
?>
<form id="myForm" action="manageOffer.php" method="POST">
    <input type="hidden" name="page" value="<?php echo $page; ?>">
    <input type="hidden" name="idOffre" value="<?php echo $idOffre; ?>">
</form>
<script>
    document.getElementById('myForm').submit();
</script>