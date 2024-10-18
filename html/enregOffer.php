<?php
$page = isset($_POST['page']) ? $_POST['page'] : 1;
$idOffre = $_POST["idOffre"];
if ($idOffre == "") {
  /* Incrémente depuis l'id la plus grande */
  // Select id from offre order by id limit 1;
  $idOffre = 15;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $page != -1) {

}



if ($page == -1 || $page == 0) {
  header("Location: search.php");
  exit();
} else {
  
}

header("Location: manageOffer.php?page=" . $page."&idOffre=".$idOffre);
exit();
?>